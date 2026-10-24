const { test, expect } = require( '@playwright/test' );

// The DBA landing page preview (built from patterns/dba-landing.php). Override with PREVIEW_PATH.
const PAGE = process.env.PREVIEW_PATH || '/imet-preview/';

test.describe( 'DBA landing page', () => {
	let errors;
	test.beforeEach( async ( { page } ) => {
		errors = [];
		page.on( 'pageerror', ( e ) => errors.push( e.message ) );
		await page.goto( PAGE );
	} );

	test( 'renders every section with one h1 and no script errors', async ( { page } ) => {
		await expect( page.locator( 'h1' ) ).toHaveCount( 1 );
		expect( await page.locator( '.imet-section' ).count() ).toBeGreaterThan( 10 );
		expect( errors ).toEqual( [] );
	} );

	test( 'has no horizontal scroll', async ( { page } ) => {
		const overflow = await page.evaluate( () => document.documentElement.scrollWidth - window.innerWidth );
		expect( overflow ).toBeLessThanOrEqual( 0 );
	} );

	test( 'every sub-navigation link scrolls to an existing section', async ( { page } ) => {
		const targets = await page.$$eval( '.subnav a[href^="#"], .subnav-links a[href^="#"]', ( as ) => as.map( ( a ) => a.getAttribute( 'href' ) ) );
		expect( targets.length ).toBeGreaterThan( 0 );
		for ( const t of targets ) {
			await expect( page.locator( t ), `${ t } should exist` ).toHaveCount( 1 );
		}
	} );

	test( 'FAQ shows five questions, then See More reveals the rest', async ( { page } ) => {
		const faq = page.locator( '.wp-block-imet-faq' );
		const items = faq.locator( 'details' );
		const total = await items.count();
		test.skip( total <= 5, 'FAQ has five or fewer questions' );
		await faq.scrollIntoViewIfNeeded();
		await expect( items.nth( 5 ) ).toBeHidden();
		const more = faq.locator( '.faq-more a' );
		await more.click();
		await expect( items.nth( 5 ) ).toBeVisible();
		await expect( more ).toBeHidden();
	} );

	test( 'FAQ opens one answer at a time', async ( { page } ) => {
		const items = page.locator( '.wp-block-imet-faq details' );
		await items.nth( 1 ).scrollIntoViewIfNeeded();
		await items.nth( 1 ).locator( 'summary' ).click();
		await expect( items.nth( 1 ) ).toHaveAttribute( 'open', '' );
		await expect( items.nth( 0 ) ).not.toHaveAttribute( 'open', '' );
	} );

	test( 'images have alt text or are explicitly decorative', async ( { page } ) => {
		const missing = await page.$$eval( '.imet-section img', ( imgs ) => imgs.filter( ( i ) => ! i.hasAttribute( 'alt' ) ).map( ( i ) => i.src ) );
		expect( missing ).toEqual( [] );
	} );

	test( 'every button and link inside sections has a destination', async ( { page } ) => {
		const dead = await page.$$eval( '.imet-section a', ( as ) => as.filter( ( a ) => ! a.getAttribute( 'href' ) ).map( ( a ) => a.textContent.trim() ) );
		expect( dead ).toEqual( [] );
	} );
} );
