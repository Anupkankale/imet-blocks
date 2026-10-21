/* Program cards: prev/next buttons scroll the row by one card; hidden at the ends and when everything fits. */
import { each } from '../../shared/view';

const icon = ( d ) => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="${ d }"/></svg>`;

each( '.wp-block-imet-specializations .spec-grid', ( grid ) => {
	const wrap = document.createElement( 'div' );
	wrap.className = 'spec-scroller';
	grid.parentNode.insertBefore( wrap, grid );
	wrap.appendChild( grid );

	const button = ( cls, label, path, dir ) => {
		const b = document.createElement( 'button' );
		b.type = 'button';
		b.className = 'spec-nav ' + cls;
		b.setAttribute( 'aria-label', label );
		b.innerHTML = icon( path );
		b.addEventListener( 'click', () => {
			const card = grid.querySelector( '.spec-card' );
			const step = card ? card.offsetWidth + parseFloat( getComputedStyle( grid ).columnGap || 0 ) : grid.clientWidth;
			grid.scrollBy( { left: dir * step, behavior: 'smooth' } );
		} );
		wrap.appendChild( b );
		return b;
	};
	const prev = button( 'prev', 'Previous programs', 'M15 6l-6 6 6 6', -1 );
	const next = button( 'next', 'More programs', 'M9 6l6 6-6 6', 1 );

	const update = () => {
		prev.disabled = grid.scrollLeft <= 2;
		next.disabled = grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 2;
	};
	grid.addEventListener( 'scroll', update, { passive: true } );
	window.addEventListener( 'resize', update );
	update();
} );
