<?php
/**
 * GrowthPress License Management Engine - Strategic Hub
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_License {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'register_license_cpt' ) );
    }

    public function register_license_cpt() {
        if ( ! post_type_exists( 'gp_license' ) ) {
            register_post_type( 'gp_license', array(
                'labels'      => array( 'name' => 'Licenses', 'singular_name' => 'License' ),
                'public'      => false,
                'show_ui'     => true,
                'menu_icon'   => 'dashicons-awards',
                'supports'    => array( 'title', 'custom-fields' ),
                'show_in_menu' => 'growthpress-dashboard'
            ) );
        }
    }

    public function issue_license( $customer_email, $tier = 'Elite' ) {
        $key = 'GP-' . strtoupper( wp_generate_password( 4, false ) ) . '-' . strtoupper( wp_generate_password( 4, false ) ) . '-' . strtoupper( wp_generate_password( 4, false ) );

        $id = wp_insert_post( array(
            'post_title'   => $key,
            'post_type'    => 'gp_license',
            'post_status'  => 'publish'
        ) );

        if ( $id ) {
            update_post_meta( $id, '_customer_email', sanitize_email( $customer_email ) );
            update_post_meta( $id, '_license_tier', $tier );
            update_post_meta( $id, '_license_status', 'active' );
            update_post_meta( $id, '_issued_at', current_time( 'mysql' ) );
            update_post_meta( $id, '_activation_count', 0 );

            // Feature capability mapping
            $capabilities = array(
                'Lite'  => array('crm', 'booking'),
                'Pro'   => array('crm', 'booking', 'ai_content', 'payments'),
                'Elite' => array('crm', 'booking', 'ai_content', 'payments', 'multi_ai', 'agency_white_label', 'full_ecosystem')
            );
            update_post_meta( $id, '_capabilities', $capabilities[$tier] ?? $capabilities['Elite'] );

            GrowthPress_Activity::log( "License Node Issued: $key [$tier] to $customer_email" );
            return $key;
        }

        return false;
    }

    public function validate_license( $key ) {
        if ( empty( $key ) ) return false;

        $posts = get_posts( array(
            'post_type'  => 'gp_license',
            'title'      => $key,
            'post_status' => 'publish',
            'posts_per_page' => 1
        ) );

        if ( ! empty( $posts ) ) {
            $status = get_post_meta( $posts[0]->ID, '_license_status', true );
            if ( $status === 'active' ) {
                $count = (int)get_post_meta( $posts[0]->ID, '_activation_count', true );
                update_post_meta( $posts[0]->ID, '_activation_count', $count + 1 );
                update_post_meta( $posts[0]->ID, '_last_validated', current_time('mysql') );
                return true;
            }
        }

        // Developer Fallback
        if ( strpos( $key, 'GP-DEV-' ) === 0 ) return true;

        return false;
    }

    public function get_license_data( $key ) {
        $posts = get_posts( array(
            'post_type'  => 'gp_license',
            'title'      => $key,
            'post_status' => 'publish',
            'posts_per_page' => 1
        ) );

        if ( empty($posts) ) return null;

        $post_id = $posts[0]->ID;
        return array(
            'key' => $key,
            'tier' => get_post_meta($post_id, '_license_tier', true),
            'status' => get_post_meta($post_id, '_license_status', true),
            'capabilities' => get_post_meta($post_id, '_capabilities', true),
            'activations' => get_post_meta($post_id, '_activation_count', true)
        );
    }

    public function get_all_licenses() {
        return get_posts( array(
            'post_type'      => 'gp_license',
            'posts_per_page' => -1,
            'post_status'    => 'any'
        ) );
    }

    public function revoke_license( $license_id ) {
        update_post_meta( $license_id, '_license_status', 'revoked' );
        GrowthPress_Activity::log( "License Node Revoked: ID #$license_id" );
    }
}

GrowthPress_License::get_instance();
