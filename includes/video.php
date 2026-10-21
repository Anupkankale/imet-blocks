<?php
/**
 * YouTube thumbnails: an Image block with the class "yt-auto" that links to a YouTube video shows that video's
 * thumbnail from YouTube, so editors only paste the link (IMET Video gallery). Without a YouTube link, or without
 * "yt-auto", the image chosen in the editor is shown.
 */

defined( 'ABSPATH' ) || exit;

/** The 11-character video ID from any YouTube URL (watch, youtu.be, embed, shorts, live), or ''. */
function imet_blocks_youtube_id( $url ) {
	return preg_match( '#(?:youtu\.be/|[?&]v=|/embed/|/shorts/|/live/)([\w-]{11})#', (string) $url, $m ) ? $m[1] : '';
}

add_filter( 'render_block_core/image', function ( $html, $block ) {
	$class = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';
	if ( ! preg_match( '/(^|\s)yt-auto(\s|$)/', $class ) || ! preg_match( '#<a\b[^>]*\bhref="([^"]+)"#', $html, $link ) ) {
		return $html;
	}
	$id = imet_blocks_youtube_id( html_entity_decode( $link[1] ) );
	if ( ! $id ) {
		return $html;
	}
	// Main player: the largest thumbnail (not every video has one) with the standard one as fallback.
	$base  = 'https://i.ytimg.com/vi/' . $id . '/';
	$large = preg_match( '/(^|\s)video-main(\s|$)/', $class );
	$src   = $base . ( $large ? 'maxresdefault.jpg' : 'hqdefault.jpg' );
	return preg_replace_callback(
		'#<img\b[^>]*>#',
		function ( $img ) use ( $src, $base, $large ) {
			$tag = preg_replace( '#\s(?:srcset|sizes|width|height)="[^"]*"#', '', $img[0] );
			$tag = preg_replace( '#\ssrc="[^"]*"#', ' src="' . esc_url( $src ) . '"', $tag );
			if ( $large ) {
				$tag = str_replace( '<img', '<img onerror="this.onerror=null;this.src=\'' . esc_url( $base . 'hqdefault.jpg' ) . '\'"', $tag );
			}
			return $tag;
		},
		$html,
		1
	);
}, 10, 2 );
