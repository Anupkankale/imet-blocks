import { each, accordion } from '../../shared/view';

each( '.wp-block-imet-faq .faq-acc', ( group ) => accordion( group, { openFirst: true } ) );

// "See More": shows the questions after the first 5 (hidden by CSS), then hides itself.
each( '.wp-block-imet-faq', ( faq ) => {
	const group = faq.querySelector( '.faq-acc' );
	const more = faq.querySelector( '.faq-more' );
	if ( ! group || ! more ) {
		return;
	}
	if ( group.querySelectorAll( 'details' ).length <= 5 ) {
		more.classList.add( 'is-hidden' );
		return;
	}
	const link = more.querySelector( 'a' );
	if ( ! link ) {
		return;
	}
	if ( ! group.id ) {
		group.id = 'imet-faq-list';
	}
	link.setAttribute( 'role', 'button' );
	link.setAttribute( 'aria-controls', group.id );
	link.setAttribute( 'aria-expanded', 'false' );
	link.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		group.classList.add( 'is-expanded' );
		link.setAttribute( 'aria-expanded', 'true' );
		more.classList.add( 'is-hidden' );
		// The button disappears: keep keyboard focus in the list, on the first question it revealed.
		group.querySelectorAll( 'details' )[ 5 ]?.querySelector( 'summary' )?.focus();
	} );
} );
