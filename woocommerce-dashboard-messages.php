<?php
/*
Plugin Name: WooCommerce Dashboard Messages
Plugin URI:  https://thadroe.com
Description: Display custom Dashboard messages to customers based on products purchased
Version:     1.0
Author:      Thad Roe
Author URI:  https://thadroe.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: woocommerce-dashboard-messages
Domain Path: /languages
*/

// Check if WooCommerce is active
if ( in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    // Run if class name is unique
    if ( ! class_exists('WC_Dashboard_Messages')){
        class WC_Dashboard_Messages{
            public function __construct(){
                add_filter('woocommerce_get_sections_products', array( $this, 'add_settings_section'), 20);
                add_action('woocommerce_settings_dashboard_messages', array( $this, 'add_settings'), 10);
                add_action('woocommerce_update_options_dashboard_messages', array( $this, 'update_settings'), 10);
                add_action('init', array ($this, 'display_dashboard_messages'));
            }

            // Create settings tab
            public function add_settings_section($sections){
                $sections['dashboard_messages'] = __('Dashboard Messages', 'woocommerce-dashboard-messages');
                return $sections;
            }

            // Create settings
            public function add_settings(){
                woocommerce_admin_fields( self::get_settings() );
            }

            // Save the settings
            public function update_settings(){
                woocommerce_update_options( self::get_settings() );
            }

            // Turn on metabox and message displays if messages enabled
            public function display_dashboard_messages(){
                $messages_on = get_option('wc_dashboard_messages_on') == 'yes' ? true : false;

                // check if the setting is checked 
                if($messages_on && TRUE && is_account_page()){
                }
            }

            // get settings
            public function get_settings(){
                $settings = array(
                    'section_title' => array(
                        'name'          => __( 'Dashboard Messages', 'woocommerce-dashboard-messages'),
                        'type'          => 'title',
                        'desc'          => '',
                        'id'            => 'wc_settings_dashboard_messages_section_title'
                    ),
                    'dashboard_messages' => array(
                        'name'          => __( 'Enable Messages', 'woocommerce-dashboard-messages'),
                        'type'          => 'checkbox',
                        'desc'          => __('Check and save to enable messages for the my account page. A new textarea box will appear in the product editor for you to enter your product-specific messages.', 'woocommerce-dashboard-messages'),
                        'id'            => 'wc_dashboard_messages_on'
                    ),
                    'section_end' => array(
                        'type'          => 'sectionend',
                        'id'            => 'wc_settings_dashboard_messages_section_end'
                    )
                );
                return $settings;
            }

        }
        $GLOBALS['wc_dashboard_messages'] = new WC_Dashboard_Messages();
    }

}

