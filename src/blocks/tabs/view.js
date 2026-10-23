/* Tabs: the labels in .tab-nav open the panel with the same position in .tab-panels (first one open).
   ARIA tabs pattern: click, or ←/→/Home/End to move between labels. The editor shows every panel. */
import { each } from '../../shared/view';

let uid = 0;

each( '.wp-block-imet-tabs', ( section ) => {
	const nav = section.querySelector( '.tab-nav' );
	const tabs = nav ? [ ...nav.querySelectorAll( '.tab-label' ) ] : [];
	const panels = [ ...section.querySelectorAll( '.tab-panel' ) ];
	const count = Math.min( tabs.length, panels.length );
	if ( count < 2 ) {
		return;
	}
	const id = 'imet-tabs-' + ++uid;
	nav.setAttribute( 'role', 'tablist' );
	const select = ( index, focus = false ) => {
		for ( let i = 0; i < count; i++ ) {
			const on = i === index;
			tabs[ i ].classList.toggle( 'is-active', on );
			tabs[ i ].setAttribute( 'aria-selected', on ? 'true' : 'false' );
			tabs[ i ].tabIndex = on ? 0 : -1;
			panels[ i ].hidden = ! on;
		}
		if ( focus ) {
			tabs[ index ].focus();
		}
	};
	for ( let i = 0; i < count; i++ ) {
		tabs[ i ].id = `${ id }-tab-${ i }`;
		tabs[ i ].setAttribute( 'role', 'tab' );
		tabs[ i ].setAttribute( 'aria-controls', `${ id }-panel-${ i }` );
		panels[ i ].id = `${ id }-panel-${ i }`;
		panels[ i ].setAttribute( 'role', 'tabpanel' );
		panels[ i ].setAttribute( 'aria-labelledby', tabs[ i ].id );
		tabs[ i ].addEventListener( 'click', () => select( i ) );
		tabs[ i ].addEventListener( 'keydown', ( e ) => {
			const keys = { ArrowRight: i + 1, ArrowLeft: i - 1, Home: 0, End: count - 1 };
			if ( e.key in keys ) {
				e.preventDefault();
				select( ( keys[ e.key ] + count ) % count, true );
			} else if ( e.key === 'Enter' || e.key === ' ' ) {
				e.preventDefault();
				select( i );
			}
		} );
	}
	panels.slice( count ).forEach( ( p ) => ( p.hidden = true ) );
	select( 0 );
	section.querySelector( '.tab-panels' )?.classList.add( 'is-ready' );
} );
