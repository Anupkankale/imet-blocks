/* Testimonial slider: auto-advances every 4s; pauses on hover, touch and focus; swipe/scroll by hand. */
import { each, marquee, reduceMotion } from '../../shared/view';

each( '.wp-block-imet-testimonials .slider-track', ( track ) => {
	if ( ! track.querySelector( '.t-card' ) ) {
		return;
	}
	const still = reduceMotion();
	const cardWidth = () => track.querySelector( '.t-card' ).offsetWidth + 12;
	if ( track.clientWidth > 900 ) {
		track.scrollLeft = cardWidth() - 62;
	}
	const next = () => {
		const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 5;
		track.scrollTo( { left: atEnd ? 0 : track.scrollLeft + cardWidth(), behavior: still ? 'auto' : 'smooth' } );
	};
	let timer = still ? null : setInterval( next, 4000 );
	const pause = () => clearInterval( timer );
	const resume = () => {
		clearInterval( timer );
		if ( ! still ) {
			timer = setInterval( next, 4000 );
		}
	};
	track.addEventListener( 'mouseenter', pause );
	track.addEventListener( 'mouseleave', resume );
	track.addEventListener( 'touchstart', pause, { passive: true } );
	track.addEventListener( 'touchend', resume );
	track.addEventListener( 'focusin', pause );
	track.addEventListener( 'focusout', resume );
} );

/* Home page: the client testimonials glide sideways; the success stories get arrows under the row. */
each( '.wp-block-imet-testimonials.is-clients .testi-grid', ( grid ) => marquee( grid, [ ...grid.querySelectorAll( '.testi-card' ) ], { speed: 30 } ) );
each( '.wp-block-imet-testimonials.is-stories .testi-grid', ( grid ) => {
	const card = grid.querySelector( '.testi-card' );
	if ( ! card ) {
		return;
	}
	const nav = document.createElement( 'div' );
	nav.className = 'sc-nav is-testi';
	[ [ 'Previous', -1 ], [ 'Next', 1 ] ].forEach( ( [ label, dir ] ) => {
		const b = document.createElement( 'button' );
		b.type = 'button';
		b.setAttribute( 'aria-label', label );
		b.addEventListener( 'click', () => grid.scrollBy( { left: dir * ( card.offsetWidth + 10 ), behavior: 'smooth' } ) );
		nav.appendChild( b );
	} );
	grid.after( nav );
} );
