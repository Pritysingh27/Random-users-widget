<?php
namespace RUW\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Random_Users_Widget extends Widget_Base {

    public function get_name() {
        return 'random_users_widget';
    }

    public function get_title() {
        return __( 'Random Users Widget', 'random-users-widget' );
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'random-users-widget' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'random-users-widget' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Random Users', 'random-users-widget' ),
                'placeholder' => __( 'Enter your title', 'random-users-widget' ),
            ]
        );

        $this->add_control(
            'number_of_users',
            [
                'label' => __( 'Number of Users', 'random-users-widget' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'default' => 5,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $title = $settings['title'];
        $number_of_users = $settings['number_of_users'];

        echo '<div class="random-users-widget">';
        if ( ! empty( $title ) ) {
            echo '<h2>' . esc_html( $title ) . '</h2>';
        }
        echo '<div class="users-list">';

        $response = wp_remote_get( "https://randomuser.me/api/?results=" . intval( $number_of_users ) );
        if ( is_wp_error( $response ) ) {
            echo '<p>' . esc_html__( 'Failed to retrieve users', 'random-users-widget' ) . '</p>';
            return;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );

        if ( isset( $data->results ) ) {
            foreach ( $data->results as $user ) {
                echo '<div class="user">';
                echo '<img src="' . esc_url( $user->picture->thumbnail ) . '" alt="' . esc_attr( $user->name->first . ' ' . $user->name->last ) . '">';
                echo '<p>' . esc_html( $user->name->first . ' ' . $user->name->last ) . '</p>';
                echo '<p>' . esc_html( $user->email ) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>' . esc_html__( 'No users found', 'random-users-widget' ) . '</p>';
        }

        echo '</div>';
        echo '</div>';
    }

    protected function _content_template() {
        ?>
        <# if ( settings.title ) { #>
            <h2>{{{ settings.title }}}</h2>
        <# } #>
        <div class="users-list">
            <# for ( var i = 0; i < settings.number_of_users; i++ ) { #>
                <div class="user">
                    <img src="https://randomuser.me/api/portraits/thumb/men/{{{ i }}}.jpg" alt="{{{ __('User', 'random-users-widget') }}}">
                    <p>{{{ __('User Name', 'random-users-widget') }}}</p>
                    <p>{{{ __('user@example.com', 'random-users-widget') }}}</p>
                </div>
            <# } #>
        </div>
        <?php
    }
}
