<?php

add_action(
	'admin_enqueue_scripts',
	function () {
		$version = ( WP_DEBUG ) ? time() : STM_LMS_DB_VERSION;

		stm_lms_register_script( 'admin/lms_sub_menu' );
		/** enqueue styles **/
		wp_enqueue_style( 'stm_lms_starter_theme', STM_LMS_URL . 'includes/starter-theme/assets/main.css', array( 'wp-admin' ), $version );
		wp_enqueue_style( 'font-awesome-min', STM_LMS_URL . '/assets/vendors/font-awesome.min.css', null, $version, 'all' );

		/** enqueue javascript **/
		wp_enqueue_script( 'stm_lms_starter_theme', STM_LMS_URL . 'includes/starter-theme/assets/main.js', array( 'jquery-core' ), $version, true );
		wp_localize_script(
			'stm_lms_starter_theme',
			'stm_lms_starter_theme_data',
			array(
				'stm_lms_admin_ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
			)
		);

		wp_enqueue_style( 'stm_lms_icons', STM_LMS_URL . 'assets/icons/style.css', null, $version );
		stm_lms_register_script( 'admin/admin', array( 'jquery' ), true );
		stm_lms_register_script( 'admin/sortable_menu', array( 'jquery' ), true );

		stm_lms_register_script( 'payout/user-search', array( 'vue.js', 'vue-select.js' ) );

		wp_localize_script(
			'stm-lms-payout/user-search',
			'stm_payout_url_data',
			array(
				'url' => get_site_url() . STM_LMS_BASE_API_URL,
			)
		);

		stm_lms_register_style( 'nuxy/main' );

		wp_register_script( 'masterstudy-vue', STM_LMS_URL . '/assets/js/vendors/vue.js', array(), $version, true );
		wp_register_script( 'masterstudy-vue-resource', STM_LMS_URL . '/assets/js/vendors/vue-resource.js', array( 'masterstudy-vue' ), $version, true );
		wp_register_script( 'masterstudy-vue-range-slider', STM_LMS_URL . '/assets/js/vendors/vue-range-slider.js', array( 'masterstudy-vue' ), $version, true );
	}
);

/** Show notice to install starter theme */

function stm_lms_add_theme_caps() {

	$instructors   = array();
	$admin_users   = array();
	$admin_users[] = get_role( 'administrator' );
	$instructors[] = get_role( 'stm_lms_instructor' );

	if ( ! empty( $admin_users ) ) {
		foreach ( $admin_users as $user ) {
			if ( empty( $user ) ) {
				continue;
			}
			foreach ( array( 'publish', 'delete', 'delete_others', 'delete_private', 'delete_published', 'edit', 'edit_others', 'edit_private', 'edit_published', 'read_private' ) as $cap ) {
				$user->add_cap( "{$cap}_stm_lms_posts" );
			}
		}
	}

	if ( ! empty( $instructors ) ) {
		foreach ( $instructors as $user ) {
			if ( empty( $user ) ) {
				continue;
			}
			foreach ( array( 'publish', 'delete', 'edit' ) as $cap ) {
				$user->add_cap( 'edit_posts' );
				$user->add_cap( "{$cap}_stm_lms_posts" );
			}
		}
	}

}

add_action( 'init', 'stm_lms_add_theme_caps' );

add_action(
	'wp_ajax_stm_lms_hide_announcement',
	function() {
		check_ajax_referer( 'stm_lms_hide_announcement', 'nonce' );
		set_transient( 'stm_lms_app_notice', '1', MONTH_IN_SECONDS );
	}
);

add_action( 'admin_init', 'stm_lms_deny_instructor_admin' );

function stm_lms_deny_instructor_admin() {
	if ( ! wp_doing_ajax() && ! empty( STM_LMS_Options::get_option( 'deny_instructor_admin', '' ) ) ) {
		$user = wp_get_current_user();
		// phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
		if ( in_array( 'stm_lms_instructor', (array) $user->roles ) ) {
			wp_safe_redirect( STM_LMS_User::user_page_url() );
			die();
		}
	}
}

add_action(
	'save_post_stm-courses',
	function ( $post_id, $post, $update ) {
		if ( ! $update ) {
			return;
		}
		$created = get_option( 'stm_lms_course_created', false );
		if ( ! $created ) {
			$data = array(
				'show_time'   => time(),
				'step'        => 0,
				'prev_action' => '',
			);
			set_transient( 'stm_masterstudy-lms-learning-management-system_single_notice_setting', $data );
			update_option( 'stm_lms_course_created', true );
		}

	},
	20,
	3
);

add_action(
	'delete_user',
	function ( $user_id ) {
		$the_query = array(
			'post_type' => array( 'stm-reviews' ),
			'author'    => $user_id,
		);
		$posts     = new WP_Query( $the_query );
		if ( ! empty( $posts ) ) {
			foreach ( $posts->posts as $post ) {
				wp_delete_post( $post->ID );
			}
		}
		wp_reset_postdata();
	}
);

add_action(
	'stm_admin_notice_rate_masterstudy-lms-learning-management-system_single',
	function ( $data ) {
		if ( is_array( $data ) ) {
			$data['title']   = 'Yoo-hoo!';
			$data['content'] = 'You have created your first course! Enjoyed? Help us to rate <strong>MasterStudy 5 Stars!</strong>';
		}

		return $data;
	},
	100
);

add_action(
	'admin_url',
	function ( $url, $path ) {
		if ( home_url( '/' ) === ms_plugin_manage_course_url() ) {
			return $url;
		}

		switch ( $path ) {
			case 'post-new.php?post_type=stm-courses':
				$url = ms_plugin_user_account_url( 'edit-course' );
				break;
			case 'post-new.php?post_type=stm-lessons':
				$url = ms_plugin_user_account_url( 'edit-lesson' );
				break;
			case 'post-new.php?post_type=stm-quizzes':
				$url = ms_plugin_user_account_url( 'edit-quiz' );
				break;
			case 'post-new.php?post_type=stm-assignments':
				$url = ms_plugin_user_account_url( 'edit-assignment' );
				break;
			case 'post-new.php?post_type=stm-google-meets':
				$url = ms_plugin_user_account_url( 'edit-google-meet' );
				break;
		}

		return $url;
	},
	10,
	2
);

add_action(
	'edit_form_after_title',
	function ( $post ) {
		$edit_url = ms_plugin_edit_course_builder_url( $post->post_type );

		if ( empty( $edit_url ) || home_url( '/' ) === $edit_url ) {
			return;
		}
		?>
		<style>
			#ms-lms-course-builder {
				height: 300px;
				width: 100%;
				margin: 15px 0;
				transition: all .5s ease;
			}
			#ms-lms-course-builder a {
				position: relative;
				height: 100%;
				display: flex;
				justify-content: center;
				align-items: center;
				text-decoration: none;
				border: 1px solid #ddd;
				background-color: #f7f7f7;
				cursor: pointer;
			}
			#ms-lms-course-builder a:hover {
				background-color: #fff;
			}
			#ms-lms-course-builder a i:before {
				vertical-align: sub;
				margin-right: 3px;
			}
			#ms-lms-course-builder .button-edit-course-builder {
				background: #227AFF;
				border-color: #227AFF;
			}
			#elementor-switch-mode-button,
			#elementor-editor-button {
				background: #93003c;
				border-color: #93003c;
			}
		</style>
		<div id="ms-lms-course-builder">
			<a href="<?php echo esc_url( "{$edit_url}{$post->ID}/" ); ?>">
				<div class="button button-primary button-hero button-edit-course-builder">
					<i class="dashicons-before dashicons-edit-large" aria-hidden="true"></i>
					<?php echo esc_html__( 'Edit with Course Builder', 'masterstudy-lms-learning-management-system' ); ?>
				</div>
			</a>
		</div>
		<?php
	},
	1
);

add_action(
	'post_row_actions',
	function ( $actions, $post ) {
		if ( 'trash' === $post->post_status ) {
			return $actions;
		}

		$edit_url = ms_plugin_edit_course_builder_url( $post->post_type );

		if ( ! empty( $edit_url ) && home_url( '/' ) !== $edit_url ) {
			$actions[] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				esc_url( $edit_url . "{$post->ID}/" ),
				esc_attr__( 'Edit with Course Builder', 'masterstudy-lms-learning-management-system' ),
				esc_html__( 'Edit with Course Builder', 'masterstudy-lms-learning-management-system' )
			);
		}

		return $actions;
	},
	1,
	2
);

add_action( 'trashed_post', 'stm_lms_route_trash_page_handler' );

function stm_lms_route_trash_page_handler( $post_id ) {
	if ( get_post_type( $post_id ) === 'page' ) {
		$settings = get_option( 'stm_lms_settings', array() );
		$pages    = array( 'user_url', 'user_url_profile', 'wishlist_url', 'checkout_url' );

		foreach ( $pages as $page ) {
			if ( isset( $settings[ $page ] ) && intval( $settings[ $page ] ) === $post_id ) {
				$settings[ $page ] = '';
			}
		}

		update_option( 'stm_lms_settings', $settings );
	}
}
