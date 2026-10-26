import { each } from '../../shared/view';

/* Home page: arrows under the row of cards scroll it one card at a time. */
each( '.wp-block-imet-step-cards.is-style-home:not(.is-cases)', ( section ) => {
	const grid = section.querySelector( '.step-grid' );
	const card = grid && grid.querySelector( '.step-card' );
	if ( ! card ) {
		return;
	}
	const nav = document.createElement( 'div' );
	nav.className = 'sc-nav';
	[ [ 'Previous', -1 ], [ 'Next', 1 ] ].forEach( ( [ label, dir ] ) => {
		const b = document.createElement( 'button' );
		b.type = 'button';
		b.setAttribute( 'aria-label', label );
		b.addEventListener( 'click', () => grid.scrollBy( { left: dir * ( card.offsetWidth + 10 ), behavior: 'smooth' } ) );
		nav.appendChild( b );
	} );
	grid.after( nav );
} );
