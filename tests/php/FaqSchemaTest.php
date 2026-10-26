<?php
use PHPUnit\Framework\TestCase;

/** includes/faq-schema.php: FAQPage JSON-LD built from the Details blocks inside the faq-acc group. */
final class FaqSchemaTest extends TestCase {

	private function details( string $question, string $answer ): array {
		return array(
			'blockName'   => 'core/details',
			'attrs'       => array(),
			'innerHTML'   => "<details><summary>$question</summary></details>",
			'innerBlocks' => array( array( 'blockName' => 'core/paragraph', 'attrs' => array(), 'innerHTML' => "<p>$answer</p>", 'innerBlocks' => array() ) ),
		);
	}

	private function group( array $children, string $class = 'faq-acc' ): array {
		return array( 'blockName' => 'core/group', 'attrs' => array( 'className' => $class ), 'innerHTML' => '', 'innerBlocks' => $children );
	}

	public function test_builds_question_and_answer_pairs(): void {
		$items = imet_blocks_faq_items( array( $this->group( array( $this->details( 'What is a DBA?', 'A doctorate.' ) ) ) ) );
		$this->assertSame(
			array( array( '@type' => 'Question', 'name' => 'What is a DBA?', 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => 'A doctorate.' ) ) ),
			$items
		);
	}

	public function test_strips_html_from_questions_and_answers(): void {
		$items = imet_blocks_faq_items( array( $this->group( array( $this->details( '<strong>Cost?</strong>', 'See <a href="#">fees</a>.' ) ) ) ) );
		$this->assertSame( 'Cost?', $items[0]['name'] );
		$this->assertSame( 'See fees.', $items[0]['acceptedAnswer']['text'] );
	}

	public function test_ignores_details_outside_the_faq_group(): void {
		$items = imet_blocks_faq_items( array( $this->group( array( $this->details( 'Not FAQ', 'Nope' ) ), 'other' ) ) );
		$this->assertSame( array(), $items );
	}

	public function test_finds_details_nested_deeper_inside_the_group(): void {
		$inner = array( 'blockName' => 'core/group', 'attrs' => array(), 'innerHTML' => '', 'innerBlocks' => array( $this->details( 'Deep?', 'Yes' ) ) );
		$this->assertCount( 1, imet_blocks_faq_items( array( $this->group( array( $inner ) ) ) ) );
	}

	public function test_skips_questions_with_no_answer(): void {
		$empty = $this->details( 'Unanswered?', '' );
		$this->assertSame( array(), imet_blocks_faq_items( array( $this->group( array( $empty ) ) ) ) );
	}

	public function test_keeps_the_order_of_the_questions(): void {
		$items = imet_blocks_faq_items( array( $this->group( array( $this->details( 'One', 'a' ), $this->details( 'Two', 'b' ), $this->details( 'Three', 'c' ) ) ) ) );
		$this->assertSame( array( 'One', 'Two', 'Three' ), array_column( $items, 'name' ) );
	}

	public function test_prints_valid_faqpage_json_ld_once(): void {
		imet_blocks_faq_collect( imet_blocks_faq_items( array( $this->group( array( $this->details( 'Q?', 'A.' ) ) ) ) ) );

		ob_start();
		imet_blocks_faq_print();
		imet_blocks_faq_print(); // head and footer both call it: only the first prints
		$out = ob_get_clean();

		$this->assertSame( 1, substr_count( $out, 'application/ld+json' ) );
		preg_match( '#<script[^>]*>(.*)</script>#s', $out, $m );
		$json = json_decode( $m[1], true );
		$this->assertSame( 'FAQPage', $json['@type'] );
		$this->assertSame( 'Q?', $json['mainEntity'][0]['name'] );
	}
}
