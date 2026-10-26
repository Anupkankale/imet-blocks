<?php
// This file is generated. Do not modify it manually.
return array(
	'about-card' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/about-card',
		'title' => 'IMET About Card',
		'category' => 'imet',
		'icon' => 'id-alt',
		'description' => 'Header and intro, then a gradient card: a tick list beside a titled list with a gold timeline line.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section about-card',
			'anchor' => 'about'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'achievements' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/achievements',
		'title' => 'IMET Achievements',
		'category' => 'imet',
		'icon' => 'chart-bar',
		'description' => 'Navy card with animated counters.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'achieve',
			'anchor' => 'presence'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js'
	),
	'alumni-video' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/alumni-video',
		'title' => 'IMET Alumni Videos',
		'category' => 'imet',
		'icon' => 'video-alt3',
		'description' => 'Video gallery: main player and three thumbnails. Click a video image → Link → paste its YouTube URL; it plays in place.',
		'keywords' => array(
			'imet',
			'section',
			'video',
			'youtube',
			'testimonials'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section alumni',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'analytics' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/analytics',
		'title' => 'IMET Image + Bullet List',
		'category' => 'imet',
		'icon' => 'analytics',
		'description' => 'Image beside an Insights header, dotted list and button.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section analytics',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'career-table' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/career-table',
		'title' => 'IMET Career Path Table',
		'category' => 'imet',
		'icon' => 'editor-table',
		'description' => 'Our USP\'s table: 5 columns × 5 rows. The first row shows; See More / Close opens the rest. Keep five entries per column.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section career',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js'
	),
	'certificate' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/certificate',
		'title' => 'IMET Certificate',
		'category' => 'imet',
		'icon' => 'media-document',
		'description' => 'Photo beside a header, tick list and button.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section cert',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'cost-banner' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/cost-banner',
		'title' => 'IMET Cost Banner',
		'category' => 'imet',
		'icon' => 'money-alt',
		'description' => 'Rounded fee card: price, scholarship ribbon and eligibility button.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'cost-banner',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'cta' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/cta',
		'title' => 'IMET Call to Action',
		'category' => 'imet',
		'icon' => 'button',
		'description' => 'Rounded deep-blue CTA card with heading, text, button and photo.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'cta-wrap',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'compact',
				'label' => 'Compact'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'cyber-banner' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/cyber-banner',
		'title' => 'IMET CTA Banner with Avatars',
		'category' => 'imet',
		'icon' => 'megaphone',
		'description' => 'Blue rounded card: heading, two buttons and floating avatars.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'cyber',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'dba-vs-phd' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/dba-vs-phd',
		'title' => 'IMET Comparison Table',
		'category' => 'imet',
		'icon' => 'columns',
		'description' => 'Header and a DBA vs PhD comparison table with a highlighted DBA column.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section vs',
			'anchor' => 'dba-vs-phd'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'embassy' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/embassy',
		'title' => 'IMET Recognition Logos',
		'category' => 'imet',
		'icon' => 'awards',
		'description' => 'Header and a row of captioned emblems/logos.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section embassy',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Emblems with captions',
				'isDefault' => true
			),
			array(
				'name' => 'logos',
				'label' => 'Logos'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		),
		'viewScript' => 'file:./view.js'
	),
	'faq' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/faq',
		'title' => 'IMET FAQ',
		'category' => 'imet',
		'icon' => 'editor-help',
		'description' => 'Accordion of Details blocks (first 5 shown, See More for the rest); outputs FAQPage structured data.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section faq',
			'anchor' => 'faq'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'compact',
				'label' => 'Compact'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'float-contact' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/float-contact',
		'title' => 'IMET Floating Contact',
		'category' => 'imet',
		'icon' => 'whatsapp',
		'description' => 'Fixed WhatsApp contact link (bottom left).',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'div',
			'className' => 'float-contact',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'hero' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/hero',
		'title' => 'IMET Hero',
		'category' => 'imet',
		'icon' => 'cover-image',
		'description' => 'Headline, stats, button, enquiry form card and accreditation logos (a marquee on phones).',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'hero',
			'anchor' => 'top'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Light',
				'isDefault' => true
			),
			array(
				'name' => 'dark',
				'label' => 'Dark'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'highlights' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/highlights',
		'title' => 'IMET Highlights',
		'category' => 'imet',
		'icon' => 'yes-alt',
		'description' => 'Overview header, icon list (bold label in gold), button and an illustration.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'highlights',
			'anchor' => 'overview'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'wide',
				'label' => 'Wide'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'overview' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/overview',
		'title' => 'IMET Program Overview',
		'category' => 'imet',
		'icon' => 'text-page',
		'description' => 'What is a DBA?: header, six benefit cards that work as tabs (first open) and a photo.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section overview',
			'anchor' => 'programs'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'photo',
				'label' => 'Photo'
			)
		)
	),
	'process' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/process',
		'title' => 'IMET Process Timeline',
		'category' => 'imet',
		'icon' => 'editor-ol',
		'description' => 'Header, then steps on a horizontal timeline (title, dot, text) and a button. Vertical on phones.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section process',
			'anchor' => 'admission'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'section' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/section',
		'title' => 'IMET Section (blank)',
		'category' => 'imet',
		'icon' => 'align-wide',
		'description' => 'Empty section with the IMET design (fonts, buttons, utilities). Build any new section inside it; add u-bg-navy / u-bg-soft / u-bg-card for a background.',
		'keywords' => array(
			'imet',
			'section',
			'blank',
			'container'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section imet-blank',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'specializations' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/specializations',
		'title' => 'IMET Specializations',
		'category' => 'imet',
		'icon' => 'tag',
		'description' => 'Our Programs: linked program cards, 4 per view, continuous marquee scroll.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section specs',
			'anchor' => 'specializations'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Four per view (scrolling)',
				'isDefault' => true
			),
			array(
				'name' => 'five',
				'label' => 'Five per row'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'step-cards' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/step-cards',
		'title' => 'IMET Step Cards',
		'category' => 'imet',
		'icon' => 'grid-view',
		'description' => 'Header, then numbered step cards in two columns (navy and light, alternating), each with a gold tag, title, text and photo.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section step-cards',
			'anchor' => 'research-steps'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		),
		'viewScript' => 'file:./view.js'
	),
	'subnav' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/subnav',
		'title' => 'IMET Section Navigation',
		'category' => 'imet',
		'icon' => 'menu',
		'description' => 'Sticky bar with links to sections on the page (highlights the section in view).',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'nav',
			'className' => 'subnav',
			'anchor' => 'subnav'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js'
	),
	'tabs' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/tabs',
		'title' => 'IMET Tabs',
		'category' => 'imet',
		'icon' => 'index-card',
		'description' => 'Header, a row of tab labels and one panel per label (e.g. Skills / Who should do / Careers / Tools). The first tab is open.',
		'keywords' => array(
			'imet',
			'section',
			'tabs'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section tabs',
			'anchor' => 'learn-more'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'testimonials' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/testimonials',
		'title' => 'IMET Testimonials',
		'category' => 'imet',
		'icon' => 'format-quote',
		'description' => 'Header, quote cards with a LinkedIn link, and a View More link. Duplicate a card to add one; set each LinkedIn link to the person\'s profile.',
		'keywords' => array(
			'imet',
			'section',
			'testimonials',
			'reviews',
			'clients'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section testimonials',
			'anchor' => ''
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Default',
				'isDefault' => true
			),
			array(
				'name' => 'compact',
				'label' => 'Compact'
			),
			array(
				'name' => 'home',
				'label' => 'Home'
			)
		)
	),
	'usp-bento' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/usp-bento',
		'title' => 'IMET USP Bento Grid',
		'category' => 'imet',
		'icon' => 'grid-view',
		'description' => 'Six feature cards in a two-column grid and a button.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section usp',
			'anchor' => 'usp'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		)
	),
	'who-can-join' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'imet/who-can-join',
		'title' => 'IMET Who Can Join',
		'category' => 'imet',
		'icon' => 'groups',
		'description' => 'Accordion (one open at a time, smooth animation) beside a photo.',
		'keywords' => array(
			'imet',
			'section'
		),
		'version' => '1.0.0',
		'textdomain' => 'imet-blocks',
		'supports' => array(
			'anchor' => true,
			'html' => false
		),
		'imet' => array(
			'tagName' => 'section',
			'className' => 'section join',
			'anchor' => 'who-can-join'
		),
		'editorScript' => 'file:./index.js',
		'style' => array(
			'imet-base',
			'file:./style-index.css'
		),
		'viewScript' => 'file:./view.js',
		'styles' => array(
			array(
				'name' => 'default',
				'label' => 'Lines',
				'isDefault' => true
			),
			array(
				'name' => 'boxed',
				'label' => 'Boxed'
			)
		)
	)
);
