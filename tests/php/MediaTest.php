<?php
use PHPUnit\Framework\TestCase;

/** includes/media.php: image tokens in default content. */
final class MediaTest extends TestCase {

	protected function setUp(): void {
		$GLOBALS['imet_test']['options']     = array();
		$GLOBALS['imet_test']['attachments'] = array();
	}

	public function test_url_token_falls_back_to_the_plugin_copy_when_not_imported(): void {
		$this->assertSame(
			'<img src="' . IMET_BLOCKS_URL . 'assets/images/highlights.webp">',
			imet_blocks_media_tokens( '<img src="{{URL:highlights.webp}}">' )
		);
	}

	public function test_id_token_is_zero_when_not_imported(): void {
		$this->assertSame( 'wp-image-0', imet_blocks_media_tokens( 'wp-image-{{ID:highlights.webp}}' ) );
	}

	public function test_tokens_use_the_media_library_copy_once_imported(): void {
		$GLOBALS['imet_test']['options']['imet_blocks_media'] = array( 'highlights.webp' => 42 );
		$GLOBALS['imet_test']['attachments'][42]              = 'https://example.test/uploads/highlights.webp';

		$this->assertSame( 'wp-image-42', imet_blocks_media_tokens( 'wp-image-{{ID:highlights.webp}}' ) );
		$this->assertSame( 'https://example.test/uploads/highlights.webp', imet_blocks_media_tokens( '{{URL:highlights.webp}}' ) );
	}

	public function test_old_plugin_option_is_still_read(): void {
		$GLOBALS['imet_test']['options']['imet_generic_media'] = array( 'cert-graduate.jpg' => 7 );
		$this->assertSame( array( 'cert-graduate.jpg' => 7 ), imet_blocks_media_map() );
	}

	public function test_a_broken_option_gives_an_empty_map(): void {
		$GLOBALS['imet_test']['options']['imet_blocks_media'] = 'garbage';
		$GLOBALS['imet_test']['options']['imet_generic_media'] = 'garbage';
		$this->assertSame( array(), imet_blocks_media_map() );
	}

	public function test_text_without_tokens_and_null_pass_through(): void {
		$this->assertSame( '<p>Hello</p>', imet_blocks_media_tokens( '<p>Hello</p>' ) );
		$this->assertSame( '', imet_blocks_media_tokens( null ) );
	}

	public function test_every_listed_file_has_alt_text_and_exists(): void {
		foreach ( imet_blocks_media_files() as $file => $alt ) {
			$this->assertFileExists( IMET_BLOCKS_DIR . 'assets/images/' . $file );
			$this->assertIsString( $alt );
		}
	}
}
