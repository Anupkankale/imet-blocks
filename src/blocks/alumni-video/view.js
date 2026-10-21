/* Video: the thumbnail links to YouTube; play it in place instead. */
import { each } from '../../shared/view';

each( '.wp-block-imet-alumni-video .video-thumb a', ( a ) => {
	const m = a.href.match( /(?:youtu\.be\/|[?&]v=|\/embed\/|\/shorts\/)([\w-]{11})/ );
	if ( ! m ) {
		return; // no video set yet: the link behaves normally
	}
	a.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		const frame = document.createElement( 'iframe' );
		frame.src = 'https://www.youtube.com/embed/' + m[ 1 ] + '?autoplay=1';
		frame.title = a.querySelector( 'img' ) ? a.querySelector( 'img' ).alt : 'Video';
		frame.allow = 'autoplay; encrypted-media; picture-in-picture';
		frame.allowFullscreen = true;
		const thumb = a.closest( '.video-thumb' );
		thumb.classList.add( 'is-playing' );
		thumb.appendChild( frame );
	} );
} );
