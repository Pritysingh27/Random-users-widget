<?php
/*
Plugin Name: Random Users Widget for Elementor
Description: A custom Elementor widget that displays random users from the Random User API.
Version: 1.0.0
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define constants
define( 'RUW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Function to check if Elementor is active
function ruw_check_for_elementor() {
    // Check if Elementor is active
    if ( ! did_action( 'elementor/loaded' ) ) {
        // Display an admin notice if Elementor is not active
        add_action( 'admin_notices', 'ruw_admin_notice_missing_elementor' );
        return;
    }

    // Register the widget with Elementor
    add_action( 'elementor/widgets/register', 'ruw_register_widgets' );
}
add_action( 'plugins_loaded', 'ruw_check_for_elementor' );

// Admin notice for missing Elementor
function ruw_admin_notice_missing_elementor() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e( 'Random Users Widget requires Elementor to be installed and activated.', 'random-users-widget' ); ?></p>
    </div>
    <?php
}

// Register the widget
function ruw_register_widgets( $widgets_manager ) {
    require_once RUW_PLUGIN_DIR . 'widgets/random-users-widget.php';
    $widgets_manager->register( new \RUW\Widgets\Random_Users_Widget() );
}

// Enqueue styles and scripts
function ruw_enqueue_styles() {
    wp_enqueue_style( 'ruw-styles', plugin_dir_url( __FILE__ ) . 'assets/style.css' );
}
add_action( 'wp_enqueue_scripts', 'ruw_enqueue_styles' );
