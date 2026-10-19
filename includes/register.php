<?php
/**
 * Blocks: every folder in build/blocks/ is one section block (see src/blocks/<name>/block.json).
 */

defined( 'ABSPATH' ) || exit;

/** Inserter category "IMET" (first in the list). */
add_filter( 'block_categories_all', function ( $categories ) {
	array_unshift( $categories, array( 'slug' => 'imet', 'title' => __( 'IMET', 'imet-blocks' ), 'icon' => null ) );
	return $categories;
} );

add_action( 'init', function () {
	// Shared section styles (fonts, tokens, buttons…): a dependency of every block's style, so it loads once and
	// only on pages that use an IMET block.
	$file = IMET_BLOCKS_DIR . 'build/shared/base.css';
	wp_register_style( 'imet-base', IMET_BLOCKS_URL . 'build/shared/base.css', array(), file_exists( $file ) ? (string) filemtime( $file ) : IMET_BLOCKS_VERSION );
	wp_style_add_data( 'imet-base', 'path', $file ); // lets WordPress inline it when small

	$dir      = IMET_BLOCKS_DIR . 'build/blocks';
	$manifest = IMET_BLOCKS_DIR . 'build/blocks-manifest.php';
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) { // WP 6.8+
		wp_register_block_types_from_metadata_collection( $dir, $manifest );
		return;
	}
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) { // WP 6.7
		wp_register_block_metadata_collection( $dir, $manifest );
	}
	foreach ( array_keys( imet_blocks_manifest() ) as $slug ) {
		register_block_type( "$dir/$slug" );
	}
} );

/** block.json of every built block: folder name => metadata. */
function imet_blocks_manifest() {
	static $manifest = null;
	if ( null === $manifest ) {
		$file     = IMET_BLOCKS_DIR . 'build/blocks-manifest.php';
		$manifest = file_exists( $file ) ? (array) require $file : array();
	}
	return $manifest;
}

/**
 * The sections: block name => { tagName, className, anchor } (the "imet" key in block.json), i.e. which element and
 * classes the block renders. Used by the migration and the FAQ schema.
 */
function imet_blocks_sections() {
	$sections = array();
	foreach ( imet_blocks_manifest() as $meta ) {
		if ( isset( $meta['name'], $meta['imet'] ) ) {
			$sections[ $meta['name'] ] = $meta['imet'] + array( 'tagName' => 'section', 'className' => '', 'anchor' => '' );
		}
	}
	return $sections;
}

/** A block's default inner content (build/blocks/<name>/default.html) with this site's images. */
function imet_blocks_default_content( $block_name ) {
	$file = IMET_BLOCKS_DIR . 'build/blocks/' . substr( $block_name, strlen( 'imet/' ) ) . '/default.html';
	return is_readable( $file ) ? imet_blocks_media_tokens( file_get_contents( $file ) ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions
}

/** Editor: default content for newly inserted blocks (read by src/shared/section-block.js). */
add_action( 'enqueue_block_editor_assets', function () {
	$defaults = array();
	foreach ( array_keys( imet_blocks_sections() ) as $name ) {
		$defaults[ $name ] = imet_blocks_default_content( $name );
	}
	wp_add_inline_script( 'wp-blocks', 'window.imetBlocks = ' . wp_json_encode( array( 'defaults' => $defaults ) ) . ';', 'before' );
} );

/*
 * Classic themes (e.g. Glidex) load every registered block's style on every page. Keep only the styles of the IMET
 * blocks this page actually uses (block themes already load block styles on demand).
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( wp_should_load_separate_core_block_assets() ) {
		return;
	}
	$content = is_singular() ? (string) get_post_field( 'post_content', get_queried_object_id() ) : '';
	$used    = false;
	foreach ( WP_Block_Type_Registry::get_instance()->get_all_registered() as $name => $type ) {
		if ( 0 !== strpos( $name, 'imet/' ) ) {
			continue;
		}
		if ( has_block( $name, $content ) ) {
			$used = true;
			continue;
		}
		foreach ( array_diff( (array) $type->style_handles, array( 'imet-base' ) ) as $handle ) {
			wp_dequeue_style( $handle );
		}
	}
	if ( ! $used ) {
		wp_dequeue_style( 'imet-base' );
	}
}, 20 );
