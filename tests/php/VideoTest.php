<?php
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/** includes/video.php */
final class VideoTest extends TestCase {

	public static function urls(): array {
		return array(
			'watch'        => array( 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ' ),
			'watch + list' => array( 'https://www.youtube.com/watch?list=PL1&v=dQw4w9WgXcQ', 'dQw4w9WgXcQ' ),
			'short link'   => array( 'https://youtu.be/dQw4w9WgXcQ?t=10', 'dQw4w9WgXcQ' ),
			'embed'        => array( 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'dQw4w9WgXcQ' ),
			'shorts'       => array( 'https://www.youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ' ),
			'live'         => array( 'https://www.youtube.com/live/dQw4w9WgXcQ', 'dQw4w9WgXcQ' ),
			'id with - _'  => array( 'https://youtu.be/a-b_c1D2e3F', 'a-b_c1D2e3F' ),
			'not a video'  => array( 'https://example.com/page', '' ),
			'empty'        => array( '', '' ),
			'null'         => array( null, '' ),
		);
	}

	#[DataProvider( 'urls' )]
	public function test_extracts_the_video_id( $url, string $expected ): void {
		$this->assertSame( $expected, imet_blocks_youtube_id( $url ) );
	}
}
