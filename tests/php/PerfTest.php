<?php
use PHPUnit\Framework\TestCase;

/** includes/perf.php: the HTML filter for the IMET Landing template (regex based, so it needs the tests most). */
final class PerfTest extends TestCase {

	public function test_preg_safe_keeps_the_previous_html_when_a_regex_fails(): void {
		$this->assertSame( '<p>old</p>', imet_blocks_preg_safe( '<p>old</p>', null ) );
		$this->assertSame( 'new', imet_blocks_preg_safe( 'old', 'new' ) );
		$this->assertSame( '', imet_blocks_preg_safe( 'old', '' ) );
	}

	public function test_html_without_a_head_is_returned_unchanged(): void {
		$this->assertSame( '<p>fragment</p>', imet_blocks_perf_filter_html( '<p>fragment</p>' ) );
	}

	public function test_google_fonts_links_are_removed_from_head(): void {
		$html = '<html><head><link rel="preconnect" href="https://fonts.gstatic.com"><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter"><title>T</title></head><body></body></html>';
		$out  = imet_blocks_perf_filter_html( $html );
		$this->assertStringNotContainsString( 'fonts.googleapis.com', $out );
		$this->assertStringNotContainsString( 'fonts.gstatic.com', $out );
		$this->assertStringContainsString( '<title>T</title>', $out );
	}

	public function test_google_fonts_import_is_removed_from_inline_css(): void {
		$html = '<html><head><title>T</title><meta name="x" content="@import url(https://fonts.googleapis.com/css?family=Inter);"></head><body></body></html>';
		$this->assertStringNotContainsString( 'fonts.googleapis.com', imet_blocks_perf_filter_html( $html ) );
	}

	public function test_gtag_library_is_delayed_but_the_inline_config_stays(): void {
		$html = '<html><head><script async src="https://www.googletagmanager.com/gtag/js?id=G-TEST"></script>'
			. '<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}</script></head><body></body></html>';
		$out  = imet_blocks_perf_filter_html( $html );
		$this->assertStringContainsString( '<script type="imet/delayed" data-src="https://www.googletagmanager.com/gtag/js?id=G-TEST">', $out );
		$this->assertStringContainsString( 'function gtag(){dataLayer.push(arguments);}', $out );
		$this->assertStringNotContainsString( '<script async src="https://www.googletagmanager.com/gtag/js', $out );
	}

	public function test_gtm_and_clarity_inline_loaders_are_delayed(): void {
		$gtm      = "<script>(function(w,d,s,l,i){var j=d.createElement(s);j.src='https://www.googletagmanager.com/gtm.js?id='+i;})(window,document,'script','dataLayer','GTM-X');</script>";
		$clarity  = '<script type="text/javascript">(function(c,l,a,r,i,t,y){t=l.createElement(r);t.src="https://www.clarity.ms/tag/"+i;})(window,document,"clarity","script","abc");</script>';
		$out      = imet_blocks_perf_filter_html( "<html><head>$gtm</head><body>$clarity</body></html>" );
		$this->assertSame( 2, substr_count( $out, '<script type="imet/delayed"' ) );
		$this->assertStringNotContainsString( 'type="text/javascript"', $out );
	}

	public function test_json_ld_and_other_scripts_are_left_alone(): void {
		$ld   = '<script type="application/ld+json" id="imet-faq-schema">{"@type":"FAQPage","note":"googletagmanager.com/gtm.js"}</script>';
		$mod  = '<script type="module">import "./clarity.ms/tag";</script>';
		$out  = imet_blocks_perf_filter_html( "<html><head>$ld$mod</head><body></body></html>" );
		$this->assertStringContainsString( $ld, $out );
		$this->assertStringContainsString( $mod, $out );
	}

	public function test_loader_is_added_once_just_before_the_closing_body_tag(): void {
		$out = imet_blocks_perf_filter_html( '<html><head></head><body><p>x</p></body></html>' );
		$this->assertSame( 1, substr_count( $out, 'var d=0;function r()' ) );
		$this->assertSame( 1, substr_count( $out, 'addEventListener("load"' ) );
		$this->assertMatchesRegularExpression( '#</script>\s*</body>#', $out );
		$this->assertStringContainsString( '"pointerdown","keydown","scroll","touchstart"', $out );
	}

	public function test_stylesheets_printed_in_the_body_move_into_head(): void {
		$css = '<link rel="stylesheet" href="https://cdn.example.org/header.css">';
		$out = imet_blocks_perf_filter_html( "<html><head><title>T</title></head><body>$css<p>x</p></body></html>" );
		$this->assertLessThan( stripos( $out, '</head>' ), strpos( $out, 'header.css' ), 'stylesheet should now be in <head>' );
		$this->assertSame( 1, substr_count( $out, 'header.css' ) );
	}
}
