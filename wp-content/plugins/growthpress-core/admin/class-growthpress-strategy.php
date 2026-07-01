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
        <div class="wrap growthpress-strategy">
            <h1>AI Growth Roadmap</h1>
            <p class="description">Your 12-month tailored strategy for market dominance in the <strong><?php echo ucfirst(get_option('growthpress_niche')); ?></strong> niche.</p>

            <div class="glass-card" style="max-width:100%; background:#f5f3ff; border-left:5px solid #7c3aed; margin-bottom:30px; padding:20px;">
                <h4 style="margin:0 0 10px 0; color:#5b21b6;">💡 Strategic Methodology</h4>
                <p style="margin:0; font-size:13px; color:#5b21b6; line-height:1.5;">The Growth Engine uses historical niche benchmarks and real-time market data to architect a month-by-month operational plan. It focuses on lead velocity, authority building, and pipeline maximization.</p>
            </div>

            <div class="glass-card" style="margin-top: 20px;">
                <h3>Generate Your Roadmap</h3>
                <p>Our AI will analyze your niche and generate a complete marketing and operations plan. <strong>Success Pattern:</strong> Implement the first 90 days immediately to establish market authority before scaling high-ticket outreach.</p>
                <button class="button button-primary" onclick="generateRoadmap()">Initialize Strategy Engine</button>
                <div id="gp-roadmap-output" style="margin-top:30px; line-height:1.8;"></div>
            </div>
        </div>
        <script>
        function generateRoadmap() {
            var $ = jQuery;
            $('#gp-roadmap-output').html('AI Strategist is thinking...');
            $.post(ajaxurl, { action: 'gp_generate_roadmap', gp_nonce: gp_admin.nonce }, function(res) {
                if(res.success) $('#gp-roadmap-output').html('<div class="glass-card" style="background:#fff;">' + res.data + '</div>');
            });
        }
        </script>
        <?php
    }
}
new GrowthPress_Strategy();
