<?php
/**
 * Core Web Vitals optimisations for pages using the IMET Landing template (see imet_blocks_landing_assets() in includes/templates.php).
 *  - CLS: the Elementor header prints its stylesheets at the end of <body>, so the header renders half-styled and
 *    then jumps (CLS ~0.38 on mobile). Stylesheets printed in the body are moved into <head>.
 *  - Fonts: the ~11 Google Font stylesheets the theme/plugins load site-wide are dropped; the families this page
 *    renders (Quantico + Cambay in the header/footer, Montserrat + Inter here) are self-hosted (see page.css).
 *  - Requests: unused feature assets are skipped; every local stylesheet and inline <style> in <head> becomes
 *    one cached file, in the same order.
 *  - INP/TBT: analytics (GTM, gtag.js, Clarity) start on the first interaction, or 5s after load.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'imet_blocks_perf_unused_styles' ) ) {
	/** Styles that only serve features not present on this page. */
	function imet_blocks_perf_unused_styles() {
		// Block themes build their header/footer from blocks: they need the block library and global styles.
		$wp_core = wp_is_block_theme()
			? array( 'wp-components', 'wp-preferences', 'wp-block-editor' )
			: array( 'wp-block-library', 'wp-theme', 'wp-components', 'wp-preferences', 'wp-block-editor', 'global-styles', 'wp-global-styles-placeholder', 'classic-theme-styles' );
		// Form plugins can use select2/flatpickr: only skipped when the page has no other plugin's shortcode.
		$form_libs = imet_blocks_has_plugin_shortcodes() ? array() : array( 'jquery-select2', 'flatpickr' );
		return array_merge( $wp_core, $form_libs, array(
			'popup-maker-block-library-style',
			// Theme/plugin modules for blog archives, single posts, sidebars, comments, pagination, breadcrumbs (hidden here)…
			'wdt-blog', 'wdt-blog-archive-minimal', 'glidex-plus-blog', 'glidex-pro-blog', 'glidex-pro-post-navigation',
			'glidex-comments', 'glidex-pagination', 'glidex-secondary', 'site-sidebar', 'site-breadcrumb', 'glidex-breadcrumb',
			'jquery-bxslider', 'glidex-magnific-popup', 'glidex-pro-advance-field', 'glidex-pro-auth', 'style-bmi', 'site-loader',
			'wpdreams-asl-basic', 'wpdreams-asl-instance',
			// Theme demo-widget styles (sliders, pricing tables, marquees…): 145KB, verified unused here by a full-page pixel diff
			// at 1440px and 390px. (glidex-layout looks unused too, but it styles the header button, so it stays.)
			'glidex-additional-css',
		) );
	}
}

if ( ! function_exists( 'imet_blocks_perf_unused_scripts' ) ) {
	/** Scripts for the same unused features. custom.js guards most of them; select2/flatpickr get no-op stubs below. */
	function imet_blocks_perf_unused_scripts() {
		$form_libs = imet_blocks_has_plugin_shortcodes() ? array() : array( 'flatpickr', 'jquery-select2' );
		return array_merge( $form_libs, array(
			'bmi-js', 'post-infinite', 'post-loadmore', 'glidex-pro-auth', 'isotope-pkgd',
			'matchheight', 'jquery-bxslider', 'jquery-fitvids', 'jquery-magnific-popup', 'wd-asl-ajaxsearchlite',
			'wdt-parallax-scroll', 'wdt-parallax',
		) );
	}
}

if ( ! function_exists( 'imet_blocks_preg_safe' ) ) {
	/** preg_* return null on failure (e.g. PCRE limits on huge markup): keep the previous HTML instead of losing it. */
	function imet_blocks_preg_safe( $previous, $result ) {
		return null === $result ? $previous : $result;
	}
}

if ( ! function_exists( 'imet_blocks_perf_filter_html' ) ) {
	function imet_blocks_perf_filter_html( $html ) {
		$head_end = stripos( $html, '</head>' );
		if ( false === $head_end ) {
			return $html;
		}
		$head = substr( $html, 0, $head_end );
		$body = substr( $html, $head_end );

		// 1. Stylesheets printed in the body (Elementor header CSS) -> end of <head>, so the header renders styled.
		$late = array();
		$body = imet_blocks_preg_safe( $body, preg_replace_callback(
			'#<link\b[^>]*rel=[\'"]stylesheet[\'"][^>]*>\s*#i',
			function ( $m ) use ( &$late ) {
				$late[] = trim( $m[0] );
				return '';
			},
			$body
		) );

		// 2. Google Fonts (stylesheets, preloads, preconnect/dns-prefetch, @imports): the fonts this page uses are self-hosted.
		$head = imet_blocks_preg_safe( $head, preg_replace( '#<link\b[^>]*(fonts\.googleapis\.com|fonts\.gstatic\.com)[^>]*>\s*#i', '', $head ) );
		$head = imet_blocks_preg_safe( $head, preg_replace( '#@import\s+url\(\s*[\'"]?(?:https?:)?//fonts\.googleapis\.com[^)]*\)\s*;?#i', '', $head ) );

		// 3. One stylesheet instead of ~40: every local stylesheet and inline <style> in <head>, in their original order.
		$head = imet_blocks_perf_combine_css( $head . ( $late ? implode( "\n", $late ) . "\n" : '' ) );
		$late = array();

		// 4. Analytics: keep the cheap inline config (dataLayer/gtag queue), delay the libraries.
		$delay = function ( $chunk ) {
			$chunk = imet_blocks_preg_safe( $chunk, preg_replace( '#<script\b([^>]*)\bsrc=(["\'])(https://www\.googletagmanager\.com/gtag/js[^"\']*)\2([^>]*)>#i', '<script type="imet/delayed" data-src="$3">', $chunk ) );
			return imet_blocks_preg_safe( $chunk, preg_replace_callback(
				// Plain inline scripts only (no type, or type="text/javascript"): JSON-LD, modules etc. are left alone.
				'#<script\b(?![^>]*\btype=(?![\'"]text/javascript[\'"]))([^>]*)>((?:(?!</script>).)*?(?:googletagmanager\.com/gtm\.js|clarity\.ms/tag)(?:(?!</script>).)*)</script>#is',
				function ( $m ) {
					return '<script type="imet/delayed"' . preg_replace( '#\s*type=[\'"]text/javascript[\'"]#i', '', $m[1] ) . '>' . $m[2] . '</script>';
				},
				$chunk
			) );
		};
		$head = $delay( $head );
		$body = $delay( $body );

		$loader = '<script>(function(){var d=0;function r(){if(d)return;d=1;document.querySelectorAll(\'script[type="imet/delayed"]\').forEach(function(o){var s=document.createElement("script");if(o.dataset.src){s.src=o.dataset.src;s.async=true;}else{s.text=o.textContent;}o.parentNode.replaceChild(s,o);});}'
			. '["pointerdown","keydown","scroll","touchstart"].forEach(function(e){addEventListener(e,r,{once:true,passive:true});});'
			. 'addEventListener("load",function(){setTimeout(r,5000);});})();</script>';

		$body = imet_blocks_preg_safe( $body, preg_replace( '#</body>#i', $loader . "\n</body>", $body, 1 ) );

		return $head . ( $late ? implode( "\n", $late ) . "\n" : '' ) . $body;
	}
}

if ( ! function_exists( 'imet_blocks_perf_combine_css' ) ) {
	/**
	 * Replace the local stylesheet links and inline <style> blocks in $head with one cached file.
	 *
	 * Order is preserved (links and inline blocks are concatenated as they appear), media attributes become
	 * @media wrappers, and relative url()s are made absolute. The file name is a hash of the inputs, so it is
	 * rebuilt automatically when any stylesheet or inline CSS changes and can be cached forever by browsers.
	 * Falls back to the original markup if anything can't be read or written.
	 */
	function imet_blocks_perf_combine_css( $head ) {
		$site    = wp_parse_url( home_url(), PHP_URL_HOST );
		$pattern = '#<link\b[^>]*rel=[\'"]stylesheet[\'"][^>]*>|<style\b([^>]*)>(.*?)</style>#is';
		if ( ! preg_match_all( $pattern, $head, $tags, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ) {
			return $head;
		}

		$parts = array();
		foreach ( $tags as $tag ) {
			$html = $tag[0][0];
			if ( 0 === stripos( $html, '<style' ) ) {
				$parts[] = array( 'inline', $tag[2][0], $html );
				continue;
			}
			if ( ! preg_match( '#href=[\'"]([^\'"]+)[\'"]#i', $html, $h ) ) {
				return $head;
			}
			$url   = html_entity_decode( $h[1] );
			$media = preg_match( '#media=[\'"]([^\'"]+)[\'"]#i', $html, $m ) ? $m[1] : 'all';
			$host  = wp_parse_url( ( 0 === strpos( $url, '//' ) ? 'http:' : '' ) . $url, PHP_URL_HOST );
			if ( $host && $host !== $site ) {
				return $head; // External stylesheet in the chain: combining would change the cascade order.
			}
			$path = wp_parse_url( $url, PHP_URL_PATH );
			$file = untrailingslashit( ABSPATH ) . substr( $path, strlen( (string) wp_parse_url( site_url(), PHP_URL_PATH ) ) );
			if ( ! is_readable( $file ) ) {
				return $head;
			}
			$parts[] = array( 'file', $file, $html, $url, $media );
		}

		$key = '';
		foreach ( $parts as $p ) {
			$key .= 'inline' === $p[0] ? md5( $p[1] ) : $p[1] . filemtime( $p[1] ) . $p[4];
		}
		$upload = wp_get_upload_dir();
		$dir    = trailingslashit( $upload['basedir'] ) . 'imet-blocks-cache';
		$name   = 'page-' . substr( md5( $key ), 0, 16 ) . '.css';

		if ( ! file_exists( "$dir/$name" ) ) {
			$css = '';
			foreach ( $parts as $p ) {
				if ( 'inline' === $p[0] ) {
					$chunk = $p[1];
				} else {
					$chunk = (string) file_get_contents( $p[1] ); // phpcs:ignore WordPress.WP.AlternativeFunctions
					$base  = trailingslashit( dirname( strtok( $p[3], '?' ) ) );
					$chunk = preg_replace_callback(
						'#url\(\s*([\'"]?)(?!data:|https?:|//|/|\#)([^\'")]+)\1\s*\)#i',
						function ( $m ) use ( $base ) {
							return 'url("' . $base . $m[2] . '")';
						},
						$chunk
					);
					if ( 'all' !== $p[4] && '' !== $p[4] ) {
						$chunk = "@media {$p[4]} {\n$chunk\n}";
					}
				}
				$chunk = preg_replace( '#@import\s+url\(\s*[\'"]?(?:https?:)?//fonts\.googleapis\.com[^)]*\)\s*;?#i', '', $chunk );
				$css  .= "\n" . $chunk;
			}
			if ( ! wp_mkdir_p( $dir ) || false === file_put_contents( "$dir/$name.tmp", $css ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
				return $head;
			}
			rename( "$dir/$name.tmp", "$dir/$name" );
		}

		// Swap: first tag becomes the combined stylesheet, the rest are removed.
		$href = esc_url( trailingslashit( $upload['baseurl'] ) . 'imet-blocks-cache/' . $name );
		$link = '<link rel="stylesheet" id="imet-blocks-combined-css" href="' . $href . '">';

		// Rebuild <head> by slicing at the recorded offsets: the first tag becomes the combined link, the rest go.
		// (Not regex replacement: a pattern built from a large inline <style> can exceed PCRE limits and fail.)
		$out = '';
		$pos = 0;
		foreach ( $tags as $i => $tag ) {
			$out .= substr( $head, $pos, $tag[0][1] - $pos ) . ( 0 === $i ? $link . "\n" : '' );
			$pos   = $tag[0][1] + strlen( $tag[0][0] );
		}
		return $out . substr( $head, $pos );
	}
}
