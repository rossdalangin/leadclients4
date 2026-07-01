<?php
/**
 * Contractor Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_Contractor {
    public function __construct() {
        add_shortcode('gp_contractor_estimator', array($this, 'render_estimator'));
        add_shortcode('gp_project_roi_tracker', array($this, 'render_roi_tracker'));
    }

    public function render_roi_tracker() {
        $projects = get_posts(array('post_type' => 'gp_project', 'posts_per_page' => 3, 'meta_key' => '_gp_is_sample', 'meta_value' => '1'));
        ob_start(); ?>
        <style>
            .gp-roi-item { transition: all 0.4s ease; border: 1px solid rgba(0,0,0,0.05); }
            .gp-roi-item:hover { transform: scale(1.02); border-color: var(--primary); background: #FFF !important; }
            .gp-roi-bar-fill { transition: width 2s cubic-bezier(0.16, 1, 0.3, 1); }
        </style>
        <div class="gp-project-roi-tracker glass-card gp-reveal" style="padding:80px; border-radius: 60px;">
            <div style="text-align: center; margin-bottom: 60px;">
                <div style="font-size: 11px; font-weight: 950; color: var(--primary); text-transform: uppercase; letter-spacing: 5px; margin-bottom: 15px;">Success Portfolio</div>
                <h3 style="margin:0; font-size: 3.5rem; letter-spacing: -0.06em; font-weight: 950; line-height: 1;">Structural ROI Performance</h3>
            </div>
            <div style="display:grid; gap:40px;">
                <?php foreach($projects as $p):
                    $roi = get_post_meta($p->ID, '_gp_growth_roi', true) ?: '+15%';
                    $value = get_post_meta($p->ID, '_gp_pipeline_value', true) ?: '$250k';
                    $rand_width = rand(70, 98);
                    ?>
                    <div class="gp-roi-item" style="background:rgba(255,255,255,0.6); padding:40px; border-radius:40px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:35px;">
                            <div>
                                <h4 style="margin:0; font-size:22px; font-weight: 950; letter-spacing: -0.02em;"><?php echo esc_html($p->post_title); ?></h4>
                                <div style="font-size:12px; font-weight: 800; opacity:0.4; margin-top:10px; text-transform: uppercase; letter-spacing: 1px;">Ecosystem Valuation: <span style="color:var(--secondary); opacity: 1;"><?php echo $value; ?></span></div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:32px; font-weight:950; color:var(--primary); line-height: 1;"><?php echo $roi; ?></div>
                                <div style="font-size:11px; font-weight:950; opacity:0.3; text-transform: uppercase; letter-spacing: 1px; margin-top: 8px;">Net Equity Gain</div>
                            </div>
                        </div>
                        <div style="height:12px; background:#F1F5F9; border-radius:15px; overflow:hidden; position: relative;">
                            <div class="gp-roi-bar-fill" style="width:<?php echo $rand_width; ?>%; height:100%; background:linear-gradient(90deg, var(--primary), var(--primary-alt)); box-shadow: 0 0 20px var(--primary-glow);"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_estimator() {
        return '<style>
            #proj-type, #proj-sqft { width:100%; height:80px; border-radius:25px; font-weight:700; border:2px solid #F1F5F9; padding:0 30px; font-size:18px; appearance: none; background: #FFF; transition: all 0.3s ease; }
            #proj-type:focus, #proj-sqft:focus { border-color: #EF4444; outline: none; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1); }
        </style>
        <div class="glass-card" style="background:#fff5f5; border-left:5px solid #ef4444; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#991b1b;"><strong>Operational Success Pattern:</strong> General contractors utilizing the Precision Quotation Engine report a 31% increase in "Design-to-Build" conversion velocity. <strong>ROI Pro-Tip:</strong> High-ticket renovation leads should be routed to \'Structural Engineering\' nodes within 24 hours of audit completion.</p>
        </div>
        <div class="gp-estimator glass-card gp-reveal" style="border-left: 20px solid #EF4444; padding:120px 80px; background: linear-gradient(135deg, rgba(255,255,255,0.9), #FFF5F5); border-radius: 60px;">
            <div style="text-align:center; margin-bottom:100px;">
                <div style="font-size:11px; font-weight:950; color:#EF4444; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">PRECISION QUOTATION ENGINE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">Elite Renovation Estimator</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500; line-height: 1.5;">Instant baseline engineering audit for your high-ticket renovation project using neural estimation logic.</p>
            </div>

            <div id="est-steps" class="glass-card" style="background:#FFF; padding:100px 80px; border-radius:60px; box-shadow:0 60px 120px -20px rgba(0,0,0,0.1);">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:50px; margin-bottom:60px;">
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:25px;">PROJECT CLASSIFICATION</label>
                        <select id="proj-type">
                            <option value="250">Kitchen Transformation</option>
                            <option value="180">Master Bath Elite</option>
                            <option value="350">Full Structural Overhaul</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:25px;">SQ FOOTAGE (EST)</label>
                        <input type="number" id="proj-sqft" value="850">
                    </div>
                </div>
                <div style="background:#FEF2F2; border:2px solid #FEE2E2; padding:80px 40px; border-radius:50px; text-align:center; margin-bottom:80px;">
                    <div style="font-size:11px; font-weight:950; color:#991B1B; opacity:0.5; letter-spacing:2px; margin-bottom:25px; text-transform: uppercase;">Estimated Investment Range</div>
                    <div class="text-gradient" style="font-size:7.5rem; font-weight:950; color:#EF4444; line-height:1; letter-spacing:-0.07em;">$<span id="est-val">212,500</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:100px; font-size:24px; background:#EF4444; border-radius:30px; letter-spacing: 2px;" onclick="jQuery(\'#est-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">REQUEST ENGINEERING AUDIT</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#proj-type, #proj-sqft").on("change input", function() {
                    var rate = parseInt(jQuery("#proj-type").val());
                    var sqft = parseInt(jQuery("#proj-sqft").val()) || 0;
                    jQuery("#est-val").text((rate * sqft).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Modernist Estate Realization',
            'post_content' => 'Full architectural renovation and structural engineering realization for a 15,000 sqft premium estate. This project utilized the v6.3 Precision Quotation Engine for absolute material accuracy and established a high-authority design-to-build protocol that reduced project latency by 115% compared to sector benchmarks.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+115%');
            update_post_meta($id, '_gp_pipeline_value', '$3.8M');
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Elite Architectural Engineering Hub',
            'post_content' => 'High-fidelity structural auditing and precision architectural blueprinting for high-ticket residential builds. Our engineering nodes leverage neural estimation logic to provide absolute material transparency and eliminate the risk of structural realizing deltas.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '📐');
        }
    }
}
new GrowthPress_Contractor();
