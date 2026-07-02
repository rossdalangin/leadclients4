<?php
/**
 * GrowthPress Strategy Dashboard Tab
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Strategy {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_strategy_menu' ) );
        add_action( 'wp_ajax_gp_generate_roadmap', array( $this, 'handle_roadmap' ) );
    }

    public function add_strategy_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Growth Strategy',
            'Growth Strategy',
            'manage_options',
            'growthpress-strategy',
            array( $this, 'render_strategy' )
        );
    }

    public function handle_roadmap() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();
        $roadmap = $ai->generate_growth_roadmap($niche);
        wp_send_json_success($roadmap);
    }

    public function render_strategy() {
        ?>
        <div class="wrap growthpress-strategy gp-reveal">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:50px;">
                <h1>AI Growth Roadmap</h1>
                <div style="background:var(--primary-glow); color:var(--primary); padding:10px 20px; border-radius:30px; font-size:11px; font-weight:950; letter-spacing:2px;">ENGINE: STRATEGY NODE v6.3</div>
            </div>
            <p class="description" style="font-size:1.2rem; margin-bottom:40px;">Your 12-month tailored strategy for market dominance in the <strong><?php echo ucfirst(get_option('growthpress_niche')); ?></strong> niche.</p>

            <div class="glass-card" style="max-width:100%; background:#f5f3ff; border-left:5px solid #7c3aed; margin-bottom:40px; padding:30px; border-radius:30px;">
                <h4 style="margin:0 0 10px 0; color:#5b21b6; font-weight:950; letter-spacing:1px;">💡 STRATEGIC METHODOLOGY</h4>
                <p style="margin:0; font-size:14px; color:#5b21b6; line-height:1.7;">The Growth Engine utilizes historical niche benchmarks and real-time market data to architect a month-by-month operational plan. It focuses on <strong>Lead Velocity</strong>, <strong>Authority Building</strong>, and <strong>Pipeline Maximization</strong>. This roadmap is designed for high-ticket realization and operational scaling.</p>
            </div>

            <div class="glass-card" style="padding:60px; border-radius:40px; border-top: 8px solid var(--primary);">
                <h3 style="font-size:2rem; margin-bottom:20px;">Initialize Strategic Generation</h3>
                <p style="font-size:16px; opacity:0.6; margin-bottom:40px;">Our AI Strategist will analyze your niche cluster and generate a complete marketing and operations plan. <strong>Success Pattern:</strong> Elite firms implement the first 90 days immediately to establish absolute sector authority before scaling high-ticket outreach.</p>
                <button class="button button-primary button-hero" style="height:70px; padding:0 50px; font-size:16px; border-radius:18px;" onclick="generateRoadmap()">INITIALIZE STRATEGY ENGINE</button>
                <p style="margin-top:15px; font-size:12px; opacity:0.5; font-weight:700;">STRATEGIC NOTE: Generation takes 20-40 seconds to architect your multi-quarter roadmap. Do not refresh.</p>

                <div id="gp-roadmap-status" style="display:none; margin-top:40px;">
                    <div style="display:flex; align-items:center; gap:20px;">
                        <div class="status-ping active"></div>
                        <div style="font-size:12px; font-weight:950; opacity:0.4; letter-spacing:2px;">AI STRATEGIST IS ARCHITECTING YOUR ROADMAP...</div>
                    </div>
                </div>

                <div id="gp-roadmap-output" style="margin-top:50px; line-height:2; font-size:15px; display:none;"></div>
            </div>
        </div>
        <script>
        function generateRoadmap() {
            var $ = jQuery;
            $('#gp-roadmap-status').fadeIn();
            $('#gp-roadmap-output').hide().html('');
            $.post(ajaxurl, { action: 'gp_generate_roadmap', gp_nonce: gp_admin.nonce }, function(res) {
                $('#gp-roadmap-status').hide();
                if(res.success) {
                    $('#gp-roadmap-output').html('<div class="glass-card" style="background:#FFF; padding:60px; border-radius:30px; border:1px solid #EEE; box-shadow:0 15px 35px rgba(0,0,0,0.05); white-space: pre-wrap;">' + res.data + '</div>').fadeIn(600);
                }
            });
        }
        </script>
        <?php
    }
}
new GrowthPress_Strategy();
