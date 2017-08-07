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
            }

        }
        $GLOBALS['wc_dashboard_messages'] = new WC_Dashboard_Messages();
    }

}

