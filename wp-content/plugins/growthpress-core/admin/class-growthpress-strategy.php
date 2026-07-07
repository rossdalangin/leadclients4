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
            'edit_posts',
            'growthpress-strategy',
            array( $this, 'render_strategy' )
        );
    }

    public function handle_roadmap() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();
        $roadmap = $ai->generate_growth_roadmap($niche);

        if(is_wp_error($roadmap)) {
            wp_send_json_error($roadmap->get_error_message());
        }

        update_option('gp_stored_roadmap_' . $niche, $roadmap);
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
                <div style="background:#FFFBEB; border-left:5px solid #F59E0B; padding:20px; border-radius:15px; margin-bottom:40px; display:flex; gap:15px; align-items:center;">
                    <div style="font-size:24px;">⌛</div>
                    <div style="font-size:13px; font-weight:800; color:#92400E; line-height:1.4;">STRATEGIC NOTE: Roadmap generation involves complex niche-cluster analysis and multi-quarter architecture. Processing takes 20-40 seconds. Do not refresh the terminal.</div>
                </div>
                <?php
                $niche = get_option('growthpress_niche', 'business');
                $stored = get_option('gp_stored_roadmap_' . $niche);
                ?>
                <h3 style="font-size:2rem; margin-bottom:20px;">Initialize Strategic Generation (<?php echo strtoupper($niche); ?> NICHE)</h3>
                <p style="font-size:16px; opacity:0.6; margin-bottom:40px;">Our AI Strategist will analyze your niche cluster and generate a complete marketing and operations plan tailored specifically for the <strong><?php echo esc_html($niche); ?></strong> sector. <strong>Success Pattern:</strong> Elite firms implement the first 90 days immediately to establish absolute sector authority before scaling high-ticket outreach.</p>
                <div style="display:flex; gap:20px;">
                    <button class="button button-primary button-hero" style="height:70px; padding:0 50px; font-size:16px; border-radius:18px;" onclick="generateRoadmap()">INITIALIZE STRATEGY ENGINE</button>
                    <?php if($stored): ?>
                        <button class="button button-secondary" style="height:70px; padding:0 40px; border-radius:18px; font-weight:700;" onclick="jQuery('html, body').animate({scrollTop: jQuery('#gp-roadmap-output').offset().top - 100}, 800);">VIEW CURRENT ROADMAP</button>
                    <?php endif; ?>
                </div>

                <?php if($stored): ?>
                    <div style="margin-top:30px; padding:15px; background:rgba(16, 185, 129, 0.1); border-radius:10px; border:1px solid rgba(16, 185, 129, 0.2); font-size:12px; color:#065F46; font-weight:700;">
                        ✓ PREVIOUS STRATEGY DETECTED. SCROLL DOWN TO VIEW.
                    </div>
                <?php endif; ?>

                <div id="gp-roadmap-status" style="display:none; margin-top:40px;">
                    <div style="display:flex; align-items:center; gap:20px;">
                        <div class="status-ping active"></div>
                        <div style="font-size:12px; font-weight:950; opacity:0.4; letter-spacing:2px;">AI STRATEGIST IS ARCHITECTING YOUR ROADMAP...</div>
                    </div>
                </div>

                <div id="gp-roadmap-output" style="margin-top:50px; line-height:2; font-size:15px; <?php echo $stored ? '' : 'display:none;'; ?>">
                    <?php if($stored): ?>
                        <div class="glass-card roadmap-container" style="padding:80px; border-radius:40px; white-space: pre-wrap; font-family:'Inter', sans-serif;">
                            <?php
                            $structured = $stored;
                            $structured = str_replace('Q1:', '<h2 style="color:var(--primary); margin-top:0;">Q1: Foundation & Authority</h2>', $structured);
                            $structured = str_replace('Q2:', '<h2 style="color:#10B981; margin-top:60px;">Q2: Operational Acceleration</h2>', $structured);
                            $structured = str_replace('Q3:', '<h2 style="color:#F59E0B; margin-top:60px;">Q3: Market Dominance</h2>', $structured);
                            $structured = str_replace('Q4:', '<h2 style="color:#EF4444; margin-top:60px;">Q4: Scaled Realization</h2>', $structured);
                            $structured = str_replace('Neural Milestone:', '<div style="background:var(--primary-glow); padding:15px 25px; border-radius:15px; margin-top:15px; font-weight:950; color:var(--primary); font-size:13px; display:inline-block;">🎯 NEURAL MILESTONE:</div>', $structured);
                            echo $structured;
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script>
        function generateRoadmap() {
            var $ = jQuery;
            gp_start_intelligence_uplink('ARCHITECTING 12-MONTH ROADMAP...');
            $('#gp-roadmap-status').fadeIn();
            $('#gp-roadmap-output').hide().html('');
            $.post(ajaxurl, { action: 'gp_generate_roadmap', gp_nonce: gp_admin.nonce }, function(res) {
                gp_stop_intelligence_uplink();
                $('#gp-roadmap-status').hide();
                if(res.success) {
                    // Structure the roadmap with cinematic styling
                    let structured = res.data;
                    structured = structured.replace(/Q1:/g, '<h2 style="color:var(--primary); margin-top:0;">Q1: Foundation & Authority</h2>');
                    structured = structured.replace(/Q2:/g, '<h2 style="color:#10B981; margin-top:60px;">Q2: Operational Acceleration</h2>');
                    structured = structured.replace(/Q3:/g, '<h2 style="color:#F59E0B; margin-top:60px;">Q3: Market Dominance</h2>');
                    structured = structured.replace(/Q4:/g, '<h2 style="color:#EF4444; margin-top:60px;">Q4: Scaled Realization</h2>');
                    structured = structured.replace(/Neural Milestone:/g, '<div style="background:var(--primary-glow); padding:15px 25px; border-radius:15px; margin-top:15px; font-weight:950; color:var(--primary); font-size:13px; display:inline-block;">🎯 NEURAL MILESTONE:</div>');

                    $('#gp-roadmap-output').html('<div class="glass-card roadmap-container" style="padding:80px; border-radius:40px; white-space: pre-wrap; font-family:\'Inter\', sans-serif;">' + structured + '</div>').fadeIn(600);

                    // Auto-scroll to the roadmap
                    $('html, body').animate({
                        scrollTop: $("#gp-roadmap-output").offset().top - 100
                    }, 1000);
                } else {
                    alert('STRATEGIC ERROR: ' + res.data);
                }
            });
        }
        </script>
        <?php
    }
}
new GrowthPress_Strategy();
