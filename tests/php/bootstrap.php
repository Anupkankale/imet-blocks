<?php
/**
 * PHPUnit bootstrap: loads the plugin's pure-PHP helpers with tiny stand-ins for the few WordPress functions they call.
 * No WordPress, database or web server is needed. (Hooks are recorded, not run.)
 */

define( 'ABSPATH', __DIR__ . '/' );
define( 'IMET_BLOCKS_DIR', dirname( __DIR__, 2 ) . '/' );
define( 'IMET_BLOCKS_URL', 'https://example.test/wp-content/plugins/imet-blocks/' );
define( 'IMET_BLOCKS_VERSION', '0.0.0-test' );

/** State the stand-ins read, reset by each test's setUp(). */
$GLOBALS['imet_test'] = array( 'options' => array(), 'attachments' => array(), 'hooks' => array() );

function add_filter( $hook, $fn, $priority = 10, $args = 1 ) {
	$GLOBALS['imet_test']['hooks'][ $hook ][] = $fn;
	return true;
}
function add_action( $hook, $fn, $priority = 10, $args = 1 ) {
	return add_filter( $hook, $fn, $priority, $args );
}
function get_option( $name, $default = false ) {
	return array_key_exists( $name, $GLOBALS['imet_test']['options'] ) ? $GLOBALS['imet_test']['options'][ $name ] : $default;
}
function wp_get_attachment_url( $id ) {
	return isset( $GLOBALS['imet_test']['attachments'][ $id ] ) ? $GLOBALS['imet_test']['attachments'][ $id ] : false;
}
function home_url() {
	return 'https://example.test';
}
function wp_parse_url( $url, $component = -1 ) {
	return parse_url( $url, $component );
}
function wp_strip_all_tags( $text ) {
	return trim( preg_replace( '/[\r\n\t ]+/', ' ', strip_tags( $text ) ) );
}
/** The plugin renders a block's inner blocks; in tests a block is just its HTML. */
function render_block( $block ) {
	return isset( $block['innerHTML'] ) ? $block['innerHTML'] : '';
}
function wp_json_encode( $data, $flags = 0 ) {
	return json_encode( $data, $flags );
}
function is_singular() {
	return true;
}
function is_admin() {
	return false;
}

require_once IMET_BLOCKS_DIR . 'includes/media.php';
require_once IMET_BLOCKS_DIR . 'includes/perf.php';
require_once IMET_BLOCKS_DIR . 'includes/faq-schema.php';
require_once IMET_BLOCKS_DIR . 'includes/video.php';
