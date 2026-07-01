<?php
/**
 * GrowthPress REST API Class - Automation Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_API {

    public function __construct() {
        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    }

    /**
     * Strategic API Hub
     *
     * This module manages external connectivity nodes and autonomous webhooks.
     * Pro-Tip: Use the Financial Ledger Sync for real-time ROI tracking.
     */
    public function register_routes() {
        register_rest_route( 'growthpress/v1', '/leads', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'create_lead' ),
            'permission_callback' => array( $this, 'check_api_permission' ),
        ) );

        // Missed Call Automation Webhook
        register_rest_route( 'growthpress/v1', '/missed-call', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'handle_missed_call' ),
            'permission_callback' => array( $this, 'check_api_permission' ),
        ) );

        // Financial Ledger Sync
        register_rest_route( 'growthpress/v1', '/sync-transactions', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'sync_transaction' ),
            'permission_callback' => array( $this, 'check_api_permission' ),
        ) );

        // Operational Availability Check
        register_rest_route( 'growthpress/v1', '/get-availability', array(
            'methods'  => 'GET',
            'callback' => array( $this, 'get_availability' ),
            'permission_callback' => array( $this, 'check_api_permission' ),
        ) );

        // Voice Triage Webhook
        register_rest_route( 'growthpress/v1', '/voice-triage', array(
            'methods'  => 'POST',
            'callback' => array( $this, 'handle_voice_triage' ),
            'permission_callback' => '__return_true', // Twilio public webhook
        ) );
    }

    public function handle_voice_triage( $request ) {
        $from = $request->get_param('From');
        $niche = get_option('growthpress_niche', 'business');
        $brand = get_option('growthpress_brand_name', 'GrowthPress');

        $ai = GrowthPress_AI::get_instance();
        $voice_prompt = $ai->call_ai("Generate a short, professional script (max 30 words) for a phone greeting for $brand, an elite $niche firm. We are busy helping other clients. Tell them to state their name and inquiry after the beep.", "Voice AI Scriptwriter");

        header('Content-Type: text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<Response>';
        echo '<Say voice="Polly.Brian" language="en-US">' . esc_html($voice_prompt) . '</Say>';
        echo '<Record action="' . esc_url(rest_url('growthpress/v1/voice-process')) . '" maxLength="30" />';
        echo '</Response>';
        exit;
    }

    public function check_api_permission() {
        $auth_token = get_option('growthpress_api_token', '');
        if ( empty($auth_token) ) return false;

        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        return ($header === "Bearer $auth_token");
    }

    public function handle_missed_call( $request ) {
        $from = $request->get_param('From');

        $ai = GrowthPress_AI::get_instance();
        $sms_body = $ai->generate_missed_call_reply($from);

        GrowthPress_Activity::log( "Missed call from $from. AI response generated." );

        return new WP_REST_Response( array( 'reply' => $sms_body, 'status' => 'handled' ), 200 );
    }

    public function sync_transaction( $request ) {
        $params = $request->get_params();
        $amount = floatval($params['amount']);
        $title = sanitize_text_field($params['title'] ?? 'External Transaction');

        $transaction_id = wp_insert_post( array(
            'post_title' => $title,
            'post_type' => 'gp_transaction',
            'post_status' => 'publish'
        ) );

        if ($transaction_id) {
            update_post_meta($transaction_id, '_amount', $amount);
            update_post_meta($transaction_id, '_status', 'Paid');
            update_post_meta($transaction_id, '_transaction_type', 'Revenue');
            GrowthPress_Activity::log( "Financial node synced via API: $title ($$amount)" );
            return new WP_REST_Response( array('id' => $transaction_id), 201 );
        }
        return new WP_Error('failed', 'Ledger Sync Error', array('status' => 500));
    }

    public function get_availability( $request ) {
        $appts = get_posts(array(
            'post_type' => 'gp_appointment',
            'posts_per_page' => -1,
            'meta_key' => '_appointment_date',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        ));

        $booked_slots = array();
        foreach($appts as $a) {
            $booked_slots[] = get_post_meta($a->ID, '_appointment_date', true);
        }

        return new WP_REST_Response( array('booked_slots' => $booked_slots, 'status' => 'active'), 200 );
    }

    public function create_lead( $request ) {
        $params = $request->get_params();
        $name = isset($params['name']) ? sanitize_text_field($params['name']) : 'New API Lead';
        $email = isset($params['email']) ? sanitize_email($params['email']) : '';

        $lead_id = wp_insert_post( array(
            'post_title' => $name,
            'post_type' => 'gp_lead',
            'post_status' => 'publish'
        ) );
        if ($lead_id) {
            if ($email) update_post_meta($lead_id, '_lead_email', $email);
            do_action('gp_lead_captured', $lead_id);
            return new WP_REST_Response( array('id' => $lead_id), 201 );
        }
        return new WP_Error('failed', 'Error', array('status' => 500));
    }
}

new GrowthPress_API();
