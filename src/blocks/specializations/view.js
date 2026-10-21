/* Program cards auto-scroll: one card every 3s, back to the start after the last; pauses while the visitor
   hovers, touches or focuses the row. Off for "reduce motion" and when every card already fits. */
import { each, reduceMotion } from '../../shared/view';

each( '.wp-block-imet-specializations .spec-grid', ( grid ) => {
	if ( reduceMotion() ) {
		return;
	}
	const step = () => {
		const card = grid.querySelector( '.spec-card' );
		return card ? card.offsetWidth + parseFloat( getComputedStyle( grid ).columnGap || 0 ) : grid.clientWidth;
	};
	const next = () => {
		if ( grid.scrollWidth <= grid.clientWidth + 2 ) {
			return; // nothing to scroll
		}
		const atEnd = grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 2;
		grid.scrollTo( { left: atEnd ? 0 : grid.scrollLeft + step(), behavior: 'smooth' } );
	};
	let timer = null;
	const start = () => {
		clearInterval( timer );
		timer = setInterval( next, 3000 );
	};
	const stop = () => clearInterval( timer );

	grid.addEventListener( 'mouseenter', stop );
	grid.addEventListener( 'mouseleave', start );
	grid.addEventListener( 'touchstart', stop, { passive: true } );
	grid.addEventListener( 'touchend', start );
	grid.addEventListener( 'focusin', stop );
	grid.addEventListener( 'focusout', start );
	// Only run while the section is on screen.
	new IntersectionObserver( ( [ e ] ) => ( e.isIntersecting ? start() : stop() ) ).observe( grid );
} );
