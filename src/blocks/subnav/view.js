/* Sticky section bar: highlights the link of the section in view, and in-page links land below the bar. */
import { each, reduceMotion } from '../../shared/view';

const find = ( hash ) => {
	try {
		return hash.length > 1 ? document.querySelector( hash ) : null;
	} catch {
		return null; // not a valid selector
	}
};

each( '.wp-block-imet-subnav', ( subnav ) => {
	const links = subnav.querySelectorAll( '.subnav-links a' );
	if ( links[ 0 ] ) {
		links[ 0 ].classList.add( 'active' );
	}
	const spy = new IntersectionObserver(
		( entries ) =>
			entries.forEach( ( e ) => {
				if ( e.isIntersecting ) {
					links.forEach( ( a ) => a.classList.toggle( 'active', a.getAttribute( 'href' ) === '#' + e.target.id ) );
				}
			} ),
		{ rootMargin: '-45% 0px -50% 0px' }
	);
	links.forEach( ( a ) => {
		const target = find( a.getAttribute( 'href' ) );
		if ( target ) {
			spy.observe( target );
		}
	} );

	// Every in-page link inside the IMET sections scrolls to just below the bar (and the admin bar when logged in).
	document.querySelectorAll( '.imet-section a[href^="#"]' ).forEach( ( a ) => {
		a.addEventListener( 'click', ( e ) => {
			const target = find( a.getAttribute( 'href' ) );
			if ( ! target ) {
				return;
			}
			e.preventDefault();
			const adminBar = document.getElementById( 'wpadminbar' );
			const offset = subnav.offsetHeight + ( adminBar ? adminBar.offsetHeight : 0 ) + 10;
			window.scrollTo( {
				top: target.getBoundingClientRect().top + window.scrollY - offset,
				behavior: reduceMotion() ? 'auto' : 'smooth',
			} );
		} );
	} );
} );
