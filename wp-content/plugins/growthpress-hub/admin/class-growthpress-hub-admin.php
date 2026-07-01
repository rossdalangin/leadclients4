<?php
/**
 * GrowthPress Hub Admin Class
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Hub_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_hub_menu' ) );
        add_action( 'wp_ajax_gp_hub_issue_license', array( $this, 'ajax_issue_license' ) );
        add_action( 'wp_ajax_gp_hub_revoke_license', array( $this, 'ajax_revoke_license' ) );
        add_action( 'wp_ajax_gp_hub_generate_sample', array( $this, 'ajax_generate_sample' ) );
        add_action( 'wp_ajax_gp_hub_generate_global', array( $this, 'ajax_generate_global' ) );
        add_action( 'wp_ajax_gp_hub_remove_sample', array( $this, 'ajax_remove_sample' ) );
    }

    public function add_hub_menu() {
        add_menu_page( 'GP Hub', 'GP Hub', 'manage_options', 'gp-hub', array( $this, 'render_license_manager' ), 'dashicons-rest-api', 3 );
        add_submenu_page( 'gp-hub', 'License Manager', 'License Manager', 'manage_options', 'gp-hub', array( $this, 'render_license_manager' ) );
        add_submenu_page( 'gp-hub', 'System Generator', 'System Generator', 'manage_options', 'gp-hub-generator', array( $this, 'render_generator' ) );
    }

    public function render_license_manager() {
        include GROWTHPRESS_HUB_PATH . 'admin/views/license-manager.php';
    }

    public function render_generator() {
        ?>
        <div class="wrap gp-hub-generator gp-reveal">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding:30px; background:rgba(255,255,255,0.6); border-radius:30px; border:1px solid #EEE;">
                <h1 style="margin:0;">System Generation Hub</h1>
                <div style="background:#1E293B; color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:900; letter-spacing:1px;">GENERATOR v1.0</div>
            </div>

            <div class="glass-card" style="max-width:1000px; background:#f5f3ff; border-left:5px solid #7c3aed; margin-bottom:30px; padding:20px;">
                <p style="margin:0; font-size:14px; color:#5b21b6;"><strong>Generator Intelligence:</strong> Use these tools to instantiate high-fidelity strategic nodes across the GrowthPress ecosystem. This is recommended for initializing new client environments or creating enterprise-grade demonstration clusters.</p>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px;">
                <div class="glass-card" style="padding:50px;">
                    <h3 class="text-gradient">Ecosystem Instantiation</h3>
                    <p style="opacity:0.6; margin-bottom:30px;">Generate a full suite of realistic sample data for the currently active niche.</p>
                    <button class="button button-primary" style="height:55px; width:100%; border-radius:12px;" onclick="runHubTool('gp_hub_generate_sample')">Generate Active Niche Data</button>
                </div>
                <div class="glass-card" style="padding:50px; background:#F8FAFC;">
                    <h3 style="margin-top:0;">Global Mass Instantiation</h3>
                    <p style="opacity:0.6; margin-bottom:30px;">Populate sample data for all 10 niches simultaneously. Generates 100+ strategic nodes.</p>
                    <button class="button" style="height:55px; width:100%; border-radius:12px;" onclick="runHubTool('gp_hub_generate_global')">Execute Global Propagation</button>
                </div>
            </div>
            <div style="margin-top:30px;">
                <div class="glass-card" style="padding:40px; background:#FEF2F2; border:1px solid #FCA5A5; text-align:center;">
                    <h4 style="color:#B91C1C; margin-top:0;">Dangerous: Purge Intelligence</h4>
                    <p style="font-size:13px; color:#991B1B; margin-bottom:20px;">Safely remove all system-generated sample data from the ecosystem while preserving production records.</p>
                    <button class="button" style="background:#EF4444; color:white; border:none; height:45px; padding:0 30px; border-radius:10px;" onclick="if(confirm('Purge all sample intelligence?')) runHubTool('gp_hub_remove_sample')">Purge Sample Nodes</button>
                </div>
            </div>
            <div id="hub-tool-res" style="margin-top:30px; padding:20px; border-radius:15px; display:none; text-align:center; font-weight:900;"></div>
        </div>
        <script>
        function runHubTool(action) {
            const res = jQuery('#hub-tool-res').fadeIn().text('EXECUTING PROTOCOL...').css({'background':'#F1F5F9','color':'#64748B'});
            jQuery.post(ajaxurl, { action: action, gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function(response) {
                if(response.success) res.text(response.data).css({'background':'#F0FDF4','color':'#10B981'});
                else res.text(response.data).css({'background':'#FEF2F2','color':'#EF4444'});
            });
        }
        </script>
        <?php
    }

    public function ajax_issue_license() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $email = sanitize_email( $_POST['customer_email'] );
        $tier = sanitize_text_field( $_POST['license_tier'] );
        $hub = GrowthPress_License::get_instance();
        $key = $hub->issue_license( $email, $tier );
        if ( $key ) wp_send_json_success( "License Node instantiated: $key" );
        else wp_send_json_error( "Failed to initialize cryptographic node." );
    }

    public function ajax_revoke_license() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $hub = GrowthPress_License::get_instance();
        $hub->revoke_license( intval( $_POST['license_id'] ) );
        wp_send_json_success();
    }

    public function ajax_generate_sample() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        GrowthPress_Sample_Data::generate_all_sample_data();
        wp_send_json_success("Active niche ecosystem successfully instantiated.");
    }

    public function ajax_generate_global() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        GrowthPress_Sample_Data::generate_everything();
        wp_send_json_success("Global multi-niche ecosystem successfully instantiated.");
    }

    public function ajax_remove_sample() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        GrowthPress_Sample_Data::remove_all_sample_data();
        wp_send_json_success("Sample intelligence successfully purged from ecosystem.");
    }
}
new GrowthPress_Hub_Admin();
