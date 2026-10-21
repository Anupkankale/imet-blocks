/* Video gallery: the main player plays its YouTube video in place; a thumbnail plays its video in the main player
   (and is marked active). Videos are Image blocks linked to YouTube; without a YouTube link they act as normal links. */
import { each } from '../../shared/view';

const youtubeId = ( url ) => ( ( url || '' ).match( /(?:youtu\.be\/|[?&]v=|\/embed\/|\/shorts\/|\/live\/)([\w-]{11})/ ) || [] )[ 1 ];

function play( box, id, title ) {
	box.querySelector( 'iframe' )?.remove();
	const frame = document.createElement( 'iframe' );
	frame.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
	frame.title = title || 'Video';
	frame.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
	frame.allowFullscreen = true;
	box.classList.add( 'is-playing' );
	box.appendChild( frame );
}

const titleOf = ( a ) => a.querySelector( 'img' )?.alt;

each( '.wp-block-imet-alumni-video .video-gallery', ( gallery ) => {
	const main = gallery.querySelector( '.video-main' );
	const mainLink = main?.querySelector( 'a' );
	if ( ! main || ! mainLink ) {
		return;
	}
	const items = [ ...gallery.querySelectorAll( '.video-item' ) ];
	const setActive = ( item ) => items.forEach( ( i ) => i.classList.toggle( 'is-active', i === item ) );

	// No thumbnail is marked at first (Figma); the gold border shows the one picked.

	mainLink.addEventListener( 'click', ( e ) => {
		const id = youtubeId( mainLink.href );
		if ( id ) {
			e.preventDefault();
			play( main, id, titleOf( mainLink ) );
		}
	} );

	items.forEach( ( item ) => {
		const a = item.querySelector( 'a' );
		const id = youtubeId( a?.href );
		if ( ! id ) {
			return; // no video set yet: the link behaves normally
		}
		a.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			setActive( item );
			mainLink.href = a.href; // the main player now stands for this video
			const caption = item.querySelector( 'figcaption' );
			const mainCaption = main.querySelector( 'figcaption' );
			if ( caption && mainCaption ) {
				mainCaption.textContent = caption.textContent;
			}
			play( main, id, titleOf( a ) );
			if ( window.innerWidth < 900 ) {
				main.scrollIntoView( { behavior: 'smooth', block: 'center' } );
			}
		} );
	} );
} );

// Earlier version of the section: a single thumbnail that plays in place.
each( '.wp-block-imet-alumni-video .video-thumb a', ( a ) => {
	const id = youtubeId( a.href );
	if ( ! id ) {
		return;
	}
	a.addEventListener( 'click', ( e ) => {
		e.preventDefault();
		play( a.closest( '.video-thumb' ), id, titleOf( a ) );
	} );
} );
