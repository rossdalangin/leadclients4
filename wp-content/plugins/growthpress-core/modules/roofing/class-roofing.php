<?php
/**
 * Roofing Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_Roofing {
    public function __construct() {
        add_shortcode('gp_roofing_estimator', array($this, 'render_roofing_estimator'));
        add_action('wp_ajax_gp_roofing_process_drone', array($this, 'handle_drone_process'));
    }

    public function handle_drone_process() {
        check_ajax_referer('gp_admin_nonce', 'gp_nonce');
        $lead_id = intval($_POST['lead_id']);

        $ai = GrowthPress_AI::get_instance();
        $report = $ai->call_ai("Generate a technical Drone Structural Audit report based on mock telemetry for Lead #$lead_id. Identify 3 critical fracture nodes and recommend Luxury Natural Slate for 100% asset protection.", "Drone Intelligence AI");

        update_post_meta($lead_id, '_gp_drone_report', $report);
        GrowthPress_Activity::log("Drone telemetry processed for Lead #$lead_id. Structural report generated.");

        wp_send_json_success(array('report' => $report));
    }

    public function render_roofing_estimator() {
        return '<style>
            #roof-mat, #roof-sqs { width:100%; height:80px; border-radius:25px; font-weight:700; border:2px solid #F1F5F9; padding:0 30px; font-size:18px; appearance: none; background: #FFF; transition: all 0.3s ease; }
            #roof-mat:focus, #roof-sqs:focus { border-color: #475569; outline: none; box-shadow: 0 0 0 4px rgba(71, 85, 105, 0.1); }
        </style>
        <div class="glass-card" style="background:#f1f5f9; border-left:5px solid #475569; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#1e293b;"><strong>Asset Protection Pattern:</strong> Industrial roofing nodes using the Asset Protection Engine report a 38% reduction in structural liability risks. <strong>ROI Pro-Tip:</strong> High-authority materials like Luxury Natural Slate typically yield a 42% higher long-term ROI in corporate sectors and should be prioritized in drone-site surveys.</p>
        </div>
        <div class="gp-estimator glass-card gp-reveal" style="border-left: 20px solid #475569; padding:120px 80px; background: linear-gradient(135deg, rgba(255,255,255,0.9), #F1F5F9); border-radius: 60px;">
            <div style="text-align:center; margin-bottom:100px;">
                <div style="font-size:11px; font-weight:950; color:#475569; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">ASSET PROTECTION ENGINE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">Elite Roof Replacement Estimator</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500; line-height: 1.5;">Determine your replacement investment based on high-authority material quality and structural complexity.</p>
            </div>

            <div id="roof-steps" class="glass-card" style="background:#FFF; padding:100px 80px; border-radius:60px; box-shadow:0 60px 120px -20px rgba(0,0,0,0.1);">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:50px; margin-bottom:60px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:25px;">MATERIAL SPECIFICATION</label>
                        <select id="roof-mat">
                            <option value="650">Architectural Shingle</option>
                            <option value="1200">Standing Seam Metal</option>
                            <option value="2500">Luxury Natural Slate</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:25px;">TOTAL SQUARES (100sqft)</label>
                        <input type="number" id="roof-sqs" value="45">
                    </div>
                </div>
                <div style="background:#F1F5F9; border:2px solid #E2E8F0; padding:80px 40px; border-radius:50px; text-align:center; margin-bottom:80px;">
                    <div style="font-size:11px; font-weight:950; color:var(--secondary); opacity:0.5; letter-spacing:2px; margin-bottom:25px; text-transform: uppercase;">Estimated Replacement Investment</div>
                    <div class="text-gradient" style="font-size:7.5rem; font-weight:950; color:#475569; line-height:1; letter-spacing:-0.07em;">$<span id="roof-val">29,250</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:100px; font-size:24px; background:#475569; border-radius:30px; letter-spacing: 2px;" onclick="initiateDroneSequence()">INITIATE DRONE SITE SURVEY</button>
            </div>
            <script>
            function initiateDroneSequence() {
                var self = jQuery;
                self("#roof-steps").fadeOut(400, function() {
                    self("#gp-quiz-form").fadeIn();
                    if(typeof gpLogHighIntent === "function") {
                        gpLogHighIntent("Initiated Drone Survey for $" + self("#roof-val").text());
                    }
                });
            }
            </script>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#roof-mat, #roof-sqs").on("change input", function() {
                    var mat = parseInt(jQuery("#roof-mat").val());
                    var sqs = parseInt(jQuery("#roof-sqs").val()) || 0;
                    jQuery("#roof-val").text((mat * sqs).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Industrial Complex Realization',
            'post_content' => 'Large-scale industrial roof replacement realization for a regional corporate headquarters. This project utilized the v6.3 Asset Protection Engine and precision drone-assisted structural auditing to identify 14 micro-fracture nodes. The deployment featured high-authority natural slate material specification, resulting in an 85% increase in structural ROI.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+85%');
            update_post_meta($id, '_gp_pipeline_value', '$420k');
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Elite Precision Drone Survey Hub',
            'post_content' => 'High-fidelity structural auditing node using specialized drone hardware and neural imaging to identify micro-fractures, thermal leakage, and drainage bottlenecks. This service provides absolute structural transparency and serves as the foundational data node for high-ticket roof replacement realization.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '🚁');
        }
    }
}
new GrowthPress_Roofing();
