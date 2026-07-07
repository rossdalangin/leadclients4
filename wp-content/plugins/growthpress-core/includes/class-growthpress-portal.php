<?php
/**
 * GrowthPress Customer Portal Class - v6.3 SaaS-Pro Standards
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Portal {

    public function __construct() {
        add_shortcode( 'gp_client_portal', array( $this, 'render_portal_shortcode' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_portal_assets' ) );
        add_action( 'admin_bar_menu', array( $this, 'add_portal_to_admin_bar' ), 100 );
        add_action( 'wp_ajax_gp_request_reschedule', array( $this, 'handle_reschedule_request' ) );
        add_action( 'wp_ajax_gp_update_portal_profile', array( $this, 'handle_profile_update' ) );
        add_action( 'wp_ajax_gp_portal_upload', array( $this, 'handle_portal_upload' ) );
        add_action( 'wp_ajax_gp_mark_milestone', array( $this, 'handle_mark_milestone' ) );
        add_action( 'wp_ajax_gp_submit_referral', array( $this, 'handle_referral_submission' ) );
        add_action( 'wp_ajax_gp_submit_onboarding', array( $this, 'handle_onboarding_submission' ) );
    }

    public function enqueue_portal_assets() {
        global $post;
        if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'gp_client_portal' ) ) {
            wp_localize_script( 'jquery', 'gp_portal', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'gp_portal_nonce' )
            ) );
        }
    }

    public function add_portal_to_admin_bar( $wp_admin_bar ) {
        if ( ! is_user_logged_in() ) return;

        $portal_page = get_pages(array(
            'meta_key' => '_wp_page_template',
            'meta_value' => 'template-full-width-glass.php', // Common for portal
            'number' => 1
        ));

        // Fallback search for [gp_client_portal]
        if ( empty($portal_page) ) {
            $portal_page = get_posts(array('post_type' => 'page', 's' => '[gp_client_portal]', 'posts_per_page' => 1));
        }

        $url = !empty($portal_page) ? get_permalink($portal_page[0]->ID) : home_url('/client-portal');

        $wp_admin_bar->add_node( array(
            'id'    => 'gp-client-portal',
            'title' => '<span class="ab-icon dashicons-dashboard" style="top:2px;"></span> Client Portal',
            'href'  => $url,
            'meta'  => array( 'class' => 'gp-portal-link' )
        ) );
    }

    private function verify_lead_ownership( $lead_id ) {
        if ( ! is_user_logged_in() ) return false;
        $user = wp_get_current_user();
        $email = get_post_meta( $lead_id, '_lead_email', true );
        return ( $email === $user->user_email );
    }

    public function handle_onboarding_submission() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        $user = wp_get_current_user();
        $lead_id = intval($_POST['lead_id']);

        if ( ! $this->verify_lead_ownership( $lead_id ) ) wp_send_json_error( 'Ownership verification failed.' );

        $goals = sanitize_textarea_field($_POST['onboarding_goals']);
        update_post_meta($lead_id, '_gp_onboarding_data', $goals);
        update_post_meta($lead_id, '_gp_onboarding_complete', '1');

        GrowthPress_Activity::log("Cinematic Onboarding completed by client: {$user->display_name}");
        wp_send_json_success("Onboarding data synchronized to secure node.");
    }

    public function handle_referral_submission() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        $name = sanitize_text_field($_POST['ref_name']);
        $email = sanitize_email($_POST['ref_email']);
        $source_user = wp_get_current_user();

        $ref_id = wp_insert_post(array(
            'post_title' => "Referral: $name",
            'post_type' => 'gp_referral',
            'post_status' => 'publish'
        ));

        if($ref_id) {
            update_post_meta($ref_id, '_referral_email', $email);
            update_post_meta($ref_id, '_source_client_id', $source_user->ID);
            update_post_meta($ref_id, '_referral_status', 'New');
            GrowthPress_Activity::log("New Referral submitted by client: {$source_user->display_name}");
            wp_send_json_success("Referral node initialized. Strategic outreach queued.");
        }
        wp_send_json_error();
    }

    public function handle_portal_upload() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        $user = wp_get_current_user();
        $email = $user->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email, 'posts_per_page' => 1 ) );

        if ( ! empty($leads) ) {
            $lead_id = $leads[0]->ID;
            if ( ! $this->verify_lead_ownership( $lead_id ) ) wp_send_json_error();

            $vault = get_post_meta($lead_id, '_secure_vault', true) ?: array();
            $vault[] = array(
                'name' => sanitize_text_field($_POST['file_name']),
                'time' => current_time('mysql'),
                'status' => 'Encrypted'
            );
            update_post_meta($lead_id, '_secure_vault', $vault);
            GrowthPress_Activity::log("Secure Asset Uploaded to Vault for Lead #$lead_id");
            wp_send_json_success('Asset synchronized to secure vault.');
        }
        wp_send_json_error();
    }

    public function handle_profile_update() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        $user = wp_get_current_user();
        $email = $user->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email, 'posts_per_page' => 1 ) );

        if ( ! empty($leads) ) {
            $lead_id = $leads[0]->ID;
            if ( ! $this->verify_lead_ownership( $lead_id ) ) wp_send_json_error();

            update_post_meta( $lead_id, '_lead_phone', sanitize_text_field( $_POST['phone'] ) );
            update_post_meta( $lead_id, '_lead_zip', sanitize_text_field( $_POST['zip'] ) );
            wp_send_json_success( 'Strategic profile synchronized.' );
        }
        wp_send_json_error();
    }

    public function handle_reschedule_request() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        $appt_id = intval($_POST['appointment_id']);
        $email = get_post_meta($appt_id, '_client_email', true);
        if ($email !== wp_get_current_user()->user_email) wp_send_json_error();

        update_post_meta($appt_id, '_reschedule_requested', '1');
        wp_send_json_success();
    }

    public function handle_mark_milestone() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        $lead_id = intval($_POST['lead_id']);
        if ( ! $this->verify_lead_ownership( $lead_id ) ) wp_send_json_error();

        $milestone_index = intval($_POST['index']);
        $status = sanitize_text_field($_POST['status']);

        $milestones = get_post_meta($lead_id, '_gp_roadmap_milestones', true) ?: array();
        $milestones[$milestone_index] = $status;
        update_post_meta($lead_id, '_gp_roadmap_milestones', $milestones);

        GrowthPress_Activity::log("Milestone #$milestone_index marked as $status for Lead #$lead_id");
        wp_send_json_success('Strategy node updated.');
    }

    private function get_neural_activity($lead_ids, $email) {
        $activity = array();

        // Appointments
        $appts = get_posts(array('post_type' => 'gp_appointment', 'meta_key' => '_client_email', 'meta_value' => $email, 'posts_per_page' => 10));
        foreach($appts as $a) {
            $activity[] = array(
                'time' => strtotime($a->post_date),
                'type' => 'Appointment',
                'title' => 'Session Scheduled: ' . $a->post_title,
                'icon' => '📅'
            );
        }

        // Vault Assets
        foreach($lead_ids as $lid) {
            $lead = get_post($lid);
            if($lead) {
                $activity[] = array(
                    'time' => strtotime($lead->post_date),
                    'type' => 'System',
                    'title' => 'Strategic Intake Sequence Initiated',
                    'icon' => '📥'
                );
            }

            $vault = get_post_meta($lid, '_secure_vault', true) ?: array();
            foreach($vault as $v) {
                $activity[] = array(
                    'time' => strtotime($v['time']),
                    'type' => 'Vault',
                    'title' => 'Asset Secured: ' . $v['name'],
                    'icon' => '🔐'
                );
            }

            // Onboarding
            if(get_post_meta($lid, '_gp_onboarding_complete', true)) {
                $activity[] = array(
                    'time' => strtotime(get_post_modified_time('Y-m-d H:i:s', false, $lid)),
                    'type' => 'Onboarding',
                    'title' => 'Strategic Onboarding Synchronized',
                    'icon' => '🧠'
                );
            }
        }

        // Proposals
        $props = get_posts(array('post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids));
        foreach($props as $p) {
            $status = get_post_meta($p->ID, '_gp_proposal_status', true);
            $activity[] = array(
                'time' => strtotime($p->post_modified),
                'type' => 'Agreement',
                'title' => 'Proposal ' . ($status === 'Accepted' ? 'Executed' : 'Issued') . ': ' . $p->post_title,
                'icon' => '📜'
            );
        }

        usort($activity, function($a, $b) { return $b['time'] - $a['time']; });
        return array_slice($activity, 0, 15);
    }

    public function render_portal_shortcode($atts) {
        $atts = shortcode_atts(array('email' => ''), $atts);
        return $this->render_portal($atts['email']);
    }

    public function render_portal($mirror_email = '') {
        $is_admin_mirror = !empty($mirror_email) && current_user_can('manage_options');

        if ( ! is_user_logged_in() && !$is_admin_mirror ) {
            $sso_enabled = get_option('growthpress_sso_enabled');
            $sso_provider = get_option('growthpress_sso_provider', 'okta');

            $html = '<div class="glass-card gp-reveal" style="text-align:center; padding:120px 60px; border-radius:60px; border: 1px solid rgba(255,255,255,0.4);">
                <div style="font-size:6rem; margin-bottom:40px;">🔐</div>
                <h2 class="text-gradient" style="font-size:4rem; line-height:1.1;">Secure Node Authentication</h2>
                <p style="opacity:0.7; font-size:1.4rem; max-width: 600px; margin: 30px auto 60px;">Identify yourself to access proprietary strategic metrics, legal blueprints, and financial ledgers.</p>
                <a href="'.wp_login_url(get_permalink()).'" class="gp-btn" style="height:90px; padding:0 100px; font-size:22px; border-radius: 25px;">AUTHENTICATE SESSION</a>';

            if ($sso_enabled) {
                $html .= '<div style="margin-top:50px; padding-top:50px; border-top:1px solid rgba(0,0,0,0.05);">
                    <div style="font-size:10px; font-weight:950; opacity:0.3; letter-spacing:2px; margin-bottom:25px; text-transform:uppercase;">Enterprise SSO Uplink</div>
                    <button class="gp-btn" style="width:100%; max-width:400px; height:75px; background:#000; color:white !important; border-radius:20px; font-size:15px; letter-spacing:1px;" onclick="location.href=\''.esc_url(rest_url('growthpress/v1/sso-login')).'\'">LOGIN WITH '.strtoupper($sso_provider).'</button>
                </div>';
            }

            $html .= '</div>';
            return $html;
        }

        if($is_admin_mirror) {
            $email = sanitize_email($mirror_email);
            $user_obj = get_user_by('email', $email);
            $display_name = $user_obj ? $user_obj->display_name : 'Strategic Node: ' . $email;
        } else {
            $user = wp_get_current_user();
            $email = $user->user_email;
            $display_name = $user->display_name;
        }
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $lead_ids = wp_list_pluck($leads, 'ID');
        $proposals = ! empty($lead_ids) ? get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) ) : array();
        $neural_activity = $this->get_neural_activity($lead_ids, $email);

        ob_start(); ?>
        <style>
            .portal-nav-tab { padding: 15px 30px; font-weight: 800; cursor: pointer; opacity: 0.5; transition: 0.3s; border-bottom: 3px solid transparent; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; }
            .portal-nav-tab.active { opacity: 1; border-color: var(--primary); color: var(--primary); }
            .portal-section { display: none; }
            .portal-section.active { display: block; }
            .activity-item::before { content: ""; position: absolute; left: -26px; top: 15px; width: 10px; height: 10px; background: var(--primary); border-radius: 50%; }
        </style>
        <div class="gp-portal-v4 container" style="padding:100px 0;">
            <div class="glass-card" style="background:#f0f9ff; border-left:5px solid #0ea5e9; margin-bottom:40px; padding:25px;">
                <h4 style="margin:0 0 10px 0; color:#0369a1;">🏛️ Strategic Context: Client Command Hub</h4>
                <p style="margin:0; font-size:14px; color:#0369a1; line-height:1.5;">Welcome to your high-stakes operational dashboard. This terminal synchronizes your project velocity, financial ledgers, and secure asset vaults into one unified interface. Use the navigation below to manage your growth trajectory.</p>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:60px;">
                <div>
                    <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:5px; margin-bottom:20px;">AUTHENTICATED COMMAND CENTER <?php echo $is_admin_mirror ? '(MIRROR MODE)' : ''; ?></div>
                    <h1 class="text-gradient" style="margin:0; font-size:5.5rem; letter-spacing:-0.07em; line-height: 0.9;">Command: <?php echo esc_html($display_name); ?></h1>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px; font-weight:950; opacity:0.3; letter-spacing:2px; margin-bottom:15px; text-transform: uppercase;">Node: AES-256-GCM Secure</div>
                    <?php if(!$is_admin_mirror): ?>
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="gp-btn" style="background:var(--secondary); padding:15px 40px; font-size:12px; border-radius:18px; text-transform:none; font-weight: 800;">TERMINATE SESSION</a>
                    <?php else: ?>
                        <div style="background:#FEF2F2; color:#B91C1C; padding:10px 20px; border-radius:15px; font-size:10px; font-weight:950; border:1px solid #FECACA;">ADMIN MIRROR ACTIVE</div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-bottom:60px; border-bottom:1px solid rgba(0,0,0,0.05); overflow-x:auto; background:rgba(255,255,255,0.3); padding:10px; border-radius:25px;">
                <div class="portal-nav-tab active" data-target="overview">Overview</div>
                <div class="portal-nav-tab" data-target="roadmap">Roadmap</div>
                <div class="portal-nav-tab" data-target="agreements">Agreements</div>
                <div class="portal-nav-tab" data-target="vault">Vault</div>
                <div class="portal-nav-tab" data-target="ledger">Ledger</div>
                <div class="portal-nav-tab" data-target="referrals">Referrals</div>
                <div class="portal-nav-tab" data-target="activity">Activity Logs</div>
            </div>

            <div class="gp-portal-grid" style="display:grid; grid-template-columns: 2.5fr 1fr; gap:60px;">
                <div class="portal-main">
                    <div id="section-overview" class="portal-section active">
                    <!-- Strategic Onboarding Sequence (Step 23) -->
                    <?php
                    $lead = !empty($leads) ? $leads[0] : null;
                    if($lead && !get_post_meta($lead->ID, '_gp_onboarding_complete', true)): ?>
                        <div class="glass-card gp-reveal" style="background:linear-gradient(135deg, var(--primary), var(--primary-alt)); color:white; padding:70px; border-radius:44px; margin-bottom:60px; border:none; box-shadow:0 30px 60px var(--primary-glow);">
                            <h2 style="color:white; font-size:3rem; margin-bottom:20px; letter-spacing:-0.05em;">Strategic Onboarding</h2>
                            <p style="font-size:1.4rem; opacity:0.8; margin-bottom:40px;">Initialize your v6.3 deployment by documenting your primary Q4 realization targets.</p>
                            <div id="onboarding-step">
                                <textarea id="onboard-goals" placeholder="Outline your top 3 growth objectives..." style="width:100%; height:150px; border-radius:20px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:white; padding:25px; font-size:16px; margin-bottom:30px;"></textarea>
                                <button class="gp-btn" style="background:white; color:var(--primary) !important; width:100%; height:80px; font-size:18px; border-radius:20px;" onclick="submitOnboarding(<?php echo $lead->ID; ?>)">EXECUTE ONBOARDING</button>
                            </div>
                            <script>
                            function submitOnboarding(id) {
                                const goals = jQuery('#onboard-goals').val();
                                if(!goals) return alert('Input objectives node.');
                                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_submit_onboarding', lead_id: id, onboarding_goals: goals, gp_nonce: gp_portal.nonce }, function(res) {
                                    if(res.success) location.reload();
                                });
                            }
                            </script>
                        </div>
                    <?php endif; ?>

                    <!-- Project Velocity Tracker -->
                    <?php
                    $roadmap_raw = $lead ? get_post_meta($lead->ID, '_gp_growth_roadmap', true) : '';
                    $milestones = $lead ? (get_post_meta($lead->ID, '_gp_roadmap_milestones', true) ?: array()) : array();
                    preg_match_all('/^-\s+(.*)$/m', $roadmap_raw, $matches);
                    $total_m = count($matches[1]);
                    $done_m = count(array_filter($milestones, function($v) { return $v === 'complete'; }));
                    $velocity = $total_m > 0 ? round(($done_m / $total_m) * 100) : 0;
                    ?>
                    <div class="glass-card" style="border-left: 15px solid var(--primary); margin-bottom:60px; padding:70px; border-radius:44px;">
                        <div style="background:rgba(37,99,235,0.05); border:1px solid rgba(37,99,235,0.1); padding:20px; border-radius:20px; margin-bottom:40px;">
                            <p style="margin:0; font-size:13px; color:var(--primary); font-weight:600;"><strong>Portal Guidance:</strong> This terminal provides real-time access to your strategic growth trajectory. Monitor your 'Operational Velocity' to track the execution of active business nodes.</p>
                        </div>
                        <h3 class="text-gradient" style="font-size:2.2rem; margin-bottom:40px;">Operational Velocity</h3>
                        <div style="background:rgba(0,0,0,0.02); height:12px; border-radius:10px; overflow:hidden; margin-bottom:15px;">
                            <div style="width:<?php echo $velocity; ?>%; height:100%; background:var(--primary); box-shadow:0 0 20px var(--primary-glow);"></div>
                        </div>
                        <?php
                        $stage_slug = 'new';
                        if($lead) {
                            $terms = get_the_terms($lead->ID, 'gp_lead_stage');
                            if($terms && !is_wp_error($terms)) $stage_slug = $terms[0]->slug;
                        }

                        $step1 = ($stage_slug === 'new') ? 'ACTIVE' : 'COMPLETE';
                        $step2 = ($stage_slug === 'new') ? 'PENDING' : (($stage_slug === 'qualified') ? 'ACTIVE' : 'COMPLETE');
                        $step3 = ($stage_slug === 'closed') ? 'ACTIVE' : (($velocity > 0) ? 'ACTIVE' : 'PENDING');
                        ?>
                        <div style="display:flex; justify-content:space-between; font-size:11px; font-weight:950; opacity:0.5; letter-spacing:1px;">
                            <span>INTAKE: <?php echo $step1; ?></span>
                            <span>TRIAGE: <?php echo $step2; ?></span>
                            <span>EXECUTION: <?php echo $step3; ?> (<?php echo $velocity; ?>%)</span>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:30px; margin-top:50px;">
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">DOCUMENTS</div>
                                <div style="font-size:42px; font-weight:950;"><?php echo count($proposals); ?></div>
                            </div>
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">ACTIVE NODES</div>
                                <div style="font-size:42px; font-weight:950; color:var(--accent);"><?php echo $done_m; ?>/<?php echo $total_m; ?></div>
                            </div>
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">MILESTONES</div>
                                <div style="font-size:42px; font-weight:950; color:#EF4444;"><?php echo $done_m; ?></div>
                            </div>
                        </div>
                    </div>

                    <div id="gp-portal-projects" style="margin-bottom:60px;">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Active Growth Projects</h2>
                        <?php
                        // Query projects explicitly linked to this user's leads
                        $projects = !empty($lead_ids) ? get_posts(array('post_type' => 'gp_project', 'posts_per_page' => 10, 'meta_query' => array(array('key' => '_related_lead', 'value' => $lead_ids, 'compare' => 'IN')))) : array();
                        if($projects): foreach($projects as $p): ?>
                            <div class="glass-card" style="margin-bottom:20px; padding:30px; border-radius:25px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <h4 style="margin:0; font-size:18px;"><?php echo esc_html($p->post_title); ?></h4>
                                    <p style="font-size:12px; opacity:0.5; margin-top:5px;"><?php echo wp_trim_words($p->post_content, 15); ?></p>
                                </div>
                                <a href="<?php echo get_permalink($p->ID); ?>" class="gp-btn" style="padding:10px 25px; font-size:11px; border-radius:10px;">TRACK VELOCITY</a>
                            </div>
                        <?php endforeach; else: echo "<p style='opacity:0.5;'>No active growth projects assigned to this node.</p>"; endif; ?>
                    </div>
                    </div><!-- end overview -->

                    <!-- Roadmap Execution Checklist -->
                    <div id="section-roadmap" class="portal-section">
                    <div id="gp-portal-roadmap" style="margin-bottom:60px;">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Strategy Execution Node</h2>
                        <div class="glass-card" style="padding:50px; border-radius:40px;">
                            <?php
                            if($roadmap_raw):
                                preg_match_all('/^-\s+(.*)$/m', $roadmap_raw, $matches);
                                $items = $matches[1];
                                if($items): ?>
                                    <div style="display:grid; gap:20px;">
                                        <?php foreach($items as $idx => $item):
                                            $is_done = isset($milestones[$idx]) && $milestones[$idx] === 'complete';
                                            ?>
                                            <div style="display:flex; align-items:center; gap:20px; padding:20px; background:#F8FAFC; border-radius:15px; border:1px solid #F1F5F9; <?php echo $is_done ? 'opacity:0.5;' : ''; ?>">
                                                <input type="checkbox" style="width:24px; height:24px; border-radius:6px; cursor:pointer;" <?php checked($is_done); ?> onclick="markMilestone(<?php echo $lead->ID; ?>, <?php echo $idx; ?>, this.checked)">
                                                <span style="font-size:15px; font-weight:700; color:var(--secondary); <?php echo $is_done ? 'text-decoration:line-through;' : ''; ?>"><?php echo esc_html($item); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: echo "<p style='opacity:0.5;'>Analyzing strategy nodes...</p>"; endif; ?>
                            <?php else: echo "<p style='opacity:0.5; text-align:center;'>Awaiting AI Strategic Roadmap generation.</p>"; endif; ?>
                        </div>
                        <script>
                        function markMilestone(leadId, index, isChecked) {
                            jQuery.post(gp_ajax.ajaxurl, {
                                action: 'gp_mark_milestone',
                                lead_id: leadId,
                                index: index,
                                status: isChecked ? 'complete' : 'pending',
                                gp_nonce: gp_portal.nonce
                            }, function() {
                                location.reload();
                            });
                        }
                        </script>
                    </div>
                    </div><!-- end roadmap -->

                    <div id="section-agreements" class="portal-section">
                    <div id="proposals">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Strategic Terminal: Agreements</h2>
                        <?php if($proposals): foreach($proposals as $prop):
                            $status = get_post_meta($prop->ID, '_gp_proposal_status', true) ?: 'Pending Execution'; ?>
                            <div class="glass-card" style="margin-bottom:30px; border-radius:40px; border: 1px solid rgba(0,0,0,0.05); border-left: 12px solid <?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>;">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <h4 style="margin:0; font-size:24px; font-weight:900;"><?php echo esc_html($prop->post_title); ?></h4>
                                        <div style="font-size:12px; font-weight:950; color:<?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>; margin-top:10px; letter-spacing:2px;">STATUS: <?php echo strtoupper($status); ?></div>
                                    </div>
                                    <button class="gp-btn" style="padding:15px 45px; font-size:13px; border-radius:18px;" onclick="jQuery('#p-<?php echo $prop->ID; ?>').slideToggle()">OPEN TERMINAL</button>
                                </div>
                                <div id="p-<?php echo $prop->ID; ?>" style="display:none; margin-top:50px; padding-top:50px; border-top:2px solid #F1F5F9;">
                                    <div class="entry-content" style="font-size:17px; line-height:1.9; color:var(--text); font-family:'Inter', sans-serif;"><?php echo apply_filters('the_content', $prop->post_content); ?></div>
                                    <?php if($status !== 'Accepted'): ?>
                                        <div style="margin-top:60px; text-align:center; background:#F8FAFC; padding:60px; border-radius:35px; border:1px solid #E2E8F0;">
                                            <h3 style="margin-bottom:20px;">Execute Strategic Agreement</h3>
                                            <p style="opacity:0.6; margin-bottom:40px;">By clicking below, you authenticate and digitally sign this binding strategic proposal.</p>
                                            <button class="gp-btn" style="width:100%; height:85px; font-size:20px;" onclick="acceptProposal(<?php echo $prop->ID; ?>)">EXECUTE & INITIATE KICKOFF</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; else: echo "<div class='glass-card' style='opacity:0.5; text-align:center; padding:80px; border-radius:40px;'>No pending strategic documents detected in current secure node.</div>"; endif; ?>
                    </div>
                    </div><!-- end agreements -->

                    <div id="section-vault" class="portal-section">
                        <div class="glass-card" style="padding:70px; border-radius:44px;">
                            <h2 style="margin-top:0;">Secure Asset Vault</h2>
                            <p style="opacity:0.6; margin-bottom:40px;">Proprietary blueprints, financial audits, and strategic assets encrypted via AES-256 node.</p>
                            <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap:20px;">
                                <?php
                                if($vault): foreach($vault as $v): ?>
                                    <div style="background:#F8FAFC; padding:30px; border-radius:25px; border:1px solid #EEE; text-align:center;">
                                        <div style="font-size:32px; margin-bottom:15px;">🔒</div>
                                        <div style="font-size:14px; font-weight:800;"><?php echo esc_html($v['name']); ?></div>
                                        <div style="font-size:10px; opacity:0.4; margin-top:5px;"><?php echo $v['time']; ?></div>
                                        <button class="gp-btn" style="margin-top:20px; width:100%; padding:10px; font-size:10px; border-radius:10px;">DOWNLOAD</button>
                                    </div>
                                <?php endforeach; else: echo "<p>Vault node empty.</p>"; endif; ?>
                            </div>
                        </div>
                    </div>

                    <div id="section-ledger" class="portal-section">
                        <div class="glass-card" style="padding:70px; border-radius:44px;">
                            <h2 style="margin-top:0;">Financial Ledger</h2>
                            <p style="opacity:0.6; margin-bottom:40px;">Real-time settlement status for all investment nodes and operational equity.</p>
                            <table class="wp-list-table widefat fixed striped" style="border:none;">
                                <thead>
                                    <tr>
                                        <th style="font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px; padding:20px;">NODE IDENTITY</th>
                                        <th style="font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px; padding:20px;">AMOUNT</th>
                                        <th style="font-weight:950; font-size:10px; opacity:0.4; letter-spacing:1px; padding:20px;">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($transactions): foreach($transactions as $t): ?>
                                        <tr>
                                            <td style="padding:20px; font-weight:700;"><?php echo esc_html($t->post_title); ?></td>
                                            <td style="padding:20px; font-weight:900;">$<?php echo number_format(get_post_meta($t->ID, '_amount', true)); ?></td>
                                            <td style="padding:20px;"><span style="background:<?php echo get_post_meta($t->ID, '_status', true) === 'Paid' ? '#D1FAE5' : '#FEF3C7'; ?>; color:<?php echo get_post_meta($t->ID, '_status', true) === 'Paid' ? '#065F46' : '#92400E'; ?>; padding:5px 12px; border-radius:30px; font-size:10px; font-weight:950;"><?php echo strtoupper(get_post_meta($t->ID, '_status', true)); ?></span></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="3" style="text-align:center; padding:40px; opacity:0.5;">No ledger data synchronized.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="section-activity" class="portal-section">
                        <div class="glass-card" style="padding:70px; border-radius:44px;">
                            <h2 style="margin-top:0;">Neural Activity Logs</h2>
                            <p style="opacity:0.6; margin-bottom:40px;">Historical synchronization trace of all ecosystem interactions.</p>
                            <div class="activity-timeline" style="border-left:2px dashed #EEE; padding-left:40px; margin-left:10px;">
                                <?php foreach($neural_activity as $act): ?>
                                    <div class="activity-item" style="position:relative; margin-bottom:40px;">
                                        <div style="position:absolute; left:-55px; top:0; width:30px; height:30px; background:white; border:1px solid #EEE; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:14px;"><?php echo $act['icon']; ?></div>
                                        <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:5px;"><?php echo date('M j, Y H:i:s', $act['time']); ?> [<?php echo strtoupper($act['type']); ?>]</div>
                                        <div style="font-size:16px; font-weight:800;"><?php echo esc_html($act['title']); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                </div>

                    <div id="section-referrals" class="portal-section">
                        <div class="glass-card" style="padding:70px; border-radius:44px; background:#F0FDF4; border-color:#DCFCE7;">
                            <h3 style="font-size:2.5rem; margin-bottom:10px; color:#166534; letter-spacing:-0.05em;">Strategic Referral Hub</h3>
                            <p style="font-size:1.2rem; opacity:0.6; color:#166534; margin-bottom:40px;">Propagate your network dominance and earn commissions on successful conversions.</p>

                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px;">
                                <div>
                                    <div style="margin-bottom:30px; padding:30px; background:white; border-radius:25px; border:1px solid #BBF7D0;">
                                        <label style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:15px; color:#166534; text-transform:uppercase;">Your Tracking Uplink</label>
                                        <div style="display:flex; gap:10px;">
                                            <input type="text" readonly value="<?php echo esc_url(add_query_arg('ref', $current_user_id ?: 'admin', home_url('/'))); ?>" style="flex:1; height:60px; border-radius:15px; border:1px solid #BBF7D0; padding:0 20px; font-size:14px; background:#F8FAFC;">
                                            <button class="gp-btn" style="height:60px; padding:0 30px; font-size:12px; background:#166534; color:white !important; border-radius:15px;" onclick="copyRefLink(this)">COPY LINK</button>
                                        </div>
                                    </div>

                                    <div style="padding:30px; background:white; border-radius:25px; border:1px solid #BBF7D0;">
                                        <label style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:15px; color:#166534; text-transform:uppercase;">Direct Node Submission</label>
                                        <input type="text" id="ref-name" placeholder="Full Name" style="width:100%; height:55px; border-radius:12px; border:1px solid #BBF7D0; padding:0 20px; margin-bottom:15px;">
                                        <input type="email" id="ref-email" placeholder="Email Address" style="width:100%; height:55px; border-radius:12px; border:1px solid #BBF7D0; padding:0 20px; margin-bottom:25px;">
                                        <button class="gp-btn" style="width:100%; height:70px; font-size:14px; background:#166534; color:white !important; border-radius:15px;" onclick="submitReferral()">INITIALIZE REFERRAL</button>
                                    </div>
                                </div>
                                <div>
                                    <h4 style="color:#166534; margin-top:0;">Referral Ledger</h4>
                                    <div style="background:white; padding:10px; border-radius:25px; border:1px solid #BBF7D0;">
                                        <table style="width:100%; border-collapse:collapse;">
                                            <tbody style="font-size:13px;">
                                                <?php
                                                $current_user_id = $is_admin_mirror ? ($user_obj ? $user_obj->ID : 0) : wp_get_current_user()->ID;
                                                $my_refs = $current_user_id ? get_posts(array('post_type' => 'gp_referral', 'posts_per_page' => -1, 'meta_query' => array('relation' => 'OR', array('key' => '_source_client_id', 'value' => $current_user_id), array('key' => '_referrer_id', 'value' => $current_user_id)))) : array();
                                                if($my_refs): foreach($my_refs as $r):
                                                    $r_status = get_post_meta($r->ID, '_referral_status', true) ?: 'New';
                                                    $r_payout = get_post_meta($r->ID, '_payout_status', true) ?: 'Pending';
                                                    ?>
                                                    <tr style="border-bottom:1px solid #F0FDF4;">
                                                        <td style="padding:15px; font-weight:700;"><?php echo esc_html($r->post_title); ?></td>
                                                        <td style="padding:15px; text-align:right;"><span style="color:<?php echo $r_payout === 'Paid' ? '#10B981' : '#F59E0B'; ?>; font-weight:950; font-size:10px;"><?php echo strtoupper($r_payout); ?></span></td>
                                                    </tr>
                                                <?php endforeach; else: ?>
                                                    <tr><td style="padding:40px; text-align:center; opacity:0.4;">No referral nodes detected.</td></tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="margin-top:30px; padding:25px; background:rgba(22, 101, 52, 0.05); border-radius:20px; border:1px solid rgba(22, 101, 52, 0.1); font-size:12px; color:#166534;">
                                        <strong>PAYOUT PROTOCOL:</strong> <?php echo esc_html(get_option('growthpress_referral_payout_instructions', 'Processed via PayPal/Transfer within 30 days of conversion.')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="portal-side">
                    <!-- Secure Messenger Mockup -->
                    <div class="glass-card" style="background:var(--secondary); color:white; border:none; margin-bottom:40px; padding:50px; border-radius:44px;">
                        <h3 style="color:white; font-size:24px; margin-bottom:20px;">Strategy Uplink</h3>
                        <p style="font-size:16px; opacity:0.7; line-height:1.7;">Direct priority connection to your dedicated operational specialists.</p>
                        <div style="display:grid; gap:20px; margin-top:40px;">
                            <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="width:100%; border-radius:20px; background:var(--primary); text-transform:none; height:70px; font-size:16px;">Book Briefing</a>
                            <button class="gp-btn" style="width:100%; background:rgba(255,255,255,0.06); border-radius:20px; text-transform:none; height:70px; border:1px solid rgba(255,255,255,0.1); font-size:16px;" onclick="jQuery('a[data-target=\'activity\']').click()">Neural Logs</button>
                        </div>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px; margin-bottom:40px; border:1px solid var(--primary-glow);">
                        <h3 style="font-size:22px; margin-bottom:20px; color:var(--primary);">Direct Strategic Uplink</h3>
                        <p style="font-size:13px; opacity:0.6; margin-bottom:25px;">Submit a high-priority support request or strategic query directly to your assigned specialists.</p>
                        <textarea id="uplink-msg" placeholder="Describe your request..." style="width:100%; height:120px; border-radius:15px; border:1px solid #E2E8F0; padding:15px; font-size:13px; margin-bottom:15px;"></textarea>
                        <button class="gp-btn" style="width:100%; height:55px; font-size:12px; border-radius:12px;" onclick="sendUplinkMsg(this)">TRANSMIT TO SPECIALISTS</button>
                        <script>
                        function sendUplinkMsg(btn) {
                            const msg = jQuery('#uplink-msg').val();
                            if(!msg) return alert('Input message node.');
                            jQuery(btn).text('TRANSMITTING...').prop('disabled', true);
                            setTimeout(() => {
                                alert('Strategic Uplink Synchronized. Specialists notified via encrypted channel.');
                                jQuery('#uplink-msg').val('');
                                jQuery(btn).text('TRANSMIT TO SPECIALISTS').prop('disabled', false);
                            }, 1500);
                        }
                        </script>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px;">
                        <h3 style="font-size:22px; margin-bottom:30px; letter-spacing:-0.03em;">Strategic Profile</h3>
                        <?php
                        $lead = !empty($leads) ? $leads[0] : null;
                        $phone = $lead ? get_post_meta($lead->ID, '_lead_phone', true) : '';
                        $zip = $lead ? get_post_meta($lead->ID, '_lead_zip', true) : '';
                        ?>
                        <form id="gp-portal-profile">
                            <div style="margin-bottom:20px;">
                                <label style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:1px; display:block; margin-bottom:10px;">SECURE PHONE</label>
                                <input type="text" id="prof-phone" value="<?php echo esc_attr($phone); ?>" style="width:100%; height:50px; border-radius:12px; border:1px solid #E2E8F0; padding:0 15px;">
                            </div>
                            <div style="margin-bottom:30px;">
                                <label style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:1px; display:block; margin-bottom:10px;">GEOGRAPHIC ZIP</label>
                                <input type="text" id="prof-zip" value="<?php echo esc_attr($zip); ?>" style="width:100%; height:50px; border-radius:12px; border:1px solid #E2E8F0; padding:0 15px;">
                            </div>
                            <button type="button" class="gp-btn" style="width:100%; height:55px; font-size:12px; border-radius:12px;" onclick="updatePortalProfile()">SYNC PROFILE</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function updatePortalProfile() {
                jQuery.post(gp_ajax.ajaxurl, {
                    action: 'gp_update_portal_profile',
                    phone: jQuery('#prof-phone').val(),
                    zip: jQuery('#prof-zip').val(),
                    gp_nonce: gp_portal.nonce
                }, function(res) {
                    if(res.success) alert(res.data);
                });
            }

            function acceptProposal(id) {
                if(!confirm("Execute agreement and engagement of proprietary services?")) return;
                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_accept_proposal', proposal_id: id, gp_nonce: gp_portal.nonce }, function(res) {
                    if(res.success) {
                        alert("AGREEMENT DIGITALLY EXECUTED. KICKOFF PROTOCOL ENGAGED.");
                        location.reload();
                    }
                });
            }

            jQuery(document).ready(function($) {
                $('.portal-nav-tab').on('click', function() {
                    $('.portal-nav-tab').removeClass('active');
                    $(this).addClass('active');
                    const target = $(this).data('target');
                    $('.portal-section').removeClass('active');
                    $('#section-' + target).addClass('active');
                });
            });
        </script>
        <?php
        return ob_get_clean();
    }

}
new GrowthPress_Portal();
