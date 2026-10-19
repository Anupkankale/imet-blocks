<?php
/**
 * FAQPage structured data (JSON-LD) from every IMET FAQ block on the page: the Details blocks inside its group
 * with class "faq-acc", so it always matches the visible questions.
 */

defined( 'ABSPATH' ) || exit;

function imet_blocks_faq_items( $blocks, $in_faq = false ) {
	$items = array();
	foreach ( $blocks as $block ) {
		$here = $in_faq || ( isset( $block['attrs']['className'] ) && false !== strpos( $block['attrs']['className'], 'faq-acc' ) );
		if ( $here && 'core/details' === $block['blockName'] ) {
			$q = preg_match( '#<summary[^>]*>(.*?)</summary>#s', $block['innerHTML'], $m ) ? trim( wp_strip_all_tags( $m[1] ) ) : '';
			$a = trim( wp_strip_all_tags( implode( ' ', array_map( 'render_block', $block['innerBlocks'] ) ) ) );
			if ( $q && $a ) {
				$items[] = array( '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $a ) );
			}
			continue;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$items = array_merge( $items, imet_blocks_faq_items( $block['innerBlocks'], $here ) );
		}
	}
	return $items;
}

/** Collects the questions while FAQ blocks render; with no argument, returns them. */
function imet_blocks_faq_collect( $items = null ) {
	static $all = array();
	if ( null !== $items ) {
		$all = array_merge( $all, $items );
	}
	return $all;
}

add_filter( 'render_block_imet/faq', function ( $html, $block ) {
	if ( is_singular() && ! is_admin() ) {
		imet_blocks_faq_collect( imet_blocks_faq_items( $block['innerBlocks'] ) );
	}
	return $html;
}, 10, 2 );

/** Printed in <head> when the content rendered before it (IMET Landing template), otherwise in the footer. */
function imet_blocks_faq_print() {
	static $done = false;
	$items = imet_blocks_faq_collect();
	if ( $done || ! $items ) {
		return;
	}
	$done = true;
	echo '<script type="application/ld+json" id="imet-faq-schema">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- JSON-LD via wp_json_encode().
}
add_action( 'wp_head', 'imet_blocks_faq_print', 100 );
add_action( 'wp_footer', 'imet_blocks_faq_print', 100 );
