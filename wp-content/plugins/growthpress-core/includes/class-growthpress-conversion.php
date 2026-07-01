<?php
/**
 * GrowthPress Conversion & CRO Engine - Elite v1.5.0
 * Implements scarcity, urgency, and social proof components.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Conversion {

    /**
     * Strategic Conversion Node
     *
     * Manages high-stakes CRO triggers including urgency banners and social proof.
     * Methodology: Aggressively reduce operational latency through automated triggers.
     */
    public function __construct() {
        add_shortcode( 'gp_urgency_banner', array( $this, 'render_urgency_banner' ) );
        add_shortcode( 'gp_review_feed', array( $this, 'render_review_feed' ) );
        add_shortcode( 'gp_location_switcher', array( $this, 'render_location_switcher' ) );
        add_shortcode( 'gp_trust_badges', array( $this, 'render_trust_badges' ) );
        add_shortcode( 'gp_stats_bar', array( $this, 'render_stats_bar' ) );
        add_action( 'wp_footer', array( $this, 'render_exit_intent_js' ) );
    }

    public function render_urgency_banner() {
        $niche = get_option('growthpress_niche', 'business');
        $messages = array(
            'dental'    => '🚨 2 Emergency appointments remaining for today. Call now!',
            'law'       => '⚖️ High-priority case slots available for ' . date('F') . '. Secure your consultation.',
            'solar'     => '☀️ Federal Tax Credit Alert: 30% Savings still active. Lock in your rate.',
            'contractor'=> '🔨 Spring booking schedule is 85% full. Get your estimate today.',
            'roofing'   => '🏠 Post-storm inspections prioritized this week. 4 slots left.',
            'medical'   => '🏥 Same-day telemedicine appointments available. Book now.',
            'real-estate'=> '🔑 3 New high-yield properties just hit the off-market list. Inquire now.'
        );
        $msg = $messages[$niche] ?? '🚀 Limited availability for new high-ticket strategy sessions this month.';

        return '<style>
            @keyframes gp-banner-shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
            .gp-urgency-banner::before { content: ""; position: absolute; top: 0; left: 0; width: 30%; height: 100%; background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent); animation: gp-banner-shimmer 3s infinite; }
        </style>
        <div class="gp-urgency-banner gp-reveal" style="background: var(--secondary); color: white; padding: 20px; text-align: center; font-weight: 900; font-size: 14px; position: relative; overflow: hidden; letter-spacing: 2px; text-transform: uppercase; border-bottom: 2px solid var(--primary);">
            <div class="gp-pulse-icon" style="display: inline-block; width: 12px; height: 12px; background: #EF4444; border-radius: 50%; margin-right: 15px; animation: gp-pulse 2s infinite; box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);"></div>
            ' . esc_html($msg) . '
        </div>';
    }

    public function render_review_feed() {
        $reputation = new GrowthPress_Reputation();
        $reviews = $reputation->get_top_reviews(6);

        ob_start(); ?>
        <style>
            .gp-review-card { transition: all 0.4s ease; border-bottom: 8px solid var(--primary) !important; background: rgba(255,255,255,0.8) !important; }
            .gp-review-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px -15px rgba(0,0,0,0.1); }
            .gp-verified-badge { background: #10B981; color: white; padding: 4px 10px; border-radius: 30px; font-size: 9px; font-weight: 950; letter-spacing: 1px; text-transform: uppercase; }
        </style>
        <div class="gp-review-wall gp-reveal" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 40px; margin-top: 80px;">
            <?php if($reviews): foreach($reviews as $r): ?>
                <div class="glass-card gp-review-card" style="padding: 50px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                        <div style="display: flex; gap: 4px; color: #F59E0B; font-size: 16px;">★★★★★</div>
                        <div class="gp-verified-badge">Verified Result</div>
                    </div>
                    <p style="font-style: italic; font-size: 17px; color: var(--text); line-height: 1.8; margin-bottom: 35px; font-weight: 500;">"<?php echo esc_html($r->post_content); ?>"</p>
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 950; color: white; font-size: 16px; box-shadow: 0 10px 20px var(--primary-glow);">
                            <?php echo substr($r->post_title, 0, 1); ?>
                        </div>
                        <div>
                            <div style="font-weight: 950; font-size: 16px; color: var(--secondary); letter-spacing: -0.02em;"><?php echo esc_html($r->post_title); ?></div>
                            <?php $source = get_post_meta($r->ID, '_gp_review_source', true) ?: 'Global Node'; ?>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px;"><?php echo esc_html($source); ?> Authority</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: echo "<p style='text-align:center; opacity:0.5;'>Awaiting social proof synchronization...</p>"; endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_location_switcher() {
        $locations_query = get_posts(array('post_type' => 'gp_location', 'posts_per_page' => -1));
        $locations = !empty($locations_query) ? wp_list_pluck($locations_query, 'post_title') : array('Global Headquarters', 'Regional Strategy Hub');
        ob_start(); ?>
        <style>
            .gp-loc-switcher { border-left: 10px solid var(--accent) !important; background: rgba(255,255,255,0.9) !important; }
            .gp-loc-select { width: 100%; height: 75px; padding: 0 25px; border-radius: 18px; font-weight: 700; appearance: none; border: 2px solid #F1F5F9; font-size: 16px; background: #FFF url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2310B981' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 25px center; transition: all 0.3s ease; }
            .gp-loc-select:focus { border-color: var(--accent); outline: none; box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.1); }
        </style>
        <div class="gp-location-nav glass-card gp-loc-switcher" style="padding: 50px;">
            <div style="font-size: 10px; font-weight: 950; color: var(--accent); text-transform: uppercase; letter-spacing: 3px; margin-bottom: 15px;">Regional Node Selection</div>
            <h4 class="text-gradient" style="margin-top: 0; font-size: 26px; letter-spacing: -0.02em;">Precision Service Coverage</h4>
            <p style="font-size: 15px; margin-bottom: 35px; opacity: 0.7; font-weight: 500;">Select your nearest hub for optimized neural routing.</p>
            <div style="position: relative;">
                <select class="gp-loc-select" onchange="window.location.search = '?location=' + this.value">
                    <option value="">Detecting nearest node...</option>
                    <?php foreach($locations as $loc): ?>
                        <option value="<?php echo esc_attr(sanitize_title($loc)); ?>"><?php echo esc_html($loc); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin-top: 30px; display: flex; align-items: center; gap: 12px; font-size: 12px; font-weight: 900; color: var(--accent); letter-spacing: 0.5px;">
                <div class="gp-pulse-icon" style="width: 10px; height: 10px; background: var(--accent); border-radius: 50%; animation: gp-pulse 2s infinite;"></div>
                NEAREST SPECIALIST RESPONDING IN < 4 MINS
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_trust_badges() {
        return '<style>
            .trust-badge { transition: all 0.3s ease; filter: grayscale(1); cursor: pointer; }
            .trust-badge:hover { filter: grayscale(0); opacity: 1 !important; transform: scale(1.1); }
        </style>
        <div class="gp-trust-grid gp-reveal" style="display: flex; justify-content: center; align-items: center; gap: 80px; margin: 100px 0; flex-wrap: wrap;">
            <div class="trust-badge" style="font-weight: 950; font-size: 28px; letter-spacing: -1.5px; opacity: 0.25;">FORBES</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 28px; letter-spacing: -1.5px; opacity: 0.25;">BLOOMBERG</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 28px; letter-spacing: -1.5px; opacity: 0.25;">TECHCRUNCH</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 28px; letter-spacing: -1.5px; opacity: 0.25;">WIRED</div>
            <div class="trust-badge" style="font-weight: 950; font-size: 28px; letter-spacing: -1.5px; opacity: 0.25;">INC.</div>
        </div>';
    }

    public function render_exit_intent_js() {
        if ( is_admin() ) return;
        ?>
        <div id="gp-exit-intent" class="gp-modal-overlay" style="display:none; z-index: 10002;">
            <div class="glass-card" style="max-width:600px; margin: 100px auto; padding:80px; text-align:center; position:relative;">
                <div style="font-size:10px; font-weight:950; color:var(--primary); letter-spacing:4px; margin-bottom:20px;">WAIT! DON'T LEAVE YET</div>
                <h3 class="text-gradient" style="font-size:3rem; line-height:1;">Get the Authority Blueprint</h3>
                <p style="font-size:1.1rem; opacity:0.7; margin:30px 0 50px;">Our AI just analyzed your session and prepared a specialized <?php echo get_option('growthpress_niche', 'business'); ?> growth roadmap for you.</p>
                <?php echo do_shortcode('[gp_lead_form]'); ?>
                <div style="cursor:pointer; position:absolute; top:30px; right:30px; opacity:0.3; font-weight:900;" onclick="jQuery('#gp-exit-intent').fadeOut()">CLOSE</div>
            </div>
        </div>
        <script>
            document.addEventListener("mouseleave", function(e) {
                if (e.clientY < 0 && !sessionStorage.getItem('gp_exit_triggered')) {
                    jQuery('#gp-exit-intent').fadeIn();
                    sessionStorage.setItem('gp_exit_triggered', '1');
                    GrowthPress_Activity_JS_Log('Exit intent detected and lead magnet triggered.');
                }
            }, false);
            function GrowthPress_Activity_JS_Log(msg) {
                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_log_behavior', page: msg, email: 'anonymous@visitor.com' });
            }
        </script>
        <?php
    }

    public function render_stats_bar() {
        return '<div class="gp-stats-bar glass-card gp-reveal" style="display: grid; grid-template-columns: repeat(3, 1fr); padding: 80px 40px; text-align: center; margin: 100px 0; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 40px 100px -20px rgba(0,0,0,0.1);">
            <div style="border-right: 1px solid rgba(0,0,0,0.05);">
                <div class="text-gradient gp-counter" data-target="250" style="font-size: 4.5rem; font-weight: 950; line-height: 1; letter-spacing: -0.05em;">$250M+</div>
                <div style="font-size: 11px; font-weight: 900; text-transform: uppercase; margin-top: 20px; letter-spacing: 3px; opacity: 0.4;">Pipeline Equity</div>
            </div>
            <div style="border-right: 1px solid rgba(0,0,0,0.05);">
                <div class="text-gradient gp-counter" data-target="14" style="font-size: 4.5rem; font-weight: 950; line-height: 1; letter-spacing: -0.05em;">14k+</div>
                <div style="font-size: 11px; font-weight: 900; text-transform: uppercase; margin-top: 20px; letter-spacing: 3px; opacity: 0.4;">Inquiries Triage</div>
            </div>
            <div>
                <div class="text-gradient gp-counter" data-target="98" style="font-size: 4.5rem; font-weight: 950; line-height: 1; letter-spacing: -0.05em;">98%</div>
                <div style="font-size: 11px; font-weight: 900; text-transform: uppercase; margin-top: 20px; letter-spacing: 3px; opacity: 0.4;">Client Retention</div>
            </div>
        </div>';
    }
}
new GrowthPress_Conversion();
