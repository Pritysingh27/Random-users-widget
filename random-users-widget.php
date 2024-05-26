<?php
/*
Plugin Name: Random Users Widget for Elementor
Description: A custom Elementor widget that displays random users from the Random User API.
Version: 1.0.0
Author: Preeti Singh
Text Domain: random-users-widget
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
define( 'RUW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
function ruw_check_for_elementor() {
    if ( ! did_action( 'elementor/loaded' ) ) {
         add_action( 'admin_notices', 'ruw_admin_notice_missing_elementor' );
        return;
    }
 add_action( 'elementor/widgets/register', 'ruw_register_widgets' );
}
add_action( 'plugins_loaded', 'ruw_check_for_elementor' );
function ruw_admin_notice_missing_elementor() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php esc_html_e( 'Random Users Widget requires Elementor to be installed and activated.', 'random-users-widget' ); ?></p>
    </div>
    <?php
}
function ruw_register_widgets( $widgets_manager ) {
    require_once RUW_PLUGIN_DIR . 'widgets/random-users-widget.php';
    $widgets_manager->register( new \RUW\Widgets\Random_Users_Widget() );
}
function ruw_enqueue_styles() {
    wp_enqueue_style( 'ruw-styles', plugin_dir_url( __FILE__ ) . 'assets/style.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'ruw_enqueue_styles' );
