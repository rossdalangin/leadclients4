<?php
/**
 * GrowthPress Shortcode Reference Page
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Shortcode_Ref {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_shortcode_menu' ) );
    }

    public function add_shortcode_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Shortcode Library',
            'Shortcodes',
            'manage_options',
            'growthpress-shortcodes',
            array( $this, 'render_shortcodes_page' )
        );
    }

    public function render_shortcodes_page() {
        $shortcodes = array(
            array(
                'tag'   => '[gp_lead_form]',
                'title' => 'Standard Lead Capture',
                'desc'  => 'Displays the primary high-converting lead form with AI spam filtering.',
                'usage' => 'Place on Landing Pages or your Contact page.',
                'example' => '[gp_lead_form]',
                'category' => 'Lead Gen'
            ),
            array(
                'tag'   => '[gp_quiz_lead_form]',
                'title' => 'Conversational AI Quiz',
                'desc'  => 'A multi-step quiz to qualify high-ticket leads before capture.',
                'usage' => 'Best used on the Homepage Hero or a dedicated Triage page.',
                'example' => '[gp_quiz_lead_form]',
                'category' => 'Lead Gen'
            ),
            array(
                'tag'   => '[gp_booking_form]',
                'title' => 'Appointment Scheduling',
                'desc'  => 'Displays the staff-aware calendar for booking discovery calls.',
                'usage' => 'Place on Services pages or after a lead is captured.',
                'example' => '[gp_booking_form]',
                'category' => 'Conversion'
            ),
            array(
                'tag'   => '[gp_solar_calculator]',
                'title' => 'Solar ROI Estimator',
                'desc'  => 'Interactive calculator for solar niches with AI energy consulting.',
                'usage' => 'Specific to Solar niche companies.',
                'example' => '[gp_solar_calculator]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_solar_financing]',
                'title' => 'Solar Financing Form',
                'desc'  => 'Secure inquiry form for $0-down solar financing eligibility.',
                'usage' => 'Place on Solar pricing or financing pages.',
                'example' => '[gp_solar_financing]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_insurance_optimizer]',
                'title' => 'Dental Insurance Optimizer',
                'desc'  => 'Frontend tool for patients to check insurance coverage and maximize benefits.',
                'usage' => 'Place on Dental intake or pricing pages.',
                'example' => '[gp_insurance_optimizer]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_contractor_estimator]',
                'title' => 'Construction Cost Estimator',
                'desc'  => 'Precision labor and material calculator for renovation niches.',
                'usage' => 'Specific to Contractor/Roofing niches.',
                'example' => '[gp_contractor_estimator]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_urgency_banner]',
                'title' => 'Dynamic Urgency Alert',
                'desc'  => 'Displays a niche-specific alert banner (e.g. Emergency Dental available).',
                'usage' => 'Place at the very top of your site or hero sections.',
                'example' => '[gp_urgency_banner]',
                'category' => 'Conversion'
            ),
            array(
                'tag'   => '[gp_kb_grid]',
                'title' => 'Knowledge Base Grid',
                'desc'  => 'Displays a high-fidelity grid of technical KB articles.',
                'usage' => 'Place on documentation or support pages.',
                'example' => '[gp_kb_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_case_study_grid]',
                'title' => 'Success Story Grid',
                'desc'  => 'Displays a grid of high-ticket Case Studies and Projects.',
                'usage' => 'Place on Portfolio or Results pages.',
                'example' => '[gp_case_study_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_service_grid]',
                'title' => 'Service Lines Grid',
                'desc'  => 'Displays a grid of your elite service offerings.',
                'usage' => 'Place on the Services overview page.',
                'example' => '[gp_service_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_inventory_grid]',
                'title' => 'Portfolio Inventory Grid',
                'desc'  => 'Displays a luxury grid of inventory items (e.g. Properties).',
                'usage' => 'Specific to Real Estate or Asset-heavy niches.',
                'example' => '[gp_inventory_grid]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_location_grid]',
                'title' => 'Location Network Grid',
                'desc'  => 'Displays a grid of all physical service locations.',
                'usage' => 'Place on the Locations or About page.',
                'example' => '[gp_location_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_funnel_grid]',
                'title' => 'Active Funnels Overview',
                'desc'  => 'Displays a grid of active conversion funnels.',
                'usage' => 'Mainly for internal landing page management.',
                'example' => '[gp_funnel_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_medical_intake]',
                'title' => 'Clinical Intake Node',
                'desc'  => 'HIPAA-compliant patient onboarding form with neural triage.',
                'usage' => 'Specific to Medical niche.',
                'example' => '[gp_medical_intake]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_tax_audit]',
                'title' => 'Fiscal Strategy Audit',
                'desc'  => 'Secure corporate tax optimization intake form.',
                'usage' => 'Specific to Accounting niche.',
                'example' => '[gp_tax_audit]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_law_conflict_check]',
                'title' => 'Litigation Clearance Node',
                'desc'  => 'Secure conflict of interest verification form.',
                'usage' => 'Specific to Law niche.',
                'example' => '[gp_law_conflict_check]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_ai_faq]',
                'title' => 'AI FAQ Assistant',
                'desc'  => 'Context-aware AI chatbot that answers questions based on your KB.',
                'usage' => 'Place on documentation, support, or service pages.',
                'example' => '[gp_ai_faq]',
                'category' => 'AI Intelligence'
            ),
            array(
                'tag'   => '[gp_staff_grid]',
                'title' => 'Specialist Team Grid',
                'desc'  => 'Displays all active specialists with performance radar metrics.',
                'usage' => 'Place on Team or About pages.',
                'example' => '[gp_staff_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_treatment_grid]',
                'title' => 'Clinical Protocols Grid',
                'desc'  => 'Displays specialized treatment protocols for medical/dental niches.',
                'usage' => 'Place on Services or Clinical hubs.',
                'example' => '[gp_treatment_grid]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_market_chart]',
                'title' => 'Strategic ROI Chart',
                'desc'  => 'Interactive Chart.js visualization of market trajectory and ROI.',
                'usage' => 'Best on Strategy or Results pages.',
                'example' => '[gp_market_chart]',
                'category' => 'Conversion'
            ),
            array(
                'tag'   => '[gp_trust_badges]',
                'title' => 'Elite Authority Logos',
                'desc'  => 'Displays high-authority trust badges (Forbes, Bloomberg, etc.).',
                'usage' => 'Place in footers or below hero sections.',
                'example' => '[gp_trust_badges]',
                'category' => 'Conversion'
            ),
            array(
                'tag'   => '[gp_stats_bar]',
                'title' => 'Financial Performance Bar',
                'desc'  => 'Displays impressive system-wide performance statistics.',
                'usage' => 'Place on About or Results pages.',
                'example' => '[gp_stats_bar]',
                'category' => 'Conversion'
            ),
            array(
                'tag'   => '[gp_client_portal]',
                'title' => 'Secure Client Portal',
                'desc'  => 'The main entry point for clients to manage projects and assets.',
                'usage' => 'Place on a dedicated /portal page.',
                'example' => '[gp_client_portal]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_coaching_assistant]',
                'title' => 'Scaling Roadmap Engine',
                'desc'  => 'AI-powered tool to identify bottlenecks and generate 12-month roadmaps.',
                'usage' => 'Specific to Coaching niche.',
                'example' => '[gp_coaching_assistant]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_smile_gallery]',
                'title' => 'Clinical Result Gallery',
                'desc'  => 'High-fidelity grid of before/after clinical transformations.',
                'usage' => 'Specific to Dental niche.',
                'example' => '[gp_smile_gallery]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_roofing_estimator]',
                'title' => 'Structural Audit Estimator',
                'desc'  => 'Drone-aware cost estimator for roofing and structural projects.',
                'usage' => 'Specific to Roofing niche.',
                'example' => '[gp_roofing_estimator]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_consulting_audit]',
                'title' => 'Strategic Efficiency Audit',
                'desc'  => 'High-stakes operational audit intake for enterprise firms.',
                'usage' => 'Specific to Consulting niche.',
                'example' => '[gp_consulting_audit]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_symptom_checker]',
                'title' => 'AI Health Intelligence',
                'desc'  => 'Neural triage tool for clinical symptom mapping.',
                'usage' => 'Specific to Medical niche.',
                'example' => '[gp_symptom_checker]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_tax_estimator]',
                'title' => 'Wealth Preservation Engine',
                'desc'  => 'AI tax savings estimator for corporate and family office niches.',
                'usage' => 'Specific to Accounting niche.',
                'example' => '[gp_tax_estimator]',
                'category' => 'Niche Tools'
            ),
            array(
                'tag'   => '[gp_location_switcher]',
                'title' => 'Regional Node Switcher',
                'desc'  => 'Allows visitors to select their nearest strategic service hub.',
                'usage' => 'Place in sidebars or footers.',
                'example' => '[gp_location_switcher]',
                'category' => 'Ecosystem'
            ),
            array(
                'tag'   => '[gp_kb_search]',
                'title' => 'Intelligence Search Hub',
                'desc'  => 'AI-powered search interface for the technical Knowledge Base.',
                'usage' => 'Place on KB Hub or support pages.',
                'example' => '[gp_kb_search]',
                'category' => 'AI Intelligence'
            )
        );
        ?>
        <div class="wrap growthpress-shortcodes gp-reveal">
            <div class="glass-card" style="background:#fdf4ff; border-left:5px solid #a855f7; margin-bottom:30px;">
                <p style="margin:0; font-size:14px; color:#7e22ce;"><strong>Shortcode Deployment:</strong> Shortcodes are the building blocks of your front-end ecosystem. You can deploy them in any page builder, widget, or post to inject real-time business intelligence and capture nodes. <strong>Pro-Tip:</strong> Use the [gp_ai_faq] on high-traffic service pages to reduce support overhead by 60%.</p>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding:30px; background:rgba(255,255,255,0.6); border-radius:30px; border:1px solid var(--glass-border);">
                <div>
                    <h1 style="margin:0;">Shortcode Strategic Repository</h1>
                    <p style="opacity:0.6; margin:5px 0 0 0;">Deploy high-stakes Business OS modules anywhere in your ecosystem.</p>
                </div>
                <div style="background:var(--secondary); color:white; padding:8px 16px; border-radius:30px; font-size:11px; font-weight:950; letter-spacing:1px;">ELITE v6.3</div>
            </div>

            <div class="shortcode-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:30px; margin-top:20px;">
                <?php foreach($shortcodes as $s): ?>
                    <div class="glass-card gp-shortcode-item" style="padding:40px; border-radius:30px; position:relative; transition: all 0.3s ease;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px;">
                            <div style="background:var(--primary-glow); color:var(--primary); padding:6px 15px; border-radius:10px; font-size:10px; font-weight:950; letter-spacing:1px;"><?php echo strtoupper($s['category']); ?></div>
                            <button class="button button-small" onclick="copyToClipboard('<?php echo esc_js($s['tag']); ?>')">COPY TAG</button>
                        </div>
                        <code style="font-size:20px; color:var(--primary); font-weight:900; background:transparent; padding:0;"><?php echo $s['tag']; ?></code>
                        <h3 style="margin:15px 0 10px; font-size:22px; letter-spacing:-0.02em;"><?php echo $s['title']; ?></h3>
                        <p style="opacity:0.7; font-size:14px; line-height:1.6; margin-bottom:30px;"><?php echo $s['desc']; ?></p>
                        <div style="background:rgba(0,0,0,0.03); padding:20px; border-radius:20px; font-size:12px; line-height:1.5;">
                            <strong style="display:block; margin-bottom:5px; text-transform:uppercase; font-size:10px; opacity:0.5; letter-spacing:1px;">Deployment Instruction</strong>
                            <?php echo $s['usage']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Shortcode node synchronized to clipboard.');
            });
        }
        </script>
        <style>
        .gp-shortcode-item:hover { transform: translateY(-5px); border-color: var(--primary); box-shadow: 0 30px 60px -12px rgba(50, 50, 93, 0.15); }
        </style>
        <?php
    }
}

new GrowthPress_Shortcode_Ref();
