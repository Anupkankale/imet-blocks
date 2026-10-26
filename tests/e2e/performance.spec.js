const { test, expect } = require( '@playwright/test' );

// Core Web Vitals and loading budgets for the DBA landing page preview.
// Thresholds are Google's "good" limits (LCP 2.5s, CLS 0.1, TBT 200ms, INP 200ms). The byte and request budgets sit
// above today's numbers with some room, so they flag real regressions, not noise. Override any with an env var,
// e.g. BUDGET_TOTAL_KB=2500 npm run test:e2e -- performance
// Note: a local site has no CDN, no HTTP/2 and no cache plugin; run this against staging for realistic timings.
const PAGE = process.env.PREVIEW_PATH || '/imet-preview/';
const num = ( name, fallback ) => ( process.env[ name ] ? Number( process.env[ name ] ) : fallback );

const BUDGET = {
	lcp: num( 'BUDGET_LCP_MS', 2500 ),
	cls: num( 'BUDGET_CLS', 0.1 ),
	tbt: num( 'BUDGET_TBT_MS', 200 ),
	inp: num( 'BUDGET_INP_MS', 200 ),
	totalKb: num( 'BUDGET_TOTAL_KB', 1500 ),
	jsKb: num( 'BUDGET_JS_KB', 150 ),
	requests: num( 'BUDGET_REQUESTS', 90 ),
	stylesheets: num( 'BUDGET_STYLESHEETS', 3 ),
};

const ANALYTICS = /googletagmanager\.com|google-analytics\.com|clarity\.ms|facebook\.net|doubleclick\.net/;

test.describe( 'Core Web Vitals and loading performance', () => {
	let requests;
	let sizes;

	test.beforeEach( async ( { page } ) => {
		requests = [];
		sizes = new Map();
		// Collect the vitals inside the page from the very start.
		await page.addInitScript( () => {
			window.__vitals = { cls: 0, lcp: 0, lcpEl: null, longTasks: [], inp: 0 };
			const watch = ( type, fn, extra = {} ) => {
				try {
					new PerformanceObserver( ( list ) => list.getEntries().forEach( fn ) ).observe( { type, buffered: true, ...extra } );
				} catch {}
			};
			watch( 'layout-shift', ( e ) => {
				if ( ! e.hadRecentInput ) {
					window.__vitals.cls += e.value;
				}
			} );
			watch( 'largest-contentful-paint', ( e ) => {
				window.__vitals.lcp = e.startTime;
				window.__vitals.lcpEl = e.element ? { tag: e.element.tagName, loading: e.element.getAttribute( 'loading' ) } : null;
			} );
			watch( 'longtask', ( e ) => window.__vitals.longTasks.push( e.duration ) );
			watch( 'event', ( e ) => {
				window.__vitals.inp = Math.max( window.__vitals.inp, e.duration );
			}, { durationThreshold: 16 } );
		} );
		page.on( 'request', ( r ) => requests.push( { url: r.url(), type: r.resourceType() } ) );
		page.on( 'response', async ( r ) => {
			try {
				sizes.set( r.url(), ( await r.body() ).length );
			} catch {}
		} );
		await page.goto( PAGE, { waitUntil: 'load' } );
	} );

	const scrollThrough = ( page ) =>
		page.evaluate( async () => {
			for ( let y = 0; y < document.body.scrollHeight; y += 500 ) {
				window.scrollTo( 0, y );
				await new Promise( ( r ) => setTimeout( r, 60 ) );
			}
			window.scrollTo( 0, 0 );
		} );

	const vitals = async ( page ) => {
		await scrollThrough( page );
		await page.waitForTimeout( 1000 );
		return page.evaluate( () => window.__vitals );
	};

	test( 'LCP is within budget and the LCP image is not lazy-loaded', async ( { page } ) => {
		const v = await vitals( page );
		expect( v.lcp, 'LCP never fired' ).toBeGreaterThan( 0 );
		expect( v.lcp, `LCP ${ Math.round( v.lcp ) }ms` ).toBeLessThanOrEqual( BUDGET.lcp );
		if ( v.lcpEl && v.lcpEl.tag === 'IMG' ) {
			expect( v.lcpEl.loading, 'the LCP image must not use loading="lazy"' ).not.toBe( 'lazy' );
		}
	} );

	test( 'CLS stays within budget while scrolling the whole page', async ( { page } ) => {
		const v = await vitals( page );
		expect( v.cls, `CLS ${ v.cls.toFixed( 4 ) }` ).toBeLessThanOrEqual( BUDGET.cls );
	} );

	test( 'main-thread blocking time (TBT proxy) stays within budget', async ( { page } ) => {
		const v = await vitals( page );
		const tbt = v.longTasks.reduce( ( sum, d ) => sum + Math.max( 0, d - 50 ), 0 );
		expect( tbt, `${ v.longTasks.length } long task(s), blocking ${ Math.round( tbt ) }ms` ).toBeLessThanOrEqual( BUDGET.tbt );
	} );

	test( 'interactions respond quickly (INP proxy)', async ( { page } ) => {
		const faq = page.locator( '.wp-block-imet-faq details summary' ).nth( 1 );
		await faq.scrollIntoViewIfNeeded();
		await faq.click();
		await page.locator( '.wp-block-imet-faq details summary' ).nth( 2 ).click();
		await page.waitForTimeout( 500 );
		const { inp } = await page.evaluate( () => window.__vitals );
		expect( inp, `slowest interaction ${ Math.round( inp ) }ms` ).toBeLessThanOrEqual( BUDGET.inp );
	} );

	test( 'page weight, JavaScript and request count are within budget', async ( { page } ) => {
		await scrollThrough( page );
		await page.waitForTimeout( 800 );
		const total = [ ...sizes.values() ].reduce( ( a, b ) => a + b, 0 );
		const js = requests.filter( ( r ) => r.type === 'script' ).reduce( ( a, r ) => a + ( sizes.get( r.url ) || 0 ), 0 );
		expect( total / 1024, `total ${ Math.round( total / 1024 ) }KB` ).toBeLessThanOrEqual( BUDGET.totalKb );
		expect( js / 1024, `JavaScript ${ Math.round( js / 1024 ) }KB` ).toBeLessThanOrEqual( BUDGET.jsKb );
		expect( requests.length, `${ requests.length } requests` ).toBeLessThanOrEqual( BUDGET.requests );
	} );

	test( 'styles are combined: few stylesheets, none blocking from a third party', async ( { page } ) => {
		const sheets = await page.$$eval( 'link[rel="stylesheet"]', ( ls ) => ls.map( ( l ) => l.href ) );
		expect( sheets.length, `stylesheets: ${ sheets.join( ', ' ) }` ).toBeLessThanOrEqual( BUDGET.stylesheets );
		const external = sheets.filter( ( s ) => new URL( s ).host !== new URL( page.url() ).host );
		expect( external, 'third-party stylesheets block rendering' ).toEqual( [] );
	} );

	test( 'no render-blocking scripts in <head>', async ( { page } ) => {
		const blocking = await page.$$eval( 'head script[src]:not([async]):not([defer]):not([type="module"])', ( ss ) => ss.map( ( s ) => s.src ) );
		expect( blocking ).toEqual( [] );
	} );

	test( 'fonts are self-hosted: no Google Fonts requests, and web fonts use font-display', async ( { page } ) => {
		expect( requests.filter( ( r ) => /fonts\.(googleapis|gstatic)\.com/.test( r.url ) ).map( ( r ) => r.url ) ).toEqual( [] );
		const faces = await page.evaluate( () =>
			[ ...document.styleSheets ].flatMap( ( s ) => {
				try {
					return [ ...s.cssRules ];
				} catch {
					return [];
				}
			} )
				.filter( ( r ) => r instanceof CSSFontFaceRule && /url\(/.test( r.style.getPropertyValue( 'src' ) ) )
				.map( ( r ) => ( { family: r.style.getPropertyValue( 'font-family' ), display: r.style.getPropertyValue( 'font-display' ) } ) )
		);
		expect( faces.length ).toBeGreaterThan( 0 );
		for ( const f of faces ) {
			expect( [ 'swap', 'fallback', 'optional' ], `${ f.family } has no font-display` ).toContain( f.display );
		}
	} );

	test( 'images: width/height set, below-the-fold images lazy, above-the-fold not', async ( { page } ) => {
		const vh = page.viewportSize().height;
		const imgs = await page.$$eval( '.imet-section img', ( els ) =>
			els.map( ( i ) => ( {
				src: i.currentSrc || i.src,
				dims: !! ( i.getAttribute( 'width' ) && i.getAttribute( 'height' ) ),
				lazy: i.getAttribute( 'loading' ) === 'lazy',
				top: i.getBoundingClientRect().top + window.scrollY,
				visible: i.offsetParent !== null,
			} ) )
		);
		expect( imgs.length ).toBeGreaterThan( 0 );
		for ( const i of imgs ) {
			expect( i.dims, `${ i.src } has no width/height (causes layout shift)` ).toBe( true );
			if ( i.visible && i.top > vh * 2 ) {
				expect( i.lazy, `${ i.src } is far below the fold but not lazy-loaded` ).toBe( true );
			}
			if ( i.visible && i.top < vh * 0.5 ) {
				expect( i.lazy, `${ i.src } is above the fold but lazy-loaded` ).toBe( false );
			}
		}
	} );

	test( 'analytics scripts wait for the first interaction', async ( { page } ) => {
		const delayed = await page.locator( 'script[type="imet/delayed"]' ).count();
		test.skip( delayed === 0, 'no analytics tags on this site (add GTM/gtag/Clarity to check the delay)' );
		const early = requests.filter( ( r ) => ANALYTICS.test( r.url ) );
		expect( early.map( ( r ) => r.url ), 'analytics loaded before any interaction' ).toEqual( [] );
		await page.mouse.wheel( 0, 400 );
		await expect.poll( () => requests.some( ( r ) => ANALYTICS.test( r.url ) ), { timeout: 6000 } ).toBe( true );
	} );

	test( 'text is visible while fonts load, and there is a viewport meta tag', async ( { page } ) => {
		expect( await page.locator( 'meta[name="viewport"]' ).count() ).toBeGreaterThanOrEqual( 1 );
		const hidden = await page.$$eval( '.imet-section h1, .imet-section h2', ( els ) => els.filter( ( e ) => getComputedStyle( e ).visibility === 'hidden' || getComputedStyle( e ).opacity === '0' ).length );
		expect( hidden ).toBe( 0 );
	} );

	// Known issue: templates/page-landing.php prints its own viewport tag and wp_head() prints a second one.
	// test.fail() keeps the suite green now and fails once it is fixed: then delete the test.fail line.
	test( 'exactly one viewport meta tag', async ( { page } ) => {
		test.fail( true, 'duplicate viewport meta: template + wp_head()' );
		await expect( page.locator( 'meta[name="viewport"]' ) ).toHaveCount( 1 );
	} );
} );
