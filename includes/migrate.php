<?php
/**
 * Migration: pages built before the IMET blocks existed (IMET Generic Landing: every section a core Group with the
 * section's class, e.g. "section faq") → each such Group becomes the matching IMET block around the same inner
 * blocks. Nothing inside the sections changes. In the editor the same is available as Group → "Transform to".
 *
 *   wp imet migrate 62 --dry-run   # show what would change
 *   wp imet migrate 62             # convert, and switch the page to Template → IMET Landing
 */

defined( 'ABSPATH' ) || exit;

/**
 * @return array{0: string, 1: string[]} New content and one line per top-level block (what happened to it).
 */
function imet_blocks_wrap_sections( $content ) {
	$sections = imet_blocks_sections();
	$out      = array();
	$report   = array();
	foreach ( parse_blocks( $content ) as $block ) {
		if ( 'core/group' !== $block['blockName'] ) {
			$out[] = serialize_block( $block );
			if ( $block['blockName'] ) {
				$report[] = "kept      {$block['blockName']}";
			}
			continue;
		}
		$classes = preg_split( '/\s+/', isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '', -1, PREG_SPLIT_NO_EMPTY );
		$match   = null;
		foreach ( $sections as $name => $section ) {
			$own = explode( ' ', $section['className'] );
			if ( in_array( end( $own ), $classes, true ) ) {
				$match = $name;
				break;
			}
		}
		if ( ! $match ) {
			$out[]    = serialize_block( $block );
			$report[] = 'kept      core/group .' . implode( '.', $classes );
			continue;
		}
		$section = $sections[ $match ];
		$extra   = array_values( array_diff( $classes, explode( ' ', $section['className'] ) ) );
		$attrs   = $extra ? array( 'className' => implode( ' ', $extra ) ) : array();
		$anchor  = isset( $block['attrs']['anchor'] ) ? $block['attrs']['anchor'] : '';
		$class   = trim( 'wp-block-' . str_replace( '/', '-', $match ) . ' imet-section ' . $section['className'] . ' ' . implode( ' ', $extra ) );
		$tag     = $section['tagName'];
		$inner   = implode( "\n", array_map( 'serialize_block', $block['innerBlocks'] ) );
		$html    = "\n<$tag" . ( $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '' ) . ' class="' . esc_attr( $class ) . '">' . $inner . "</$tag>\n";

		$out[]    = get_comment_delimited_block_content( $match, $attrs, $html );
		$report[] = 'converted core/group .' . implode( '.', $classes ) . " → $match" . ( $anchor ? " #$anchor" : '' );
	}
	return array( implode( '', $out ), $report );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'imet migrate', function ( $args, $assoc ) {
		foreach ( $args as $id ) {
			$post = get_post( (int) $id );
			if ( ! $post ) {
				WP_CLI::warning( "No post $id." );
				continue;
			}
			list( $content, $report ) = imet_blocks_wrap_sections( $post->post_content );
			WP_CLI::log( "Post $id: " . get_the_title( $post ) );
			foreach ( $report as $line ) {
				WP_CLI::log( "  $line" );
			}
			if ( ! empty( $assoc['dry-run'] ) ) {
				continue;
			}
			wp_update_post( array( 'ID' => $post->ID, 'post_content' => wp_slash( $content ) ) ); // keeps a revision
			if ( 'page' === $post->post_type ) {
				update_post_meta( $post->ID, '_wp_page_template', IMET_BLOCKS_TEMPLATE );
			}
			WP_CLI::success( "Post $id migrated (previous version kept as a revision)." );
		}
	}, array(
		'shortdesc' => 'Convert section Groups (IMET Generic Landing) into IMET blocks.',
		'synopsis'  => array(
			array( 'type' => 'positional', 'name' => 'id', 'repeating' => true, 'description' => 'Post/page IDs.' ),
			array( 'type' => 'flag', 'name' => 'dry-run', 'optional' => true, 'description' => 'Only show what would change.' ),
		),
	) );
}
