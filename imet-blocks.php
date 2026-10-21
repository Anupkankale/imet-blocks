<?php
/**
 * Plugin Name:       IMET Blocks
 * Description:       Reusable IMET section blocks (Hero, FAQ, Testimonials, CTA…) built from normal WordPress blocks, plus page patterns that compose them into full pages. Use with Template → IMET Landing.
 * Version:           1.6.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Text Domain:       imet-blocks
 * License:           GPL-2.0-or-later
 */

defined( 'ABSPATH' ) || exit;

define( 'IMET_BLOCKS_VERSION', '1.6.0' );
define( 'IMET_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );
define( 'IMET_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

/*
 * Layout:
 *   src/blocks/<name>/   one folder per section block (block.json, style.scss, view.js, default.html) → npm run build
 *   build/               compiled blocks + shared styles (what WordPress loads)
 *   patterns/            full pages composed from the blocks (one file per page)
 *   templates/           the "IMET Landing" page template
 *   includes/            PHP: block registration, template, media, speed, FAQ schema, migration
 */
require_once IMET_BLOCKS_DIR . 'includes/media.php';
require_once IMET_BLOCKS_DIR . 'includes/register.php';
require_once IMET_BLOCKS_DIR . 'includes/templates.php';
require_once IMET_BLOCKS_DIR . 'includes/perf.php';
require_once IMET_BLOCKS_DIR . 'includes/faq-schema.php';
require_once IMET_BLOCKS_DIR . 'includes/video.php';
require_once IMET_BLOCKS_DIR . 'includes/migrate.php';

register_activation_hook( __FILE__, function () {
	$upload = wp_get_upload_dir();
	wp_mkdir_p( trailingslashit( $upload['basedir'] ) . 'imet-blocks-cache' ); // combined-CSS cache (optional)
	imet_blocks_import_media();
} );
