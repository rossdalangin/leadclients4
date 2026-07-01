<?php
/**
 * Coaches Niche specialized Closer Tools - Ultra Elite v3.5
 */
class GrowthPress_Coaches {
    public function __construct() {
        add_shortcode('gp_coaching_assistant', array($this, 'render_assistant'));
    }

    public function render_assistant() {
        return '<style>
            .gp-bottleneck-btn { width:100%; background:#DB2777; text-transform:none; border-radius:25px; height:90px; font-size:20px; font-weight: 800; letter-spacing: -0.01em; color: white !important; transition: all 0.4s ease; border: none; cursor: pointer; box-shadow: 0 15px 35px rgba(219, 39, 120, 0.2); }
            .gp-bottleneck-btn:hover { transform: translateY(-8px); box-shadow: 0 25px 50px rgba(219, 39, 120, 0.35); filter: brightness(1.1); }
        </style>
        <div class="glass-card" style="background:#fff1f2; border-left:5px solid #be123c; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#9f1239;"><strong>Success Pattern:</strong> High-ticket coaching nodes utilizing this triage engine report a 40% increase in lead-to-call conversion. <strong>ROI Pro-Tip:</strong> Always route "Market Dominance" inquiries directly to your Senior Growth Architect for immediate psychological profiling.</p>
        </div>
        <div class="gp-coaching-assistant glass-card gp-reveal" style="padding:120px 80px; text-align:center; background: linear-gradient(135deg, rgba(255,255,255,0.95), #FFF5F7); position:relative; overflow:hidden; border-radius: 60px;">
            <div style="position:absolute; top:40px; right:60px; font-size:11px; font-weight:950; opacity:0.3; letter-spacing:5px; text-transform: uppercase;">Elite Performance Triage</div>
            <h3 class="text-gradient" style="font-size:4.5rem; line-height: 0.9; letter-spacing: -0.06em;">Scalability & Performance Engine</h3>
            <p style="font-size:1.5rem; opacity:0.7; max-width:700px; margin:40px auto 0; font-weight: 500; line-height: 1.5;">Select your primary operational bottleneck to generate an AI-powered 12-month high-ticket scaling roadmap.</p>

            <div id="coach-steps" class="glass-card" style="background:#FFF; padding:80px 60px; border-radius:50px; margin-top:80px; box-shadow:0 40px 100px rgba(219,39,119,0.08); border: 1px solid rgba(0,0,0,0.03);">
                <div style="display:grid; gap:25px; margin-bottom:50px;">
                    <button class="gp-bottleneck-btn" onclick="jQuery(\'#coach-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Scale to 7 Figures (Market Dominance)</button>
                    <button class="gp-bottleneck-btn" onclick="jQuery(\'#coach-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Automate High-Authority Content</button>
                    <button class="gp-bottleneck-btn" onclick="jQuery(\'#coach-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">Master Behavioral Sales Psychology</button>
                </div>
                <div style="font-size:12px; font-weight:950; opacity:0.4; text-transform:uppercase; letter-spacing:3px;">Average Inference Time: 7.2 Seconds</div>
            </div>
            <div id="gp-quiz-form" style="display:none; margin-top:60px; max-width: 700px; margin-left: auto; margin-right: auto;">[gp_lead_form]</div>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Strategic Scaling Blueprint Realization',
            'post_content' => 'Comprehensive operational audit and scaling realization for a high-performance mentorship program. By transitioning the client from manual latency to the v6.3 Performance Engine, we identified $2.5M in untapped pipeline equity and achieved absolute market dominance in their sector through behavioral sales optimization and neural content automation.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+680%');
            update_post_meta($id, '_gp_pipeline_value', '$2.5M+');
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Elite Behavioral Sales Triage Node',
            'post_content' => 'High-stakes training and structural deployment of psychological closing tactics and neural triage protocols for high-ticket sales teams. This service empowers founders to master behavioral sales psychology and utilize autonomous lead qualification to maximize conversion velocity and net equity realization.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '🧠');
        }
    }
}
new GrowthPress_Coaches();
