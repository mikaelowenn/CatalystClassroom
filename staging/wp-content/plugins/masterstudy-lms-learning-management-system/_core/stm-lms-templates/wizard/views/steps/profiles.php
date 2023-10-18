<div class="stm_lms_splash_wizard__content_tab"
	v-if="active_step === 'profiles'">
	<h4>
		<?php esc_html_e( 'Profiles', 'masterstudy-lms-learning-management-system' ); ?>
	</h4>
	<hr v-if="isMarketPlace()"/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch"
		v-if="isMarketPlace()"
		v-bind:class="{'active' : wizard.disable_instructor_premoderation}">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Instructor Premoderation', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/switcher',
				array(
					'model' => 'wizard.disable_instructor_premoderation',
					'desc'  => esc_html__( 'Enable this if users need to get approved by the admin to become an instructor.', 'masterstudy-lms-learning-management-system' ),
				)
			);
			?>
		</div>
	</div>
	<hr/>
	<div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch"
		v-bind:class="{'active' : wizard.register_as_instructor}">
		<?php
		STM_LMS_Templates::show_lms_template(
			'wizard/views/field_data',
			array(
				'title' => esc_html__( 'Instructor Registration', 'masterstudy-lms-learning-management-system' ),
			)
		);
		?>
		<div class="stm_lms_splash_wizard__field_input">
			<?php
			STM_LMS_Templates::show_lms_template(
				'wizard/fields/switcher',
				array(
					'model' => 'wizard.register_as_instructor',
					'desc'  => esc_html__( 'By disabling the instructor registration, you remove the checkbox "Register as an instructor" from the registration form.', 'masterstudy-lms-learning-management-system' ),
				)
			);
			?>
		</div>
	</div>
</div>
