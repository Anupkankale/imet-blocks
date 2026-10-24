/** @jest-environment jsdom */
import { each, accordion, reduceMotion } from '../../src/shared/view';

const FAQ = `
<div class="faq-acc">
	<details><summary>One</summary><p>A1</p></details>
	<details><summary>Two</summary><p>A2</p></details>
	<details><summary>Three</summary><p>A3</p></details>
</div>`;

beforeEach( () => {
	document.body.innerHTML = FAQ;
	// jsdom has no matchMedia and no Element.animate: "reduce motion" makes the accordion toggle instantly.
	window.matchMedia = jest.fn().mockReturnValue( { matches: true } );
} );

describe( 'each', () => {
	it( 'runs for every match when the DOM is ready', () => {
		const seen = [];
		each( 'details', ( d ) => seen.push( d.textContent ) );
		expect( seen ).toHaveLength( 3 );
	} );
} );

describe( 'reduceMotion', () => {
	it( 'reads prefers-reduced-motion', () => {
		expect( reduceMotion() ).toBe( true );
		expect( window.matchMedia ).toHaveBeenCalledWith( '(prefers-reduced-motion: reduce)' );
	} );
} );

describe( 'accordion', () => {
	const setup = ( opts ) => {
		const group = document.querySelector( '.faq-acc' );
		accordion( group, opts );
		return [ ...group.querySelectorAll( 'details' ) ];
	};

	it( 'wraps each answer in .acc-body and leaves the summary alone', () => {
		const [ first ] = setup();
		expect( first.querySelector( ':scope > summary' ).textContent ).toBe( 'One' );
		expect( first.querySelector( ':scope > .acc-body p' ).textContent ).toBe( 'A1' );
	} );

	it( 'opens the first item only when asked', () => {
		expect( setup().map( ( d ) => d.open ) ).toEqual( [ false, false, false ] );
		document.body.innerHTML = FAQ;
		expect( setup( { openFirst: true } ).map( ( d ) => d.open ) ).toEqual( [ true, false, false ] );
	} );

	it( 'keeps one item open at a time', () => {
		const items = setup( { openFirst: true } );
		items[ 1 ].querySelector( 'summary' ).click();
		expect( items.map( ( d ) => d.open ) ).toEqual( [ false, true, false ] );
	} );

	it( 'closes an open item when its question is clicked again', () => {
		const items = setup( { openFirst: true } );
		items[ 0 ].querySelector( 'summary' ).click();
		expect( items[ 0 ].open ).toBe( false );
	} );

	it( 'prevents the native details toggle', () => {
		const [ first ] = setup();
		const event = new MouseEvent( 'click', { bubbles: true, cancelable: true } );
		first.querySelector( 'summary' ).dispatchEvent( event );
		expect( event.defaultPrevented ).toBe( true );
	} );
} );
