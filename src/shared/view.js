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
