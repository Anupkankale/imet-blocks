/* Benefit cards as tabs: the first card is open; clicking (or Enter/Space on) another opens it and closes the rest. */
import { each } from '../../shared/view';

each( '.wp-block-imet-overview .benefit-list', ( list ) => {
	const cards = [ ...list.querySelectorAll( '.ben-item' ) ];
	if ( cards.length < 2 ) {
		return;
	}
	const open = ( card ) =>
		cards.forEach( ( c ) => {
			const on = c === card;
			c.classList.toggle( 'is-open', on );
			c.setAttribute( 'aria-expanded', on ? 'true' : 'false' );
		} );
	cards.forEach( ( card ) => {
		card.classList.add( 'is-tab' );
		card.setAttribute( 'role', 'button' );
		card.setAttribute( 'tabindex', '0' );
		card.addEventListener( 'click', () => open( card ) );
		card.addEventListener( 'keydown', ( e ) => {
			if ( e.key === 'Enter' || e.key === ' ' ) {
				e.preventDefault();
				open( card );
			}
		} );
	} );
	open( cards[ 0 ] );
} );
