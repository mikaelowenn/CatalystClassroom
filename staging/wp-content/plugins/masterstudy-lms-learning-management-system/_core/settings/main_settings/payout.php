<?php

function stm_lms_settings_payout_section() {
	return array(
		'name'   => esc_html__( 'Payout', 'masterstudy-lms-learning-management-system' ),
		'label'  => esc_html__( 'Payout Settings', 'masterstudy-lms-learning-management-system' ),
		'icon'   => 'fas fa-hand-holding-usd',
		'fields' => array(
			'payout' => array(
				'pro'     => true,
				'pro_url' => admin_url( 'admin.php?page=stm-lms-go-pro' ),
				'type'    => 'payout',
				'label'   => esc_html__( 'Masterstudy LMS PRO Payout', 'masterstudy-lms-learning-management-system' ),
			),
		),
	);
}
