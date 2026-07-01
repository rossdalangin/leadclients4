<?php
/**
 * GrowthPress Social Proof & Market Authority Feed - v1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Social_Proof {

    /**
     * Strategic Social Proof Node
     *
     * Manages market authority signals and real-time conversion notifications.
     * Strategic Note: Use these feeds to boost trust in high-stakes acquisition hubs.
     */
    public function __construct() {
        add_action( 'wp_ajax_gp_get_authority_feed', array( $this, 'handle_authority_feed' ) );
        add_action( 'wp_ajax_nopriv_gp_get_authority_feed', array( $this, 'handle_authority_feed' ) );
    }

    public function handle_authority_feed() {
        $events = array();

        // Recent Leads (Privacy Focused)
        $leads = get_posts(array('post_type' => 'gp_lead', 'posts_per_page' => 3, 'post_status' => 'publish'));
        foreach($leads as $l) {
            $events[] = array(
                'type' => 'conversion',
                'title' => 'New Strategy Request',
                'msg' => 'A user in ' . (get_post_meta($l->ID, '_lead_zip', true) ?: 'the region') . ' just initialized a growth sequence.',
                'time' => 'Recently'
            );
        }

        // Recent High Ratings
        $reviews = get_posts(array('post_type' => 'gp_review', 'meta_key' => '_gp_rating', 'meta_value' => '5', 'posts_per_page' => 2));
        foreach($reviews as $r) {
            $events[] = array(
                'type' => 'authority',
                'title' => 'Market Authority Verified',
                'msg' => esc_html($r->post_title) . ' verified an elite result profile.',
                'time' => 'Verified'
            );
        }

        // Recent Bookings
        $appts = get_posts(array('post_type' => 'gp_appointment', 'posts_per_page' => 2, 'post_status' => 'publish'));
        foreach($appts as $a) {
            $events[] = array(
                'type' => 'booking',
                'title' => 'Specialist Secured',
                'msg' => 'A strategy briefing was just confirmed in the master calendar.',
                'time' => 'Active'
            );
        }

        shuffle($events);
        wp_send_json_success(array_slice($events, 0, 5));
    }
}
new GrowthPress_Social_Proof();
