<?php

namespace MasterStudy\Lms\Http\Controllers\Quiz;

use MasterStudy\Lms\Http\Serializers\CustomFieldsSerializer;
use MasterStudy\Lms\Http\WpResponseFactory;
use MasterStudy\Lms\Repositories\QuestionRepository;
use MasterStudy\Lms\Repositories\QuizRepository;

class GetController {
	public function __invoke( int $quiz_id ) {
		$repo = new QuizRepository();
		$quiz = $repo->get( $quiz_id );

		if ( null === $quiz ) {
			return WpResponseFactory::not_found();
		}

		if ( ! empty( $quiz['questions'] ) ) {
			$question_repo     = new QuestionRepository();
			$quiz['questions'] = $question_repo->get_all( $quiz['questions'] );
		}

		return new \WP_REST_Response(
			array(
				'quiz'          => $quiz,
				'custom_fields' => ( new CustomFieldsSerializer() )->collectionToArray(
					$quiz_id,
					apply_filters( 'masterstudy_lms_quiz_custom_fields', array() )
				),

			)
		);
	}
}
