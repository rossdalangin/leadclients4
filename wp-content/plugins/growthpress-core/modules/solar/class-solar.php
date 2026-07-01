<?php
/**
 * Solar Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_Solar {
    public function __construct() {
        add_shortcode('gp_solar_calculator', array($this, 'render_solar_calc'));
        add_shortcode('gp_solar_financing', array($this, 'render_solar_financing'));
        add_action('gp_niche_lead_analysis', array($this, 'analyze_solar_lead'));
    }

    public function render_solar_financing() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        return '<style>
            .gp-solar-financing input, .gp-solar-financing select, .gp-solar-financing textarea { width: 100%; background: #FFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 0 25px; transition: all 0.3s ease; font-weight: 700; }
            .gp-solar-financing input, .gp-solar-financing select { height: 75px; }
            .gp-solar-financing textarea { padding: 25px; line-height: 1.7; }
            .gp-solar-financing input:focus, .gp-solar-financing select:focus, .gp-solar-financing textarea:focus { border-color: #10B981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); outline: none; }
        </style>
        <div class="gp-solar-financing glass-card gp-reveal" style="border-left: 20px solid #10B981; padding:100px 80px; background: linear-gradient(135deg, rgba(255,255,255,0.9), #F0FDF4); border-radius: 50px;">
            <div style="text-align:center; margin-bottom:70px;">
                <div style="font-size:11px; font-weight:950; color:#10B981; text-transform:uppercase; letter-spacing:5px; margin-bottom:20px;">CAPITAL DEPLOYMENT NODE v6.3</div>
                <h3 class="text-gradient" style="font-size:4rem; line-height:1.0; letter-spacing: -0.05em;">$0-Down Solar Financing</h3>
                <p style="font-size:1.4rem; opacity:0.7; max-width:700px; margin:30px auto 0; font-weight: 500;">Apply for immediate capital authorization to initialize your energy independence sequence with no upfront investment.</p>
            </div>
            <form class="gp-form" data-action="gp_submit_lead">
                <input type="hidden" name="nonce" value="'.$nonce.'">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:35px; margin-bottom:35px;">
                    <div><label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">APPLICANT IDENTITY</label><input type="text" name="lead_name" placeholder="Full Legal Name" required></div>
                    <div><label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">SECURE CHANNEL</label><input type="email" name="lead_email" placeholder="Direct Email" required></div>
                </div>
                <div style="margin-bottom:35px;">
                    <label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">ESTIMATED CREDIT TIER</label>
                    <select name="lead_msg_prefix">
                        <option value="Tier1">Executive Elite (740+)</option>
                        <option value="Tier2">Strategic Prime (680-739)</option>
                        <option value="Tier3">Standard (640-679)</option>
                    </select>
                </div>
                <div style="margin-bottom:45px;">
                    <label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">FINANCING REQUIREMENTS</label>
                    <textarea name="lead_msg" placeholder="Summarize energy goals or specific financing requirements..." style="height:180px;"></textarea>
                </div>
                <button type="submit" class="gp-btn" style="width:100%; height:95px; font-size:22px; background:#10B981; border-radius: 25px;">INITIALIZE FINANCING AUDIT</button>
            </form>
        </div>';
    }

    public function analyze_solar_lead($lead_id) {
        $lead = get_post($lead_id);
        $content = strtolower($lead->post_content);
        $crm = GrowthPress_CRM::get_instance();

        if (strpos($content, 'bill') !== false || strpos($content, 'utility') !== false) {
            $crm->create_task("Utility Load Analysis", "Lead provided bill context. Calculate ROI modeling.", $lead_id);
        }

        if (strpos($content, 'financing') !== false || strpos($content, 'credit') !== false) {
            $crm->create_task("Solar Finance Qualification", "Lead inquired about $0-down options. Run preliminary check.", $lead_id);
            wp_set_object_terms($lead_id, 'Financing-Lead', 'gp_lead_tag', true);
        }
    }

    public function render_solar_calc() {
        return '<style>
            .solar-slider { -webkit-appearance: none; height: 12px; border-radius: 10px; background: #F1F5F9; outline: none; transition: all 0.3s ease; }
            .solar-slider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 30px; height: 30px; border-radius: 50%; background: #F59E0B; cursor: pointer; box-shadow: 0 0 15px rgba(245, 158, 11, 0.4); border: 4px solid #FFF; }
        </style>
        <div class="glass-card" style="background:#fffcf2; border-left:5px solid #f59e0b; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#92400e;"><strong>Energy Success Pattern:</strong> Solar deployment nodes using the Precision ROI Predictor report a 62% higher retention rate during the engineering phase. <strong>ROI Pro-Tip:</strong> Emphasize the 30% Federal Tax Credit and long-term energy equity in the initial discovery sequence.</p>
        </div>
        <div class="gp-solar-calc glass-card gp-reveal" style="border-top: 20px solid #F59E0B; text-align:center; padding:120px 80px; background: linear-gradient(180deg, rgba(245,158,11,0.06) 0%, transparent 100%), rgba(255,255,255,0.9); border-radius: 60px; box-shadow: 0 60px 120px -30px rgba(0,0,0,0.15);">
            <div style="text-align:center; margin-bottom:100px;">
                <div style="font-size:11px; font-weight:950; color:#F59E0B; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">ENERGY INDEPENDENCE ENGINE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">Precision ROI Predictor</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500;">Determine your 25-year energy equity and federal incentive eligibility with neural precision modeling.</p>
            </div>

            <div style="background:#FFF; border-radius:60px; border:1px solid #F1F5F9; padding:100px 80px; margin-bottom:80px; position:relative; box-shadow:0 50px 100px rgba(0,0,0,0.05);">
                <div style="display:grid; grid-template-columns: 1.2fr 1fr; gap:100px; text-align:left; align-items:center;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:40px;">MONTHLY UTILITY LOAD ($)</label>
                        <input type="range" min="100" max="2500" value="450" class="solar-slider" id="solar-input" style="width:100%;">
                        <div style="font-size:70px; font-weight:950; color:var(--secondary); margin-top:50px; letter-spacing:-0.05em; line-height: 1;">$<span id="solar-val">450</span></div>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:20px;">ESTIMATED 25-YR EQUITY</label>
                        <div class="text-gradient" style="font-size:7.5rem; font-weight:950; line-height:0.9; letter-spacing:-0.07em;">$<span id="roi-val">94,500</span></div>
                        <div style="margin-top:35px; display:flex; align-items:center; gap:15px;">
                            <div style="width:14px; height:12px; background:#10B981; border-radius:50%; box-shadow:0 0 15px rgba(16, 185, 129, 0.5);"></div>
                            <span style="font-size:13px; font-weight:950; color:#10B981; letter-spacing:1px; text-transform: uppercase;">Inc. 30% Federal Tax Credit</span>
                        </div>
                    </div>
                </div>
                <!-- Neural Performance Chart -->
                <div style="margin-top:100px; height:220px; display:flex; align-items:flex-end; gap:15px;">
                    <div style="flex:1; background:#F1F5F9; height:20%; border-radius:12px; transition: height 0.6s ease;"></div>
                    <div style="flex:1; background:#F1F5F9; height:35%; border-radius:12px; transition: height 0.6s ease;"></div>
                    <div style="flex:1; background:#F1F5F9; height:50%; border-radius:12px; transition: height 0.6s ease;"></div>
                    <div style="flex:1; background:#F59E0B; height:65%; border-radius:12px; box-shadow:0 20px 50px rgba(245,158,11,0.3); transition: height 0.6s ease;"></div>
                    <div style="flex:1; background:#F59E0B; height:80%; border-radius:12px; box-shadow:0 20px 50px rgba(245,158,11,0.3); transition: height 0.6s ease;"></div>
                    <div style="flex:1; background:#10B981; height:100%; border-radius:12px; box-shadow:0 30px 60px rgba(16,185,129,0.4); transition: height 0.6s ease;"></div>
                </div>
            </div>

            <button class="gp-btn" style="width:100%; height:100px; font-size:24px; border-radius:30px; letter-spacing: 2px;" onclick="jQuery(\'#gp-quiz-step-1\').hide(); jQuery(\'#gp-quiz-form\').show();">INITIATE PRECISION ENGINEERING AUDIT</button>

            <script>
                jQuery("#solar-input").on("input", function() {
                    var v = parseInt(jQuery(this).val());
                    jQuery("#solar-val").text(v);
                    jQuery("#roi-val").text((v * 12 * 25 * 0.75).toLocaleString());
                });
                jQuery("#solar-input").on("change", function() {
                    if(typeof gpLogHighIntent === "function") {
                        gpLogHighIntent("Solar Calc Value: $" + jQuery(this).val());
                    }
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Elite Residential Array Realization',
            'post_content' => 'Full-scale realization of a neural-optimized solar infrastructure for a premium modernist estate. This deployment utilized high-efficiency Tier-1 panels and integrated smart battery storage to achieve 98% grid independence. The project successfully captured 30% federal tax credits and established a 25-year energy equity trajectory for the client.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+320%');
            update_post_meta($id, '_gp_pipeline_value', '$85k+');
            update_post_meta($id, '_gp_ai_score', 96);
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Precision Grid Independence Audit',
            'post_content' => 'Comprehensive structural and energy audit designed to determine absolute solar eligibility and generate high-fidelity ROI modeling. Our engineering specialists utilize the v6.3 Energy Independence Engine to identify the optimal array configuration for maximum capital preservation and grid-independent realization.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '☀️');
        }
    }
}
new GrowthPress_Solar();
