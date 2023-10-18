<?php

/** @var \MasterStudy\Lms\Plugin $plugin */

use MasterStudy\Lms\Repositories\CurriculumRepository;
use MasterStudy\Lms\Repositories\CurriculumSectionRepository;
use MasterStudy\Lms\Plugin\PostType;

add_action( 'init', array( $plugin, 'init' ) );
add_action( 'rest_api_init', array( $plugin, 'register_api' ) );

add_action(
	'plugins_loaded',
	function () use ( $plugin ) {
		$plugin->register_addons( apply_filters( 'masterstudy_lms_plugin_addons', array() ) );
	}
);

add_action(
	'delete_post',
	function ( int $post_id, \WP_Post $post ) {
		if ( PostType::COURSE === $post->post_type ) {
			( new CurriculumSectionRepository() )->delete_course_sections( $post_id );
		}
	},
	10,
	2
);

add_action(
	'dp_duplicate_post',
	function ( $post_id, $post ) {
		if ( PostType::COURSE === $post->post_type ) {
			( new CurriculumRepository() )->duplicate_curriculum( $post->ID, $post_id );
		}
	},
	10,
	2
);
