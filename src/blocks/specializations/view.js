/* Program cards scroll like a marquee: a steady glide to the left that loops without a jump (the cards are
   repeated once after the originals). Pauses while the visitor hovers, touches or focuses the row; swiping
   still works. Off for "reduce motion" and when every card already fits. */
import { each, reduceMotion } from '../../shared/view';

const SPEED = 40; // px per second

each( '.wp-block-imet-specializations .spec-grid', ( grid ) => {
	const cards = [ ...grid.querySelectorAll( '.spec-card' ) ];
	if ( reduceMotion() || cards.length < 2 || grid.scrollWidth <= grid.clientWidth + 2 ) {
		return;
	}
	// One copy of the cards after the originals, hidden from screen readers and the tab order.
	cards.forEach( ( card ) => {
		const copy = card.cloneNode( true );
		copy.setAttribute( 'aria-hidden', 'true' );
		copy.querySelectorAll( 'a' ).forEach( ( a ) => a.setAttribute( 'tabindex', '-1' ) );
		grid.appendChild( copy );
	} );
	grid.classList.add( 'is-marquee' );

	// Width of one full set of cards (start of the first copy).
	const loopWidth = () => grid.children[ cards.length ].offsetLeft - cards[ 0 ].offsetLeft;

	let pos = 0;
	let last = 0;
	let paused = false;
	let visible = false;
	let frame = 0;
	const tick = ( now ) => {
		frame = requestAnimationFrame( tick );
		const dt = last ? Math.min( now - last, 100 ) : 0;
		last = now;
		if ( paused || ! visible ) {
			return;
		}
		pos += ( SPEED * dt ) / 1000;
		const w = loopWidth();
		if ( pos >= w ) {
			pos -= w;
		}
		grid.scrollLeft = pos;
	};
	const pause = () => {
		paused = true;
	};
	const resume = () => {
		const w = loopWidth();
		pos = grid.scrollLeft % w; // continue from where a swipe left it
		paused = false;
	};
	grid.addEventListener( 'mouseenter', pause );
	grid.addEventListener( 'mouseleave', resume );
	grid.addEventListener( 'touchstart', pause, { passive: true } );
	grid.addEventListener( 'touchend', () => setTimeout( resume, 1500 ) );
	grid.addEventListener( 'focusin', pause );
	grid.addEventListener( 'focusout', resume );
	new IntersectionObserver( ( [ e ] ) => {
		visible = e.isIntersecting;
		last = 0;
	} ).observe( grid );
	frame = requestAnimationFrame( tick );
} );
