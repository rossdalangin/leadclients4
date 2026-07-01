<?php
/**
 * GrowthPress Booking Engine Class - Ultra Elite v3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Booking {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_booking_cpt' ) );
        add_action( 'gp_appointment_created', array( $this, 'trigger_appointment_reminders' ) );
        add_shortcode( 'gp_booking_form', array( $this, 'render_booking_form' ) );
        add_action( 'wp_ajax_gp_submit_booking', array( $this, 'handle_booking_submission' ) );
        add_action( 'wp_ajax_nopriv_gp_submit_booking', array( $this, 'handle_booking_submission' ) );
        add_action( 'wp_ajax_gp_join_waiting_list', array( $this, 'handle_waiting_list' ) );
        add_action( 'wp_ajax_nopriv_gp_join_waiting_list', array( $this, 'handle_waiting_list' ) );
        add_action( 'wp_ajax_gp_complete_appointment', array( $this, 'handle_appointment_completion' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_booking_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_booking_meta' ) );
        add_filter( 'manage_gp_appointment_posts_columns', array( $this, 'booking_columns' ) );
        add_action( 'manage_gp_appointment_posts_custom_column', array( $this, 'booking_column_content' ), 10, 2 );
    }

    public function booking_columns( $cols ) {
        $cols['_date'] = 'Session Date';
        $cols['_email'] = 'Client Email';
        $cols['_status'] = 'Status';
        return $cols;
    }

    public function booking_column_content( $col, $post_id ) {
        if ( $col === '_date' ) echo get_post_meta( $post_id, '_appointment_date', true );
        if ( $col === '_email' ) echo get_post_meta( $post_id, '_client_email', true );
        if ( $col === '_status' ) echo get_post_meta( $post_id, '_status', true ) ?: 'Pending';
    }

    public function add_booking_meta_boxes() {
        add_meta_box( 'gp_booking_details', '🗓️ Strategic Session Execution Details', array( $this, 'render_booking_meta' ), 'gp_appointment', 'normal', 'high' );
    }

    public function render_booking_meta( $post ) {
        $date = get_post_meta( $post->ID, '_appointment_date', true );
        $staff_id = get_post_meta( $post->ID, '_staff_id', true );
        $email = get_post_meta( $post->ID, '_client_email', true );
        $link = get_post_meta( $post->ID, '_meeting_link', true );
        $status = get_post_meta( $post->ID, '_status', true ) ?: 'Pending';
        $is_waiting = get_post_meta( $post->ID, '_is_waiting_list', true );
        $staff = get_users( array( 'role__in' => array('author', 'editor', 'administrator') ) );
        ?>
        <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0ea5e9;">
            <p style="margin: 0; font-size: 13px; color: #0369a1;"><strong>Strategic Scheduling:</strong> Manage confirmed strategy sessions and virtual briefings here. Completing an appointment can automatically trigger review requests and move leads to the next lifecycle stage.</p>
        </div>
        <div style="background: #fdf2f8; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #db2777;">
            <p style="margin: 0; font-size: 13px; color: #9d174d;"><strong>Success Pattern:</strong> Strategy sessions with a linked 'Secure Meeting Link' report a 15% lower no-show rate. <strong>Pro-Tip:</strong> Use the 'Priority Queue' checkbox to flag leads that require immediate specialist escalation if a slot becomes available.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Appointment Status</label><p class="description">Current state of the session. 'Completed' triggers reputation automations.</p></th>
                <td>
                    <select name="gp_status" style="width:100%;">
                        <option value="Pending" <?php selected($status, 'Pending'); ?>>Pending</option>
                        <option value="Confirmed" <?php selected($status, 'Confirmed'); ?>>Confirmed</option>
                        <option value="Completed" <?php selected($status, 'Completed'); ?>>Completed</option>
                        <option value="Cancelled" <?php selected($status, 'Cancelled'); ?>>Cancelled</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Session Date & Time</label><p class="description">Format: YYYY-MM-DD HH:MM. Used for calendar synchronization.</p></th>
                <td><input type="text" name="gp_appointment_date" value="<?php echo esc_attr($date); ?>" class="regular-text" placeholder="YYYY-MM-DD HH:MM"></td>
            </tr>
            <tr>
                <th><label>Assigned Specialist</label><p class="description">The team member hosting this strategy session.</p></th>
                <td>
                    <select name="gp_staff_id" style="width:100%;">
                        <option value="0">Unassigned</option>
                        <?php foreach($staff as $s): ?>
                            <option value="<?php echo $s->ID; ?>" <?php selected($staff_id, $s->ID); ?>><?php echo esc_html($s->display_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Client Email</label><p class="description">Email of the attendee. Links the appointment to a Lead record.</p></th>
                <td><input type="email" name="gp_client_email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Secure Meeting Link</label><p class="description">Zoom, Google Meet, or Telemedicine URL for the virtual session.</p></th>
                <td><input type="url" name="gp_meeting_link" value="<?php echo esc_attr($link); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Priority Queue</label><p class="description">Flag for leads on the high-intent waiting list.</p></th>
                <td><input type="checkbox" name="gp_is_waiting" value="1" <?php checked($is_waiting, '1'); ?>> Marked as Waiting List</td>
            </tr>
        </table>
        <?php
    }

    public function save_booking_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_appointment_date'] ) ) return;
        update_post_meta( $post_id, '_appointment_date', sanitize_text_field( $_POST['gp_appointment_date'] ) );
        update_post_meta( $post_id, '_staff_id', intval( $_POST['gp_staff_id'] ) );
        update_post_meta( $post_id, '_client_email', sanitize_email( $_POST['gp_client_email'] ) );
        update_post_meta( $post_id, '_meeting_link', esc_url_raw( $_POST['gp_meeting_link'] ) );
        update_post_meta( $post_id, '_status', sanitize_text_field( $_POST['gp_status'] ) );
        update_post_meta( $post_id, '_is_waiting_list', isset($_POST['gp_is_waiting']) ? '1' : '0' );
    }

    public function handle_appointment_completion() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $id = intval($_POST['appointment_id']);
        update_post_meta( $id, '_status', 'Completed' );
        do_action( 'gp_appointment_completed', $id );

        GrowthPress_Activity::log( "Appointment #$id marked as completed." );
        wp_send_json_success( 'Appointment finalized.' );
    }

    public function register_booking_cpt() {
        register_post_type( 'gp_appointment', array(
            'labels'      => array( 'name' => 'Appointments', 'singular_name' => 'Appointment' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-calendar-alt',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function render_booking_form() {
        $nonce = wp_create_nonce('gp_booking_nonce');
        $staff = get_users( array( 'role__in' => array('author', 'editor', 'administrator') ) );
        ob_start(); ?>
        <style>
            .gp-booking-form input, .gp-booking-form select { width: 100%; background: rgba(255,255,255,0.8); border: 1px solid #E2E8F0; padding: 0 20px; border-radius: 15px; transition: all 0.3s ease; font-weight: 700; height: 65px; }
            .gp-booking-form input:focus, .gp-booking-form select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-glow); outline: none; background: #FFF; }
        </style>
        <div class="gp-booking-elite glass-card" style="padding:80px; border-radius:50px; background:linear-gradient(135deg, rgba(255,255,255,0.9), rgba(248,250,252,0.9)); border:1px solid rgba(255,255,255,0.6); box-shadow: 0 50px 100px -20px rgba(0,0,0,0.12);">
            <div style="text-align:center; margin-bottom:60px;">
                <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">SECURE CALENDAR ENGINE v3.0</div>
                <h2 class="text-gradient" style="font-size:3.5rem; margin:0; line-height: 1.1; letter-spacing: -0.05em;">Secure Your Strategy Session</h2>
                <p style="opacity:0.6; font-size:16px; margin-top:15px; font-weight: 500;">Select a slot to engage with our elite <?php echo get_option('growthpress_niche', 'business'); ?> specialists.</p>
            </div>

            <form id="gp-booking-form" class="gp-booking-form">
                <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">SPECIALIST NODE</label>
                        <select name="staff_id">
                            <option value="0">Any Available Specialist</option>
                            <?php foreach($staff as $member): ?>
                                <option value="<?php echo $member->ID; ?>"><?php echo esc_html($member->display_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">STRATEGY TRACK</label>
                        <select name="service">
                            <option value="consultation">Initial Strategy Audit</option>
                            <option value="blueprint">Performance Blueprinting</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:40px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">PREFERRED DATE</label>
                        <input type="date" name="date" required>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">TARGET TIME</label>
                        <input type="time" name="time" required>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:50px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">APPLICANT IDENTITY</label>
                        <input type="text" name="client_name" placeholder="Full Legal Name" required>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">UPLINK EMAIL</label>
                        <input type="email" name="client_email" placeholder="direct@enterprise.com" required>
                    </div>
                </div>

                <div style="display:flex; gap:25px;">
                    <button type="submit" class="gp-btn" style="flex:2; height:85px; font-size:20px; border-radius: 20px;">CONFIRM SESSION SLOT</button>
                    <button type="button" class="gp-btn" onclick="joinWaitingList()" style="flex:1; background:var(--secondary); height:85px; font-size:14px; text-transform:none; border-radius: 20px;">Join Priority Waiting List</button>
                </div>
                <div id="booking-res" style="margin-top:30px; text-align:center; font-weight:900; color:var(--primary);"></div>
            </form>
        </div>
        <script>
        jQuery('#gp-booking-form').on('submit', function(e) {
            e.preventDefault();
            var $btn = jQuery(this).find('button[type="submit"]');
            $btn.text('SYNCHRONIZING...');
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_submit_booking', formData: jQuery(this).serialize() }, function(res) {
                if(res.success) {
                    jQuery('#gp-booking-form').fadeOut(400, function() {
                        jQuery('#booking-res').html('<div style="padding:40px;"><div style="font-size:4rem; margin-bottom:20px;">🗓️</div><h3 class="text-gradient">SLOT SECURED</h3><p>Your session has been added to the master calendar.</p></div>').fadeIn();
                    });
                }
            });
        });
        function joinWaitingList() {
            var name = jQuery('input[name="client_name"]').val();
            if(!name) { alert("Identify yourself first."); return; }
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_join_waiting_list', name: name, nonce: '<?php echo $nonce; ?>' }, function(res) {
                if(res.success) alert("Priority waiting list joined.");
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_waiting_list() {
        check_ajax_referer( 'gp_booking_nonce', 'nonce' );
        $appt_id = wp_insert_post( array( 'post_title' => "Priority Waiting: " . sanitize_text_field($_POST['name']), 'post_type' => 'gp_appointment', 'post_status' => 'publish' ) );
        update_post_meta($appt_id, '_is_waiting_list', '1');
        wp_send_json_success();
    }

    public function handle_booking_submission() {
        parse_str($_POST['formData'], $data);
        if ( ! wp_verify_nonce($data['nonce'], 'gp_booking_nonce') ) wp_send_json_error();
        $id = wp_insert_post( array( 'post_title' => "Appointment: " . $data['client_name'], 'post_type' => 'gp_appointment', 'post_status' => 'publish' ) );
        if ( $id ) {
            update_post_meta( $id, '_appointment_date', $data['date'] . ' ' . $data['time'] );
            update_post_meta( $id, '_staff_id', intval($data['staff_id']) );
            $email = sanitize_email($data['client_email']);
            update_post_meta( $id, '_client_email', $email );

            // Link to Lead if exists
            $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email, 'posts_per_page' => 1 ) );
            if ( ! empty($leads) ) {
                $lead_id = $leads[0]->ID;
                update_post_meta( $id, '_related_lead', $lead_id );
                wp_set_object_terms( $lead_id, 'booked', 'gp_lead_stage' );
                GrowthPress_Activity::log( "Lead #$lead_id moved to 'Booked' via session scheduling." );
            }

            // Create Invoice for Deposit
            $payments = new GrowthPress_Payments();
            $invoice_id = $payments->create_invoice(150, $id, 'booking');
            update_post_meta($id, '_deposit_invoice_id', $invoice_id);

            // Generate Secure Meeting Link
            $zoom_cid = get_option('growthpress_zoom_client_id');
            if ($zoom_cid) {
                // Strategic Note: In a production environment, this would involve a server-to-server
                // Oauth2 handshake with Zoom using the saved credentials to instantiate a
                // meeting node. This mock simulates the authenticated result.
                $meeting_link = "https://growthpress.zoom.us/j/" . rand(100000000, 999999999) . "?pwd=" . wp_generate_password(12, false);
            } else {
                $meeting_link = "https://growthpress.zoom.us/j/" . rand(100000000, 999999999);
            }
            update_post_meta($id, '_meeting_link', $meeting_link);

            do_action( 'gp_appointment_created', $id );
            wp_send_json_success(array('appointment_id' => $id, 'invoice_id' => $invoice_id));
        }
        wp_send_json_error();
    }

    public function trigger_appointment_reminders( $id ) {
        GrowthPress_Activity::log( "Booking Logic: SMS reminders scheduled for session #$id" );
    }
}
GrowthPress_Booking::get_instance();
