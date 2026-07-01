<?php
/**
 * Accounting Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_Accounting {
    public function __construct() {
        add_shortcode('gp_tax_estimator', array($this, 'render_tax_estimator'));
        add_shortcode('gp_tax_audit', array($this, 'render_tax_audit'));
        add_shortcode('gp_tax_savings_ledger', array($this, 'render_savings_ledger'));
    }

    public function render_savings_ledger() {
        $leads = get_posts(array('post_type' => 'gp_lead', 'posts_per_page' => 5, 'meta_key' => '_gp_is_sample', 'meta_value' => '1'));
        ob_start(); ?>
        <div class="gp-tax-ledger glass-card gp-reveal" style="padding:80px; border-radius: 60px; background: rgba(255,255,255,0.9);">
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:60px;">
                <div>
                    <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:5px; margin-bottom:15px;">AUTHENTICATED FISCAL DATA</div>
                    <h3 style="margin:0; font-size: 32px; letter-spacing: -0.04em; font-weight: 950;">Tax Preservation Ledger</h3>
                </div>
                <div style="background:var(--secondary); color:white; padding:10px 25px; border-radius:15px; font-size:11px; font-weight:950; letter-spacing: 1px;">LEDGER: v6.3 FINAL</div>
            </div>
            <div style="border: 1px solid #F1F5F9; border-radius: 30px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <table style="width:100%; border-collapse:collapse; background: #FFF;">
                    <thead>
                        <tr style="text-align:left; background: #F8FAFC;">
                            <th style="padding:25px 35px; font-size:11px; font-weight: 950; opacity:0.5; letter-spacing: 2px;">ENTITY NODE</th>
                            <th style="padding:25px 35px; font-size:11px; font-weight: 950; opacity:0.5; letter-spacing: 2px;">OPTIMIZATION STRATEGY</th>
                            <th style="padding:25px 35px; font-size:11px; font-weight: 950; opacity:0.5; letter-spacing: 2px; text-align:right;">EQUITY PRESERVATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($leads as $l):
                            $prob = get_post_meta($l->ID, '_gp_ai_probability', true) ?: 75;
                            $savings = $prob * 1450;
                            ?>
                            <tr style="border-bottom:1px solid #F1F5F9;">
                                <td style="padding:30px 35px; font-weight:800; font-size: 16px; color: var(--secondary);"><?php echo esc_html($l->post_title); ?></td>
                                <td style="padding:30px 35px; font-size:14px; font-weight: 600; opacity:0.7;">Corporate Architecture Redesign</td>
                                <td style="padding:30px 35px; text-align:right; font-weight:950; color:#10B981; font-size: 18px;">$<?php echo number_format($savings); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background: #F8FAFC;">
                            <td colspan="2" style="padding:25px 35px; font-weight: 950; font-size: 12px; letter-spacing: 1px;">AGGREGATE SECTOR SAVINGS</td>
                            <td style="padding:25px 35px; text-align:right; font-weight:950; font-size: 20px; color: var(--primary);">$<?php
                                $total = 0; foreach($leads as $l) { $total += (get_post_meta($l->ID, '_gp_ai_probability', true) ?: 75) * 1450; }
                                echo number_format($total);
                            ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div style="margin-top:40px; display: flex; justify-content: center; gap: 40px; opacity: 0.3; font-weight: 950; font-size: 10px; letter-spacing: 3px;">
                <span>ENCRYPTION: AES-256</span>
                <span>PROTOCOL: FISCAL-X</span>
                <span>STATUS: VERIFIED</span>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_tax_audit() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        return '<style>
            .gp-tax-audit input, .gp-tax-audit select, .gp-tax-audit textarea { width:100%; background: #FFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 0 25px; transition: all 0.3s ease; font-weight: 700; height: 75px; }
            .gp-tax-audit textarea { padding: 25px; height: 200px; line-height: 1.7; }
            .gp-tax-audit input:focus, .gp-tax-audit select:focus, .gp-tax-audit textarea:focus { border-color: #7C3AED; outline: none; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1); }
        </style>
        <div class="gp-tax-audit glass-card gp-reveal" style="border-left: 20px solid #7C3AED; padding:120px 80px; background: linear-gradient(135deg, rgba(255,255,255,0.9), #F5F3FF); border-radius: 60px;">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:11px; font-weight:950; color:#7C3AED; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">FISCAL INTELLIGENCE NODE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">Secure Tax Strategy Audit</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500; line-height: 1.5;">Submit your corporate profile for a proprietary AI tax optimization analysis.</p>
            </div>
            <form class="gp-form" data-action="gp_submit_lead">
                <input type="hidden" name="nonce" value="'.$nonce.'">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:35px; margin-bottom:35px;">
                    <div><label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">ENTITY IDENTITY</label><input type="text" name="lead_name" placeholder="Business or Legal Name" required></div>
                    <div><label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">SECURE CHANNEL</label><input type="email" name="lead_email" placeholder="direct@enterprise.com" required></div>
                </div>
                <div style="margin-bottom:35px;">
                    <label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">ANNUAL REVENUE TIER</label>
                    <select name="lead_msg_prefix">
                        <option value="Tier1">$250k - $1M</option>
                        <option value="Tier2">$1M - $5M</option>
                        <option value="Tier3">$5M - $20M</option>
                        <option value="Tier4">$20M+</option>
                    </select>
                </div>
                <div style="margin-bottom:45px;">
                    <label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">FISCAL OBJECTIVES</label>
                    <textarea name="lead_msg" placeholder="Describe current fiscal challenges or specific optimization goals..."></textarea>
                </div>
                <button type="submit" class="gp-btn" style="width:100%; height:95px; font-size:22px; background:#7C3AED; border-radius: 25px; letter-spacing: 2px;">INITIALIZE FISCAL AUDIT</button>
            </form>
        </div>';
    }

    public function render_tax_estimator() {
        return '<style>
            #rev-select { width:100%; height:85px; border-radius:25px; font-weight:700; border:2px solid #F1F5F9; padding:0 35px; font-size:20px; appearance: none; background: #FFF url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%237C3AED\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 30px center; transition: all 0.3s ease; }
            #rev-select:focus { border-color: #7C3AED; outline: none; box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1); }
        </style>
        <div class="gp-tax-estimator glass-card gp-reveal" style="border-right: 20px solid #7C3AED; padding:120px 80px; background: linear-gradient(135deg, rgba(255,255,255,0.9), #F5F3FF); border-radius: 60px;">
            <div style="text-align:center; margin-bottom:100px;">
                <div style="font-size:11px; font-weight:950; color:#7C3AED; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">WEALTH PRESERVATION ENGINE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">AI Tax Optimization Estimator</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500; line-height: 1.5;">Determine your potential tax optimization nodes and capital preservation benefits based on your current corporate profile.</p>
            </div>

            <div id="tax-steps" class="glass-card" style="background:#FFF; padding:100px 80px; border-radius:60px; box-shadow:0 60px 120px -20px rgba(0,0,0,0.1);">
                <div style="margin-bottom:60px;">
                    <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:30px;">ESTIMATED ANNUAL REVENUE</label>
                    <select id="rev-select">
                        <option value="18000">$250k - $750k</option>
                        <option value="65000">$750k - $3M</option>
                        <option value="215000">$3M - $15M</option>
                        <option value="480000">$15M+</option>
                    </select>
                </div>
                <div style="background:rgba(124, 58, 237, 0.05); border:2px solid rgba(124, 58, 237, 0.1); padding:80px 40px; border-radius:50px; text-align:center; margin-bottom:70px;">
                    <div style="font-size:11px; font-weight:950; color:#7C3AED; opacity:0.5; letter-spacing:2px; margin-bottom:25px; text-transform: uppercase;">Estimated Savings Potential</div>
                    <div class="text-gradient" style="font-size:8rem; font-weight:950; color:#7C3AED; line-height:1; letter-spacing:-0.07em;">$<span id="tax-savings">18,000</span></div>
                </div>
                <button class="gp-btn" style="width:100%; height:100px; font-size:24px; background:#7C3AED; border-radius:30px; letter-spacing: 2px;" onclick="jQuery(\'#tax-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">SECURE HIGH-STAKES FINANCIAL AUDIT</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
            <script>
                jQuery("#rev-select").on("change", function() {
                    jQuery("#tax-savings").text(parseInt(jQuery(this).val()).toLocaleString());
                });
            </script>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Fortune 500 Fiscal Realization',
            'post_content' => 'Complete realization of corporate tax restructuring and multi-jurisdictional capital optimization for a global entity. Our partners utilized the v6.3 Wealth Preservation Engine to identify $12.5M in reclaimable capital nodes and established high-fidelity fiscal reporting across 5 international hubs.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+42%');
            update_post_meta($id, '_gp_pipeline_value', '$12.5M');
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Corporate Tax Shielding Node',
            'post_content' => 'Elite fiscal strategy and architectural restructuring designed for maximum asset protection and multi-jurisdictional tax optimization. This service utilizes the v6.3 Wealth Preservation Engine to identify hidden fiscal deltas and secure your long-term capital trajectory.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '🛡️');
        }
    }
}
new GrowthPress_Accounting();
