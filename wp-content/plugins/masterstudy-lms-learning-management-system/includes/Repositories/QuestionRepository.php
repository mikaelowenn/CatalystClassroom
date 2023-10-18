<?php

namespace MasterStudy\Lms\Repositories;

use MasterStudy\Lms\Enums\QuestionType;
use MasterStudy\Lms\Plugin\PostType;
use MasterStudy\Lms\Plugin\Taxonomy;

final class QuestionRepository extends AbstractRepository {
	protected static string $post_type = PostType::QUESTION;

	protected static array $fields_meta_map = array(
		'answers'     => 'answers',
		'explanation' => 'question_explanation',
		'image'       => 'image',
		'hint'        => 'question_hint',
		'type'        => 'type',
		'view_type'   => 'question_view_type',
	);

	protected static array $fields_post_map = array(
		'question' => 'post_title',
	);

	protected static array $fields_taxonomy_map = array(
		'categories' => Taxonomy::QUESTION_CATEGORY,
	);

	protected static array $casts = array(
		'answers' => 'list',
		'image'   => 'nullable',
	);

	public function get_all( array $questions ) {
		$list_types = array( QuestionType::SINGLE_CHOICE, QuestionType::MULTI_CHOICE, QuestionType::IMAGE_MATCH );
		$questions  = array_map(
			function ( $question ) use ( $list_types ) {
				$question = $this->get( $question );

				if ( isset( $question['type'] ) && empty( $question['type'] ) ) {
					$question['type'] = QuestionType::SINGLE_CHOICE;
				}

				if ( empty( $question['view_type'] ) && in_array( $question['type'] ?? '', $list_types, true ) ) {
					$question['view_type'] = 'list';
				}

				return $question;
			},
			$questions
		);

		return array_filter( $questions );
	}

	public function create( array $data ): int {
		$data = $this->resolve_bank_categories( $data );
		return parent::create( $data );
	}

	public function update( int $question_id, array $data ): void {
		$data = $this->resolve_bank_categories( $data );
		parent::update( $question_id, $data );
	}

	private function resolve_bank_categories( array $data ) {
		if ( QuestionType::QUESTION_BANK !== $data['type'] || empty( $data['categories'][0] ) ) {
			return $data;
		}

		$categories = (array) $data['categories'];
		if ( ! is_numeric( $categories[0] ) ) {
			return $data;
		}

		$terms = get_terms(
			array(
				'taxonomy' => Taxonomy::QUESTION_CATEGORY,
				'include'  => wp_parse_id_list( $categories ),
			)
		);

		$data['answers'][0]['categories'] = array_map(
			function ( \WP_Term $term ) {
				return $term->to_array();
			},
			$terms
		);

		$data['categories'] = array();

		return $data;
	}

}
