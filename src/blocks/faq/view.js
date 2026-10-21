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
	more.querySelector( 'a' )?.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		group.classList.add( 'is-expanded' );
		more.classList.add( 'is-hidden' );
	} );
} );
