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
		'home-grad-1.webp' => 'Dr. Suresh A Shan, Chairman CSI', 'home-grad-2.webp' => 'Dr. Adebola Paveun, Medical Aesthetician',
		'home-grad-3.webp' => 'Dr. Caroline Koech, Executive Director', 'home-grad-4.webp' => 'Dr. Thamer Al Qadi, CTO',
		'home-grad-5.webp' => 'Dr. Olaniyi Olaniyan, Director CyberSecurity', 'home-grad-6.webp' => 'Dr. Rufino Jr, VP CyberSecurity',
		'home-tab-doctorate.webp' => 'Online DBA graduate with research visibility on IEEE, Scopus and Google Scholar', 'home-emb-4.webp' => 'Embassy of Kuwait',
		'home-emb-5.webp' => 'Saudi Arabia Ministry of Foreign Affairs', 'home-emb-6.webp' => 'Embassy of South Africa',
		'home-emb-7.webp' => 'Embassy of United Arab Emirates', 'home-emb-1.webp' => 'Embassy of New Zealand',
		'home-emb-2.webp' => 'Embassy of Singapore', 'home-emb-3.webp' => 'Embassy of United Kingdom',
		'home-why-3.webp' => 'Online, Flexible & Affordable Program', 'home-why-4.webp' => 'Scholarship for online master degree',
		'home-why-1.webp' => 'Why choose IMET\'s online doctorate and master\'s program', 'home-why-2.webp' => 'Executive branding services',
		'home-logo-1.webp' => 'Ooredoo logo', 'home-logo-2.webp' => 'First Abu Dhabi Bank logo',
		'home-logo-3.webp' => 'Cure Medical Centres logo', 'home-logo-4.webp' => 'Alshaya Group logo',
		'home-logo-5.webp' => 'Nesto logo', 'home-logo-6.webp' => 'Ceer logo',
		'home-logo-7.webp' => 'Alpha Data logo', 'home-logo-8.webp' => 'Cognizant logo',
		'home-logo-9.webp' => 'Edelweiss logo', 'home-logo-10.webp' => 'MAP logo',
		'home-logo-11.webp' => 'AG&P logo', 'home-logo-12.webp' => 'Dexian logo',
		'home-logo-13.webp' => 'HP logo', 'home-logo-14.webp' => 'Katara logo',
		'home-logo-15.webp' => 'Dubai Culture logo', 'home-logo-16.webp' => 'Western International Group logo',
		'home-logo-17.webp' => 'Adnoc logo', 'home-logo-18.webp' => 'Global Care logo',
		'home-logo-19.webp' => 'IBM logo', 'home-logo-20.webp' => 'Ericsson logo',
		'home-logo-21.webp' => 'Deloitte logo', 'home-logo-22.webp' => 'RIVIAN logo',
		'home-logo-23.webp' => 'ANZ logo', 'home-convocation.webp' => 'Doctorate Convocation Ceremony of IMET Worldwide',
		'home-case-1.webp' => 'Dr. Ernesto Rufino Jr.', 'home-case-2.webp' => 'Dr. Christian Senyo Atitsogbu',
		'home-case-3.webp' => 'Dr. Shan Shokanwar', 'home-case-4.webp' => 'Dr. Sona Saha Das',
		'home-conv-1.webp' => 'Doctorate Convocation Ceremony of IMET Worldwide', 'home-conv-2.webp' => 'Doctorate Convocation Ceremony of IMET Worldwide',
		'home-conv-3.webp' => 'Doctorate Convocation Ceremony of IMET Worldwide',
		'home-bulb.webp' => 'Light bulb',
		'home-journals.webp' => 'International journals: IEEE, ResearchGate, Elsevier Scopus, Google Scholar, UGC, Web of Science', 'home-map.webp' => 'Map of IMET Worldwide affiliated information centers across the USA, Europe, Dubai (UAE) and Singapore',
		'home-story-1.webp' => 'Dr. Shijin Prasad', 'home-story-2.webp' => 'Dr. Shibin Chulliparambil',
		'home-story-3.webp' => 'Dr. Abhishek Mathur', 'home-story-4.webp' => 'Dr. Prabhkar Ramakrishnan',
		'home-client-1.webp' => 'Dr. Davinder Pal S.', 'home-client-2.webp' => 'Dinesh Singh',
		'home-client-3.webp' => 'Meheriar Patel', 'home-client-4.webp' => 'Dr. Amey Naik',
		'home-client-5.webp' => 'Dr. Vikrant Nivate', 'home-client-6.webp' => 'Dr. Jey Radha',
		'home-client-7.webp' => 'Dr. Shijin Prasad R. S.', 'home-client-8.webp' => 'Dr. Suresh A Shan',
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
