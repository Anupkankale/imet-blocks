/* Helpers for the blocks' front-end scripts (each block's view.js only touches its own blocks). */
export const reduceMotion = () => window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

/** Run fn for every element matching selector once the DOM is ready. */
export function each( selector, fn ) {
	const run = () => document.querySelectorAll( selector ).forEach( fn );
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', run );
	} else {
		run();
	}
}

/** Accordion of core/details blocks: one item open at a time. */
export function accordion( group, { openFirst = false } = {} ) {
	const items = group.querySelectorAll( 'details' ); // also inside .wp-block-group__inner-container (classic themes)
	if ( openFirst && items[ 0 ] && ! group.querySelector( 'details[open]' ) ) {
		items[ 0 ].open = true;
	}
	items.forEach( ( d ) =>
		d.addEventListener( 'toggle', () => {
			if ( d.open ) {
				items.forEach( ( o ) => o !== d && ( o.open = false ) );
			}
		} )
	);
}

/**
 * Marquee: the row glides left at `speed` px/s and loops without a jump (the items are repeated once,
 * marked .is-clone and hidden from screen readers and the tab order). It only moves while the row actually
 * overflows (so CSS decides per screen size), is on screen and isn't hovered, focused or touched; swiping still
 * works. Off for "reduce motion".
 */
export function marquee( row, items, { speed = 40 } = {} ) {
	if ( reduceMotion() || items.length < 2 ) {
		return;
	}
	items.forEach( ( item ) => {
		const copy = item.cloneNode( true );
		copy.classList.add( 'is-clone' );
		copy.setAttribute( 'aria-hidden', 'true' );
		copy.querySelectorAll( 'a' ).forEach( ( a ) => a.setAttribute( 'tabindex', '-1' ) );
		row.appendChild( copy );
	} );
	row.classList.add( 'is-marquee' );

	const firstCopy = row.children[ items.length ];
	const loopWidth = () => firstCopy.offsetLeft - items[ 0 ].offsetLeft;
	let pos = 0;
	let last = 0;
	let paused = false;
	let visible = false;
	const tick = ( now ) => {
		requestAnimationFrame( tick );
		const dt = last ? Math.min( now - last, 100 ) : 0;
		last = now;
		const w = loopWidth();
		if ( paused || ! visible || w <= 0 || row.scrollWidth <= row.clientWidth + 2 ) {
			return;
		}
		pos += ( speed * dt ) / 1000;
		if ( pos >= w ) {
			pos -= w;
		}
		row.scrollLeft = pos;
	};
	const pause = () => {
		paused = true;
	};
	const resume = () => {
		const w = loopWidth();
		pos = w > 0 ? row.scrollLeft % w : 0; // continue from where a swipe left it
		paused = false;
	};
	row.addEventListener( 'mouseenter', pause );
	row.addEventListener( 'mouseleave', resume );
	row.addEventListener( 'touchstart', pause, { passive: true } );
	row.addEventListener( 'touchend', () => setTimeout( resume, 1500 ) );
	row.addEventListener( 'focusin', pause );
	row.addEventListener( 'focusout', resume );
	new IntersectionObserver( ( [ e ] ) => {
		visible = e.isIntersecting;
		last = 0;
	} ).observe( row );
	requestAnimationFrame( tick );
}
