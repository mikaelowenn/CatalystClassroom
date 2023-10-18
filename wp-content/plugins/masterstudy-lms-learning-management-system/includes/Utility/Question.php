<?php

namespace MasterStudy\Lms\Utility;

use MasterStudy\Lms\Plugin\PostType;

final class Question {
	public static function filter_allow_access( int $user_id, array $question_ids ): array {
		if ( empty( $question_ids ) ) {
			return array();
		}

		$post_ids = get_posts(
			array(
				'post_type'      => PostType::QUESTION,
				'post__in'       => $question_ids,
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'author'         => user_can( $user_id, 'administrator' ) ? null : $user_id,
			)
		);

		return array_intersect( $question_ids, $post_ids );
	}
}
