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
                add_filter('woocommerce_settings_tabs_array', array( $this, 'add_settings_tab'), 80);
                add_action('woocommerce_settings_dashboard_messages', array( $this, 'add_settings'), 10);
                add_action('woocommerce_update_options_dashboard_messages', array( $this, 'update_settings'), 10);
                add_action('init', array($this, 'display_dashboard_messages'));
                add_action('add_meta_boxes', array($this, 'dashboard_messages_custom_meta'));

                // Meta box callback
                function dashboard_meta_callback($product){
                    wp_nonce_field( basename( __FILE__ ), 'dashboard_nonce' );
                    $dashboard_stored_meta = get_post_meta( $product->ID );
                    ?>

                    <p>
                        <label for="meta-textarea" class="dashboard-row-title" style="display: block;"><?php _e( 'Enter a message for customers to see after purchasing this product.', 'woocommerce-dashboard-messages' )?></label>
                        <textarea name="meta-textarea" id="meta-textarea" style="width: 100%;min-height: 200px;"><?php if ( isset ( $dashboard_stored_meta['meta-textarea'] ) ) echo $dashboard_stored_meta['meta-textarea'][0]; ?></textarea>
                    </p>

                    <?php
                }

                // Save the custom meta input
                function dashboard_meta_save( $post_id ) {
                
                    // Checks save status
                    $is_autosave = wp_is_post_autosave( $post_id );
                    $is_revision = wp_is_post_revision( $post_id );
                    $is_valid_nonce = ( isset( $_POST[ 'dashboard_nonce' ] ) && wp_verify_nonce( $_POST[ 'dashboard_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
                
                    // Exits script depending on save status
                    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
                        return;
                    }

                    // Checks for input and saves if needed
                    if( isset( $_POST[ 'meta-textarea' ] ) ) {
                        update_post_meta( $post_id, 'meta-textarea', $_POST[ 'meta-textarea' ] );
                    }
                
                }
                add_action( 'save_post', 'dashboard_meta_save' );
            }

            // Create settings tab
            public function add_settings_tab($settings_tabs){
                $settings_tabs['dashboard_messages'] = __('Dashboard Messages', 'woocommerce-dashboard-messages');
                return $settings_tabs;
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

                // check if the setting is checked and on account page
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

            // Add meta box for products
            public function dashboard_messages_custom_meta(){
                add_meta_box( 'dashboard_meta', __( 'Account Dashboard Messages', 'woocommerce-dashboard-messages' ), 'dashboard_meta_callback', 'product' );
            }

        }
        $GLOBALS['wc_dashboard_messages'] = new WC_Dashboard_Messages();
    }

}

/* To temporarily test display in template
<?php
    $meta_value = get_post_meta( get_the_ID(), 'meta-textarea', true );
 
    if( !empty( $meta_value ) ) {
        echo $meta_value;
    }
?>
*/

