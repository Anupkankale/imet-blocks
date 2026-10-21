/* "See More" opens the rest of the table and becomes "Close"; "Close" folds it back and returns to the table. */
import { each } from '../../shared/view';

each( '.wp-block-imet-career-table', ( section ) => {
	const table = section.querySelector( '.career-table' );
	const link = section.querySelector( '.ct-more .wp-block-button__link' );
	if ( ! table || ! link ) {
		return;
	}
	const more = link.textContent.trim() || 'See More';
	if ( ! table.id ) {
		table.id = 'imet-career-table';
	}
	link.setAttribute( 'role', 'button' );
	link.setAttribute( 'aria-controls', table.id );
	link.setAttribute( 'aria-expanded', 'false' );
	link.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		const open = table.classList.toggle( 'is-open' );
		link.textContent = open ? 'Close' : more;
		link.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		if ( ! open ) {
			table.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
		}
	} );
} );
