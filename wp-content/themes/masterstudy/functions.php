<?php
$stm_token_option = get_site_option( 'stm_masterstudy_token', [] );
$stm_token_option['token'] = 'activated';
update_site_option( 'stm_masterstudy_token', $stm_token_option );
$theme_info = wp_get_theme();
define( 'STM_THEME_VERSION', ( WP_DEBUG ) ? time() : $theme_info->get( 'Version' ) );
define( 'STM_MS_SHORTCODES', '1' );

define( 'STM_THEME_NAME', 'Masterstudy' );
define( 'STM_THEME_CATEGORY', 'Education WordPress Theme' );
define( 'STM_ENVATO_ID', '12170274' );
define( 'STM_TOKEN_OPTION', 'stm_masterstudy_token' );
define( 'STM_TOKEN_CHECKED_OPTION', 'stm_masterstudy_token_checked' );
define( 'STM_THEME_SETTINGS_URL', 'stm_option_options' );
define( 'STM_GENERATE_TOKEN', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/installation-and-activation/theme-activation' );
define( 'STM_SUBMIT_A_TICKET', 'https://support.stylemixthemes.com/tickets/new/support?item_id=12' );
define( 'STM_DEMO_SITE_URL', 'https://masterstudy.stylemixthemes.com/' );
define( 'STM_DOCUMENTATION_URL', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/' );
define( 'STM_CHANGELOG_URL', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/extra-materials/changelog' );
define( 'STM_INSTRUCTIONS_URL', 'https://docs.stylemixthemes.com/masterstudy-theme-documentation/installation-and-activation/theme-activation' );
define( 'STM_INSTALL_VIDEO_URL', 'https://www.youtube.com/watch?v=a8zb5KTAw48&list=PL3Pyh_1kFGGDikfKuVbGb_dqKmXZY86Ve&index=6&ab_channel=StylemixThemes' );
define( 'STM_VOTE_URL', 'https://stylemixthemes.cnflx.io/boards/masterstudy-lms' );
define( 'STM_BUY_ANOTHER_LICENSE', 'https://themeforest.net/item/masterstudy-education-center-wordpress-theme/12170274' );
define( 'STM_VIDEO_TUTORIALS', 'https://www.youtube.com/playlist?list=PL3Pyh_1kFGGDikfKuVbGb_dqKmXZY86Ve' );
define( 'STM_FACEBOOK_COMMUNITY', 'https://www.facebook.com/groups/masterstudylms' );
define( 'STM_TEMPLATE_URI', get_template_directory_uri() );
define( 'STM_TEMPLATE_DIR', get_template_directory() );
define( 'STM_THEME_SLUG', 'stm' );
define( 'STM_INC_PATH', get_template_directory() . '/inc' );

$inc_path     = get_template_directory() . '/inc';
$widgets_path = get_template_directory() . '/inc/widgets';
// Theme setups


add_filter( 'stm_theme_default_layout', 'get_stm_theme_default_layout' );
function get_stm_theme_default_layout() {
	return 'default';
}

add_filter( 'stm_theme_default_layout_name', 'get_stm_theme_default_layout_name' );
function get_stm_theme_default_layout_name() {
	return 'classic_lms';
}

add_filter( 'stm_theme_demos', 'masterstudy_get_demos' );
add_filter( 'stm_theme_demo_layout', 'stm_get_layout' );
add_filter( 'stm_theme_plugins', 'get_stm_theme_plugins' );
add_filter( 'stm_theme_layout_plugins', 'stm_layout_plugins', 10, 1 );

function get_stm_theme_plugins() {
	return stm_require_plugins( true );
}

add_filter( 'stm_theme_enable_elementor', 'get_stm_theme_enable_elementor' );

function get_stm_theme_enable_elementor() {
	return true;
}

add_filter( 'stm_theme_secondary_required_plugins', 'get_stm_theme_secondary_required_plugins' );
add_filter( 'stm_theme_elementor_addon', 'get_stm_theme_elementor_addon' );
add_action( 'stm_reset_theme_options', 'do_stm_reset_theme_options' );


if ( is_admin() && file_exists( get_template_directory() . '/admin/admin.php' ) ) {
	require_once get_template_directory() . '/admin/admin.php';
}

// Custom code and theme main setups
require_once $inc_path . '/setup.php';

// Header an Footer actions
require_once $inc_path . '/header.php';
require_once $inc_path . '/footer.php';

// Enqueue scripts and styles for theme
require_once $inc_path . '/scripts_styles.php';

/*Theme configs*/
require_once $inc_path . '/theme-config.php';

// Visual composer custom modules
if ( defined( 'WPB_VC_VERSION' ) ) {
	require_once $inc_path . '/visual_composer.php';
}

require_once $inc_path . '/elementor.php';

// Custom code for any outputs modifying
//require_once($inc_path . '/payment.php');
require_once $inc_path . '/custom.php';

// Custom code for woocommerce modifying
if ( class_exists( 'WooCommerce' ) ) {
	require_once $inc_path . '/woocommerce_setups.php';
}

if ( defined( 'STM_LMS_URL' ) ) {
	require_once $inc_path . '/lms/main.php';
}
function stm_glob_pagenow() {
	global $pagenow;

	return $pagenow;
}

function stm_glob_wpdb() {
	global $wpdb;

	return $wpdb;
}

add_filter( 'woocommerce_add_cart_item', 'dhp_remove_active_course', 10, 2 );

function dhp_remove_active_course($cart_item_data, $cart_id) {
	global $wpdb;
	
	$prefix = $wpdb->prefix;
	$uid = get_current_user_id();

	$request_check = "select s.membership_id from `{$prefix}users` as u inner join `{$prefix}pmpro_memberships_users` s on u.ID = s.user_id and s.status = 'active' and s.enddate > CURRENT_TIME and s.user_id = {$uid} limit 1";
	
	$check = $wpdb->get_results( $request_check, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	if (! empty($check)) {
		$req_member_products = "select post_id from `{$prefix}postmeta` where meta_key = '_membership_product_level'";
		$member_products = $wpdb->get_results( $req_member_products, ARRAY_A ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		if (!empty($member_products)) {
			$flag = false;
			foreach ($member_products as &$value) {
				if ($value['post_id'] == $cart_item_data['product_id']) {
					wc_clear_notices();
					wc_add_notice("You already have an active subscription.", "error");
					$flag = true;
					break;
				}
			}
			if ($flag) {
				return;
			}
		}
	}
	
	return $cart_item_data;

}
// Start tech task 1
function update_event_id_and_participant_count(int $event_id, string $user_email) {
    // Update event id di tabel user
    $query = "UPDATE user
                SET event_id = $event_id
                WHERE user_email = '$user_email';";
    $result = $wpdb->query($query);

    if ($result) {
        // Update participant count di tabel event
        $query = "UPDATE event
                SET participant_count = participant_count + 1
                WHERE event_id = $event_id;";
        $result = $wpdb->query($query);
    }
}
// End tech task 1

//Announcement banner
if ( is_admin() ) {
    require_once $inc_path . '/admin/generate_styles.php';
    require_once $inc_path . '/admin/admin_helpers.php';
    require_once $inc_path . '/tgm/tgm-plugin-registration.php';
}

if ( class_exists( 'BuddyPress' ) ) {
	require_once $inc_path . '/buddypress.php';
}

//Announcement banner
if ( is_admin() ) {
	require_once $inc_path . '/admin/generate_styles.php';
	require_once $inc_path . '/admin/admin_helpers.php';
	require_once $inc_path . '/tgm/tgm-plugin-registration.php';
}
