<?php
/**
 * Images: photos and logos used by the default content are imported into the Media Library, so editors can replace
 * them with the media picker and WordPress serves responsive sizes. Decorative icons stay in the blocks' CSS.
 *
 * Default content (default.html, patterns) refers to them with tokens: {{ID:file.webp}} and {{URL:file.webp}}.
 */

defined( 'ABSPATH' ) || exit;

/** Photos and logos used by the default content: file name => alt text. */
function imet_blocks_media_files() {
	return array(
		'logo-singapore.webp' => 'Singapore', 'logo-ministry-education.webp' => 'Ministry of Education', 'logo-wes.webp' => 'World Education Services',
		'logo-mca.webp' => 'Ministry of Corporate Affairs', 'logo-mofa.webp' => 'Ministry of Foreign Affairs',
		'highlights.webp' => 'DBA graduates: promoted to CxO, 15+ top global specializations, skill growth',
		'emb-uae.webp' => 'Embassy of UAE', 'emb-bahrain.webp' => 'Embassy of Bahrain', 'emb-usa.webp' => 'Embassy of United States',
		'emb-qatar.webp' => 'Embassy of Qatar', 'emb-nz.webp' => 'Embassy of New Zealand', 'emb-singapore.webp' => 'Embassy of Singapore',
		'graduate.webp' => 'DBA graduate: 3.5M+ cybersecurity job openings worldwide, +85% immediate ROI, career path from IT professional to CISO',
		'bento-flex.webp' => '', 'bento-tags.webp' => 'AI/ML, Research, Data Analytics, Cybersecurity, Leadership, Digital transformation, IT Governance, Gen-AI, Project Management, Visionaries',
		'bento-map.webp' => '', 'bento-avatars.webp' => '50+ alumni', 'bento-mentor.webp' => '', 'bento-roi.webp' => '',
		'cyber-1.webp' => '', 'cyber-2.webp' => '', 'cyber-3.webp' => '', 'cyber-4.webp' => '',
		'join-photo.webp' => 'Professionals collaborating in a meeting', 'insights.webp' => 'Insights analytics: learner countries, salaries and career impact, industries',
		'video-thumb.webp' => 'Repositioning the Leader in Me: My Doctorate Journey to Global Visibility – podcast with Dr. Raj Srirambatla',
		'avatar-1.webp' => '', 'avatar-2.webp' => '', 'avatar-3.webp' => '',
		'certificate.webp' => 'Framed IMET Doctor of Business Administration certificate',
		'alumni-video.png' => 'Video: IMET DBA graduate with a program advisor',
		'bento-flex-man.png' => '', 'bento-mentor-woman.png' => '',
		'join-graduates.jpg' => 'DBA graduates celebrating at convocation',
		'cert-graduate.jpg' => 'IMET DBA graduate holding his certificate',
		'cta-graduates.jpg' => 'IMET DBA graduates at convocation',
		'alumni-convocation.jpg' => 'IMET convocation day: a DBA graduate with a programme advisor',
		// DBA Course Page (Cybersecurity)
		'course-highlights.webp' => 'Cybersecurity shield over a digital hand: Cyber Risk Director, 85% of global organizations report increased cyber risk',
		'course-requirements.webp' => 'AI head with a padlock: AI ethics & security, cyber resilience, C-suite reporting 50% direct to board',
		'course-benefits.webp' => 'Cybersecurity leader: 3.5M+ job openings worldwide, +85% immediate ROI, career path from IT professional to CISO',
		'course-curriculum.webp' => 'IMET DBA graduate holding his certificate of achievement',
		'course-step-1.webp' => 'Researcher drafting a thesis title on paper', 'course-step-2.webp' => 'Typewriter with a page that reads Research',
		'course-step-3.webp' => 'Doctoral candidate writing on a laptop', 'course-step-4.webp' => 'Tablet showing a global cybersecurity map',
		'course-step-5.webp' => 'Thesis draft open on a laptop', 'course-step-6.webp' => 'DBA graduate receiving his doctorate certificate',
		'alumni-fab.webp' => 'First Abu Dhabi Bank', 'alumni-katara.webp' => 'Katara', 'alumni-cure.webp' => 'Cure Medical Centers',
		'alumni-western.webp' => 'Western International Group', 'alumni-globalcare.webp' => 'Global Care Hospital',
	);
}

/**
 * file name => attachment ID for images already imported. Images the IMET Generic Landing plugin imported are
 * reused, so they aren't imported twice.
 */
function imet_blocks_media_map() {
	$map = get_option( 'imet_blocks_media', null );
	if ( ! is_array( $map ) ) {
		$map = get_option( 'imet_generic_media', array() );
	}
	return is_array( $map ) ? $map : array();
}

function imet_blocks_import_media() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$map = imet_blocks_media_map();
	foreach ( imet_blocks_media_files() as $file => $alt ) {
		if ( ! empty( $map[ $file ] ) && get_post( $map[ $file ] ) ) {
			continue;
		}
		$src = IMET_BLOCKS_DIR . 'assets/images/' . $file;
		if ( ! is_readable( $src ) ) {
			continue;
		}
		$tmp = wp_tempnam( $file );
		if ( ! $tmp || ! copy( $src, $tmp ) ) {
			continue;
		}
		$id = media_handle_sideload( array( 'name' => 'imet-' . $file, 'tmp_name' => $tmp ), 0, $alt ? $alt : null );
		if ( is_wp_error( $id ) ) {
			wp_delete_file( $tmp );
			continue;
		}
		if ( $alt ) {
			update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		}
		$map[ $file ] = $id;
	}
	update_option( 'imet_blocks_media', $map, false );
	return $map;
}

/** After a plugin update (activation doesn't run again): import images added in the new version. */
add_action( 'admin_init', function () {
	if ( get_option( 'imet_blocks_version' ) === IMET_BLOCKS_VERSION || ! current_user_can( 'upload_files' ) ) {
		return;
	}
	imet_blocks_import_media();
	update_option( 'imet_blocks_version', IMET_BLOCKS_VERSION, false );
} );

/** Replace {{ID:file}} / {{URL:file}} with the Media Library attachment (or the plugin's copy if not imported). */
function imet_blocks_media_tokens( $html ) {
	$map = imet_blocks_media_map();
	return preg_replace_callback(
		'#\{\{(ID|URL):([^}]+)\}\}#',
		function ( $m ) use ( $map ) {
			$id = isset( $map[ $m[2] ] ) ? (int) $map[ $m[2] ] : 0;
			if ( 'ID' === $m[1] ) {
				return (string) $id;
			}
			$url = $id ? wp_get_attachment_url( $id ) : '';
			return $url ? $url : IMET_BLOCKS_URL . 'assets/images/' . $m[2];
		},
		(string) $html
	);
}
