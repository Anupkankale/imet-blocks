/* Testimonial slider: auto-advances every 4s; pauses on hover, touch and focus; swipe/scroll by hand. */
import { each, reduceMotion } from '../../shared/view';

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
