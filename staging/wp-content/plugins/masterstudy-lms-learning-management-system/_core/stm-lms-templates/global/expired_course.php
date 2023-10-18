<?php

/**
 * @var $course_id
 * @var $expired_popup
 */

stm_lms_register_style( 'expiration/main' );

$user_id                = get_current_user_id();
$course_expiration_days = STM_LMS_Course::get_course_expiration_days( $course_id );
$is_course_time_expired = STM_LMS_Course::is_course_time_expired( $user_id, $course_id );
$user_course            = STM_LMS_Course::get_user_course( $user_id, $course_id );
$course_duration_time   = STM_LMS_Course::get_course_duration_time( $course_id );
$course_end_time        = ! empty( $course_duration_time ) && ! empty( $user_course['start_time'] )
	? intval( $user_course['start_time'] ) + $course_duration_time
	: null;

$expired_popup = ( isset( $expired_popup ) ) ? $expired_popup : true;

/*If we just telling about course expiration info*/
if ( empty( $user_course ) && ! empty( $course_expiration_days ) ) {
	STM_LMS_Templates::show_lms_template( 'expiration/info', compact( 'course_expiration_days' ) );

	/*If we have course and time is not expired*/
} elseif ( ! $is_course_time_expired && ! empty( $user_course ) && ! empty( $course_expiration_days ) ) {
	STM_LMS_Templates::show_lms_template( 'expiration/not_expired', compact( 'course_id', 'course_end_time' ) );

	/*If we have course and time is expired*/
} elseif ( $is_course_time_expired && ! empty( $user_course ) && ! empty( $course_expiration_days ) ) {
	STM_LMS_Templates::show_lms_template( 'expiration/info', compact( 'course_expiration_days' ) );

	if ( $expired_popup ) {
		STM_LMS_Templates::show_lms_template( 'expiration/expired', compact( 'course_id', 'course_end_time' ) );
	}
}
