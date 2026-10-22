<?php
/**
 * Pages: the "IMET Landing" page template (full width, no theme title banner, speed optimisations) and the page
 * patterns in patterns/ (full pages composed from the IMET blocks).
 */

defined( 'ABSPATH' ) || exit;

/** Template key stored in "_wp_page_template". */
const IMET_BLOCKS_TEMPLATE = 'imet-blocks-landing';

/* -----------------------------------------------------------------------------------------------------------
 * Page template
 * --------------------------------------------------------------------------------------------------------- */
add_filter( 'theme_page_templates', function ( $templates ) {
	$templates[ IMET_BLOCKS_TEMPLATE ] = __( 'IMET Landing', 'imet-blocks' );
	return $templates;
} );
add_filter( 'template_include', function ( $template ) {
	return imet_blocks_is_landing_page() ? IMET_BLOCKS_DIR . 'templates/page-landing.php' : $template;
}, 99 );

/*
 * Block themes list templates by their block-template name, so the editor's Template panel would call this one
 * by the theme's default ("Pages"). Registering it as a block template too gives it its name there. The front end
 * still uses templates/page-landing.php (template_include above).
 */
add_action( 'init', function () {
	if ( wp_is_block_theme() && function_exists( 'register_block_template' ) ) {
		register_block_template( 'imet-blocks//' . IMET_BLOCKS_TEMPLATE, array(
			'title'       => __( 'IMET Landing', 'imet-blocks' ),
			'description' => __( 'Full-width IMET page between the theme header and footer.', 'imet-blocks' ),
			'post_types'  => array( 'page' ),
			'content'     => '<!-- wp:template-part {"slug":"header","tagName":"header"} /--><!-- wp:post-content {"layout":{"type":"default"}} /--><!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->',
		) );
	}
} );

function imet_blocks_is_landing_page() {
	return is_page() && IMET_BLOCKS_TEMPLATE === get_page_template_slug( get_queried_object_id() );
}

/**
 * Pages built from IMET blocks always open in the block editor, even where the Classic Editor plugin (or a theme /
 * page-builder setting) turns it off site-wide. Other pages keep whatever editor the site uses.
 */
function imet_blocks_is_imet_page( $post ) {
	return $post instanceof WP_Post && 'page' === $post->post_type
		&& ( IMET_BLOCKS_TEMPLATE === get_page_template_slug( $post ) || false !== strpos( $post->post_content, '<!-- wp:imet/' ) );
}
add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
	return imet_blocks_is_imet_page( $post ) ? true : $use;
}, PHP_INT_MAX, 2 );

// A page built from IMET blocks with no template chosen gets the IMET Landing template when saved, so a page
// started from a pattern is ready without a trip to the Template setting. A template chosen on purpose is kept.
add_action( 'save_post_page', function ( $post_id, $post ) {
	if ( wp_is_post_revision( $post_id ) || false === strpos( $post->post_content, '<!-- wp:imet/' ) ) {
		return;
	}
	$template = get_page_template_slug( $post_id );
	if ( '' === $template || 'default' === $template ) {
		update_post_meta( $post_id, '_wp_page_template', IMET_BLOCKS_TEMPLATE );
	}
}, 10, 2 );

/* -----------------------------------------------------------------------------------------------------------
 * Patterns: patterns/<slug>.php (header: Title, Description, Kind; body: blocks with {{ID:…}}/{{URL:…}} tokens).
 *   Kind: page     a full page: offered in "Choose a pattern" for new pages, in the inserter (Patterns → IMET)
 *                  and as "Create: <title>" on the Plugins screen. A new page type = a new file.
 *   Kind: section  a ready-made section (CTA banner, button row…): inserter only (Patterns → IMET).
 * --------------------------------------------------------------------------------------------------------- */
function imet_blocks_patterns() {
	$patterns = array();
	foreach ( glob( IMET_BLOCKS_DIR . 'patterns/*.php' ) as $file ) {
		$data = get_file_data( $file, array( 'title' => 'Title', 'description' => 'Description', 'kind' => 'Kind' ) );
		$data['kind'] = 'section' === strtolower( trim( $data['kind'] ) ) ? 'section' : 'page';
		$patterns[ basename( $file, '.php' ) ] = $data + array( 'file' => $file );
	}
	return $patterns;
}

/** Full-page patterns only. */
function imet_blocks_page_patterns() {
	return array_filter( imet_blocks_patterns(), function ( $p ) {
		return 'page' === $p['kind'];
	} );
}

function imet_blocks_pattern_content( $slug ) {
	$patterns = imet_blocks_patterns();
	if ( empty( $patterns[ $slug ] ) ) {
		return '';
	}
	ob_start();
	include $patterns[ $slug ]['file'];
	return imet_blocks_media_tokens( preg_replace( '#^<\?php.*?\?>\s*#s', '', (string) ob_get_clean() ) );
}

add_action( 'init', function () {
	register_block_pattern_category( 'imet', array( 'label' => __( 'IMET', 'imet-blocks' ) ) );
	foreach ( imet_blocks_patterns() as $slug => $pattern ) {
		register_block_pattern( "imet-blocks/$slug", array_filter( array(
			'title'         => $pattern['title'],
			'description'   => $pattern['description'],
			'content'       => imet_blocks_pattern_content( $slug ),
			'categories'    => array( 'imet' ),
			// No 'postTypes': with block themes the page editor resolves patterns for the template it shows the page
			// in, so a page-only pattern disappears. "Choose a pattern" still offers it on new pages only.
			'blockTypes'    => 'page' === $pattern['kind'] ? array( 'core/post-content' ) : null,
			'viewportWidth' => 1440,
		) ) );
	}
}, 20 );

// Plugins screen → "Create: <pattern>": a draft page with the template and the pattern's content.
add_action( 'admin_post_imet_blocks_create_page', function () {
	check_admin_referer( 'imet_blocks_create_page' );
	if ( ! current_user_can( 'publish_pages' ) ) {
		wp_die( esc_html__( 'You are not allowed to create pages.', 'imet-blocks' ) );
	}
	$slug     = isset( $_GET['pattern'] ) ? sanitize_key( wp_unslash( $_GET['pattern'] ) ) : '';
	$patterns = imet_blocks_page_patterns();
	if ( empty( $patterns[ $slug ] ) ) {
		wp_die( esc_html__( 'Unknown page pattern.', 'imet-blocks' ) );
	}
	if ( ! get_option( 'imet_blocks_media' ) ) {
		imet_blocks_import_media();
	}
	$id = wp_insert_post( array(
		'post_type'    => 'page',
		'post_status'  => 'draft',
		'post_title'   => $patterns[ $slug ]['title'],
		'post_content' => wp_slash( imet_blocks_pattern_content( $slug ) ),
		'meta_input'   => array( '_wp_page_template' => IMET_BLOCKS_TEMPLATE ),
	), true );
	wp_safe_redirect( is_wp_error( $id ) ? admin_url( 'plugins.php' ) : admin_url( 'post.php?post=' . $id . '&action=edit' ) );
	exit;
} );
add_filter( 'plugin_action_links_' . plugin_basename( IMET_BLOCKS_DIR . 'imet-blocks.php' ), function ( $links ) {
	foreach ( imet_blocks_page_patterns() as $slug => $pattern ) {
		$url = wp_nonce_url( admin_url( 'admin-post.php?action=imet_blocks_create_page&pattern=' . $slug ), 'imet_blocks_create_page' );
		/* translators: %s: page pattern title */
		array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html( sprintf( __( 'Create: %s', 'imet-blocks' ), $pattern['title'] ) ) . '</a>' );
	}
	return $links;
} );

/* -----------------------------------------------------------------------------------------------------------
 * Enquiry form: the hero's form card holds a normal Shortcode block. Paste any form plugin's shortcode there
 * (Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator…). The default content holds
 * [imet_advisor_form], a placeholder only editors can see.
 * --------------------------------------------------------------------------------------------------------- */
add_action( 'init', function () {
	if ( ! shortcode_exists( 'imet_advisor_form' ) ) {
		add_shortcode( 'imet_advisor_form', function () {
			return current_user_can( 'edit_pages' )
				? '<p class="imet-form-missing">' . esc_html__( 'Form placeholder (only editors see this): edit the page and replace [imet_advisor_form] in this Shortcode block with your form shortcode, e.g. [contact-form-7 id="123"] or [wpforms id="123"].', 'imet-blocks' ) . '</p>'
				: '';
		} );
	}
} );

/**
 * Whether the page has shortcodes from other plugins (normally the form). Their scripts may expect jQuery,
 * select2 or flatpickr early, so those speed tweaks are skipped on such pages.
 */
function imet_blocks_has_plugin_shortcodes() {
	$post = get_queried_object();
	if ( ! $post instanceof WP_Post || ! preg_match_all( '#\[([A-Za-z0-9_-]+)#', $post->post_content, $m ) ) {
		return false;
	}
	foreach ( array_unique( $m[1] ) as $tag ) {
		if ( 'imet_advisor_form' !== $tag && shortcode_exists( $tag ) ) {
			return true;
		}
	}
	return false;
}

/* -----------------------------------------------------------------------------------------------------------
 * Editor: full-width canvas for pages using the template (the blocks bring their own styles everywhere).
 * --------------------------------------------------------------------------------------------------------- */
add_filter( 'block_editor_settings_all', function ( $settings, $context ) {
	if ( empty( $context->post ) || IMET_BLOCKS_TEMPLATE !== get_page_template_slug( $context->post ) ) {
		return $settings;
	}
	$css = (string) @file_get_contents( IMET_BLOCKS_DIR . 'build/shared/editor-canvas.css' ); // phpcs:ignore
	$settings['styles'][] = array( 'css' => $css, '__unstableType' => 'theme', 'source' => 'imet-blocks' );
	return $settings;
}, 10, 2 );

/* -----------------------------------------------------------------------------------------------------------
 * Front end, pages using the template: page layout CSS and the speed optimisations (includes/perf.php).
 * --------------------------------------------------------------------------------------------------------- */
add_action( 'template_redirect', function () {
	if ( imet_blocks_is_landing_page() && ! is_feed() ) {
		imet_blocks_landing_assets();
	}
}, 5 );

function imet_blocks_landing_assets() {
	add_filter( 'body_class', function ( $classes ) {
		$classes[] = 'imet-landing-template';
		return $classes;
	} );
	add_action( 'wp_enqueue_scripts', function () {
		$file = IMET_BLOCKS_DIR . 'build/shared/page-layout.css';
		wp_enqueue_style( 'imet-page-layout', IMET_BLOCKS_URL . 'build/shared/page-layout.css', array(), (string) @filemtime( $file ) ); // phpcs:ignore
		wp_style_add_data( 'imet-page-layout', 'path', $file );

		foreach ( imet_blocks_perf_unused_styles() as $handle ) {
			wp_dequeue_style( $handle );
		}
		foreach ( imet_blocks_perf_unused_scripts() as $handle ) {
			wp_dequeue_script( $handle );
		}
		if ( ! imet_blocks_has_plugin_shortcodes() ) {
			foreach ( array( 'jquery', 'jquery-core', 'jquery-migrate' ) as $handle ) {
				wp_scripts()->add_data( $handle, 'group', 1 ); // print in the footer
			}
		}
		wp_add_inline_script( 'jquery-core', 'jQuery.fn.select2=jQuery.fn.select2||function(){return this;};window.flatpickr=window.flatpickr||function(){return{};};' );

		// The IMET styles print last, after the theme's and WordPress' global styles: the design's resets win ties
		// (as when it was one stylesheet printed at the end of <head>).
		$queue = wp_styles()->queue;
		foreach ( $queue as $handle ) {
			if ( 0 === strpos( $handle, 'imet-' ) ) {
				wp_dequeue_style( $handle );
				wp_enqueue_style( $handle );
			}
		}
	}, 999 );
	add_action( 'wp_print_styles', function () {
		foreach ( imet_blocks_perf_unused_styles() as $handle ) {
			wp_dequeue_style( $handle );
		}
	}, 999 );
	// Classic themes: WordPress prints the global styles again at wp_footer (moved into <head> only when its own output
	// buffer runs, which caching plugins can prevent). Printed after the IMET styles, their block-gap rules
	// (`:root :where(.is-layout-flow) > * { margin-block-start: 24px }`) win the ties and add space everywhere.
	if ( ! wp_is_block_theme() ) {
		remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	add_action( 'wp_head', function () {
		foreach ( array( 'montserrat-latin-var.woff2', 'inter-latin-var.woff2', 'quantico-700-latin.woff2' ) as $font ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( IMET_BLOCKS_URL . 'assets/fonts/' . $font ) );
		}
	}, 1 );

	ob_start( 'imet_blocks_perf_filter_html' );
}
