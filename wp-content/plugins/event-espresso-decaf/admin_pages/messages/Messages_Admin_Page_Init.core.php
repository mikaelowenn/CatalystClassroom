<?php

/**
 * Messages_Admin_Page_Init
 *
 * for setup of the message admin pages
 *
 * @package         Event Espresso
 * @subpackage      includes/core/message/EE_Message_Admin_Page_Init.core.php
 * @author          Darren Ethier
 *
 * ------------------------------------------------------------------------
 */
class Messages_Admin_Page_Init extends EE_Admin_Page_Init
{
    /**
     *constructor
     *
     * @Constructor
     * @access public
     * @return void
     */
    public function __construct()
    {

        do_action('AHEE_log', __FILE__, __FUNCTION__, '');

        if (! defined('EE_MSG_PG_SLUG')) {
            define('EE_MSG_PG_SLUG', 'espresso_messages');
            define('EE_MSG_PG_NAME', ucwords(str_replace('_', '', EE_MSG_PG_SLUG)));
            define('EE_MSG_ADMIN', EE_ADMIN_PAGES . 'messages/');
            define('EE_MSG_ADMIN_URL', admin_url('admin.php?page=' . EE_MSG_PG_SLUG));
            define('EE_MSG_ASSETS_PATH', EE_MSG_ADMIN . 'assets/');
            define('EE_MSG_ASSETS_URL', EE_ADMIN_PAGES_URL . 'messages/assets/');
            define('EE_MSG_TEMPLATE_PATH', EE_MSG_ADMIN . 'templates/');
            define('EE_MSG_TEMPLATE_URL', EE_ADMIN_PAGES_URL . 'messages/templates/');
        }

        parent::__construct();
    }


    protected function _set_init_properties()
    {
        $this->label = esc_html__('Messages System', 'event_espresso');
    }


    protected function _set_menu_map()
    {
        $this->_menu_map = new EE_Admin_Page_Sub_Menu(
            array(
                'menu_group'      => 'management',
                'menu_order'      => 10,
                'show_on_menu'    => EE_Admin_Page_Menu_Map::BLOG_ADMIN_ONLY,
                'parent_slug'     => 'espresso_events',
                'menu_slug'       => EE_MSG_PG_SLUG,
                'menu_label'      => esc_html__('Messages', 'event_espresso'),
                'capability'      => 'ee_read_global_messages',
                'admin_init_page' => $this,
            )
        );
    }
}
