<?php
/**
 * IMET Landing page template: the page's blocks full width between the theme's header and footer.
 *
 * Works with classic themes (header.php/footer.php) and block themes (the theme's "header"/"footer" template parts).
 * The content is rendered before wp_head(), so the styles of the blocks it uses are printed in <head>.
 */

defined( 'ABSPATH' ) || exit;

if ( have_posts() ) {
	the_post();
}
$imet_content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core filter.

$imet_block_theme = wp_is_block_theme();
if ( $imet_block_theme ) {
	// Render the template parts first too, so their styles are enqueued before wp_head().
	$imet_header = do_blocks( '<!-- wp:template-part {"slug":"header","tagName":"header"} /-->' );
	$imet_footer = do_blocks( '<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->' );
	?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wp-site-blocks">
	<?php
	echo $imet_header; // phpcs:ignore WordPress.Security.EscapeOutput -- block output.
} else {
	get_header();
}
?>
<main id="content" class="imet-page">
	<?php echo $imet_content; // phpcs:ignore WordPress.Security.EscapeOutput -- block content. ?>
</main>
<?php
if ( $imet_block_theme ) {
	echo $imet_footer; // phpcs:ignore WordPress.Security.EscapeOutput -- block output.
	?>
</div>
	<?php wp_footer(); ?>
</body>
</html>
	<?php
} else {
	get_footer();
}
