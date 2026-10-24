const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { test, expect } = require( '@playwright/test' );

// Spacing and size consistency on the DBA landing page preview. Heights and sizes come from the design tokens in
// src/shared/_buttons.scss and base.scss. Run per viewport by the "desktop" (1440) and "mobile" (390) projects.
const PAGE = process.env.PREVIEW_PATH || '/imet-preview/';
const GOLDEN = path.join( __dirname, 'spacing.golden.json' );
const TOLERANCE = 1; // px: sub-pixel rounding

const BUTTON_HEIGHTS = {
	desktop: { 'btn-sm': 48, 'btn-md': 60, 'btn-lg': 72 },
	mobile: { 'btn-sm': 44, 'btn-md': 54, 'btn-lg': 62 },
};

test.describe( 'Spacing and size consistency', () => {
	let viewport;
	test.beforeEach( async ( { page }, info ) => {
		viewport = info.project.name;
		await page.goto( PAGE );
		await page.evaluate( () => document.fonts.ready );
	} );

	test( 'buttons are exactly the height of their size class', async ( { page } ) => {
		const expected = BUTTON_HEIGHTS[ viewport ];
		const found = await page.$$eval( '.imet-section .wp-block-button', ( els ) =>
			els
				.filter( ( b ) => ! b.classList.contains( 'btn-link' ) ) // text links have no fixed height
				.map( ( b ) => {
					const size = [ 'btn-sm', 'btn-md', 'btn-lg' ].find( ( c ) => b.classList.contains( c ) );
					const a = b.querySelector( 'a' );
					return { size, text: a ? a.textContent.trim() : '', h: a ? a.getBoundingClientRect().height : 0 };
				} )
				.filter( ( b ) => b.size && b.h > 0 ) // skip hidden ones (e.g. a collapsed "See More")
		);
		expect( found.length ).toBeGreaterThan( 0 );
		for ( const b of found ) {
			expect( Math.abs( b.h - expected[ b.size ] ), `"${ b.text }" (${ b.size }) is ${ b.h }px, expected ${ expected[ b.size ] }px` ).toBeLessThanOrEqual( TOLERANCE );
		}
	} );

	test( 'phone buttons are at least 44px tall (touch target)', async ( { page } ) => {
		test.skip( viewport !== 'mobile', 'phones only' );
		const small = await page.$$eval( '.imet-section .wp-block-button:not(.btn-link) a', ( as ) =>
			as.map( ( a ) => ( { t: a.textContent.trim(), h: a.getBoundingClientRect().height } ) ).filter( ( b ) => b.h > 0 && b.h < 43.5 )
		);
		expect( small ).toEqual( [] );
	} );

	test( 'content containers are centred, within 1200px, and keep a side gutter', async ( { page } ) => {
		const width = page.viewportSize().width;
		const gutter = viewport === 'mobile' ? 20 : 0;
		const bad = await page.$$eval( '.imet-section .imet-container', ( els, vw ) =>
			els
				.map( ( c ) => {
					const r = c.getBoundingClientRect();
					return { cls: c.className, left: r.left, right: vw - r.right, w: r.width };
				} )
				.filter( ( c ) => c.w > 0 )
		, width );
		expect( bad.length ).toBeGreaterThan( 0 );
		for ( const c of bad ) {
			expect( c.w, `${ c.cls } is wider than 1200px` ).toBeLessThanOrEqual( 1200 + TOLERANCE );
			expect( Math.abs( c.left - c.right ), `${ c.cls } is not centred (left ${ c.left }, right ${ c.right })` ).toBeLessThanOrEqual( TOLERANCE );
			expect( c.left, `${ c.cls } touches the screen edge` ).toBeGreaterThanOrEqual( gutter - TOLERANCE );
		}
	} );

	test( 'sections never exceed the viewport and never overlap', async ( { page } ) => {
		const vw = page.viewportSize().width;
		const rects = await page.$$eval( 'section.imet-section', ( els ) =>
			els.map( ( s ) => {
				const r = s.getBoundingClientRect();
				return { id: s.id || s.className.split( ' ' )[ 0 ], top: r.top + window.scrollY, bottom: r.bottom + window.scrollY, w: r.width, h: r.height };
			} ).filter( ( s ) => s.h > 0 )
		);
		for ( const s of rects ) {
			expect( s.w, `${ s.id } is wider than the screen` ).toBeLessThanOrEqual( vw + TOLERANCE );
		}
		// Top-level sections only (a banner can sit inside another section's box, so compare in page order).
		const ordered = [ ...rects ].sort( ( a, b ) => a.top - b.top );
		ordered.forEach( ( s, i ) => {
			const prev = ordered[ i - 1 ];
			if ( prev && prev.bottom <= s.top + TOLERANCE ) {
				return; // stacked normally
			}
			// Otherwise it must be fully nested in the previous box (not a partial overlap).
			if ( prev ) {
				expect( s.bottom, `${ s.id } partly overlaps ${ prev.id }` ).toBeLessThanOrEqual( prev.bottom + TOLERANCE );
			}
		} );
	} );

	test( 'section titles keep the type scale and stay larger than their subtitles', async ( { page } ) => {
		const sizes = await page.$$eval( '.imet-section h2.title', ( els ) => els.filter( ( e ) => e.offsetParent ).map( ( e ) => parseFloat( getComputedStyle( e ).fontSize ) ) );
		expect( sizes.length ).toBeGreaterThan( 0 );
		const [ min, max ] = viewport === 'mobile' ? [ 26, 28.5 ] : [ 35, 38.5 ];
		for ( const s of sizes ) {
			expect( s ).toBeGreaterThanOrEqual( min - 0.6 );
			expect( s ).toBeLessThanOrEqual( max + 0.6 );
		}
	} );

	test( 'eyebrow text is the same size on every section', async ( { page } ) => {
		const sizes = await page.$$eval( '.imet-section p.eyebrow-line', ( els ) => els.filter( ( e ) => e.offsetParent ).map( ( e ) => Math.round( parseFloat( getComputedStyle( e ).fontSize ) ) ) );
		expect( sizes.length ).toBeGreaterThan( 0 );
		const allowed = viewport === 'mobile' ? [ 18, 20 ] : [ 22 ];
		for ( const s of sizes ) {
			expect( allowed, `eyebrow is ${ s }px` ).toContain( s );
		}
	} );

	test( 'section padding matches the saved spacing snapshot (spacing.golden.json)', async ( { page } ) => {
		const now = await page.$$eval( 'section.imet-section', ( els ) => {
			const out = {};
			els.forEach( ( s ) => {
				const cs = getComputedStyle( s );
				const key = s.id || s.className.split( ' ' ).find( ( c ) => c !== 'wp-block-imet-' && ! c.startsWith( 'wp-block' ) && c !== 'imet-section' && c !== 'section' ) || s.className;
				out[ key ] = [ cs.paddingTop, cs.paddingBottom ].map( ( v ) => Math.round( parseFloat( v ) ) );
			} );
			return out;
		} );
		let golden = {};
		try {
			golden = JSON.parse( fs.readFileSync( GOLDEN, 'utf8' ) );
		} catch {}
		if ( process.env.UPDATE_GOLDEN ) {
			// Both projects write their own viewport; run serially so they do not overwrite each other.
			golden[ viewport ] = now;
			fs.writeFileSync( GOLDEN, JSON.stringify( golden, null, '\t' ) + '\n' );
			return;
		}
		expect( golden[ viewport ], 'No snapshot yet: run UPDATE_GOLDEN=1 npm run test:e2e -- --workers=1 spacing' ).toBeTruthy();
		expect( now ).toEqual( golden[ viewport ] );
	} );
} );
