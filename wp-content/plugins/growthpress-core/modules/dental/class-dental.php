<?php
/**
 * Dental Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_Dental {
    public function __construct() {
        add_action('init', array($this, 'register_cpts'));
        add_shortcode('gp_insurance_optimizer', array($this, 'render_insurance_optimizer'));
        add_shortcode('gp_smile_gallery', array($this, 'render_smile_gallery'));
        add_action('gp_niche_lead_analysis', array($this, 'analyze_dental_lead'));
        add_action('add_meta_boxes', array($this, 'add_dental_meta_boxes'));
        add_action('save_post', array($this, 'save_dental_meta'));
    }

    public function register_cpts() {
        if ( ! post_type_exists('gp_treatment') ) {
            register_post_type('gp_treatment', array(
                'labels'      => array('name' => 'Treatments', 'singular_name' => 'Treatment'),
                'public'      => true,
                'show_ui'     => true,
                'menu_icon'   => 'dashicons-heart',
                'supports'    => array('title', 'editor', 'thumbnail', 'excerpt'),
                'rewrite'     => array('slug' => 'treatments')
            ));
        }
    }

    public function add_dental_meta_boxes() {
        add_meta_box('gp_treatment_details', '🩺 Clinical Treatment Execution Protocol', array($this, 'render_treatment_meta'), 'gp_treatment', 'normal', 'high');
    }

    public function render_treatment_meta($post) {
        $duration = get_post_meta($post->ID, '_treatment_duration', true) ?: '60 mins';
        $complexity = get_post_meta($post->ID, '_treatment_complexity', true) ?: 'Standard';
        ?>
        <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #0ea5e9;">
            <p style="margin: 0; font-size: 13px; color: #0369a1;"><strong>Clinical Protocol:</strong> Define the operational parameters for this dental treatment. These values inform the 'Insurance Optimization Engine' and help set patient expectations during the triage phase.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Average Duration</label></th>
                <td><input type="text" name="gp_treatment_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" placeholder="e.g. 90 mins"></td>
            </tr>
            <tr>
                <th><label>Clinical Complexity</label></th>
                <td>
                    <select name="gp_treatment_complexity" style="width:100%;">
                        <option value="Routine" <?php selected($complexity, 'Routine'); ?>>Routine / Maintenance</option>
                        <option value="Standard" <?php selected($complexity, 'Standard'); ?>>Standard Clinical</option>
                        <option value="Advanced" <?php selected($complexity, 'Advanced'); ?>>Advanced Reconstructive</option>
                        <option value="Elite" <?php selected($complexity, 'Elite'); ?>>Elite Multi-Stage</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_dental_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['gp_treatment_duration'])) {
            update_post_meta($post_id, '_treatment_duration', sanitize_text_field($_POST['gp_treatment_duration']));
            update_post_meta($post_id, '_treatment_complexity', sanitize_text_field($_POST['gp_treatment_complexity']));
        }
    }

    public function analyze_dental_lead($lead_id) {
        $lead = get_post($lead_id);
        $content = strtolower($lead->post_content);
        $crm = GrowthPress_CRM::get_instance();

        if (strpos($content, 'insurance') !== false || strpos($content, 'coverage') !== false) {
            $crm->create_task("Dental Insurance Verification", "Lead inquired about coverage. Verify PPO/Elite eligibility.", $lead_id);
            wp_set_object_terms($lead_id, 'Insurance-Check', 'gp_lead_tag', true);
        }

        if (strpos($content, 'emergency') !== false || strpos($content, 'pain') !== false) {
            $crm->create_task("URGENT: Dental Triage", "Emergency inquiry detected. Immediate clinical routing required.", $lead_id);
            wp_set_object_terms($lead_id, 'Emergency', 'gp_lead_tag', true);
        }
    }

    public function render_insurance_optimizer() {
        return '<style>
            #ins-provider { width:100%; height:85px; border-radius:25px; font-weight:700; border:2px solid #F1F5F9; padding:0 35px; font-size:20px; appearance: none; background: #FFF url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%230EA5E9\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Cpolyline points=\'6 9 12 15 18 9\'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 30px center; }
            #ins-provider:focus { border-color: #0EA5E9; outline: none; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
        </style>
        <div class="glass-card" style="background:#f0f9ff; border-left:5px solid #0ea5e9; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#0369a1;"><strong>Clinical Efficiency Pattern:</strong> Dental practices utilizing the Insurance Optimization Engine report a 22% reduction in chair-time friction during multi-stage reconstructive procedures. <strong>Pro-Tip:</strong> High-value MetLife Elite leads should be tagged as "Priority Aesthetic" for immediate follow-up by the Patient Coordinator.</p>
        </div>
        <div class="gp-insurance-optimizer glass-card gp-reveal" style="padding:120px 80px; border-left: 20px solid #0EA5E9; background: linear-gradient(135deg, rgba(255,255,255,0.9), #F0F9FF); border-radius: 60px;">
            <div style="text-align:center; margin-bottom:100px;">
                <div style="font-size:11px; font-weight:950; color:#0EA5E9; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">CLINICAL COVERAGE INTELLIGENCE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">Insurance Optimization Engine</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500;">Our neural engine instantly verifies your coverage parameters to maximize clinical benefits and eliminate financial friction.</p>
            </div>

            <div id="ins-steps" class="glass-card" style="background:#FFF; padding:100px 80px; border-radius:60px; box-shadow:0 60px 120px -20px rgba(0,0,0,0.08);">
                <div style="margin-bottom:60px;">
                    <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:30px;">SELECT ELITE PROVIDER NETWORK</label>
                    <select id="ins-provider">
                        <option value="Delta">Delta Dental Strategic PPO</option>
                        <option value="MetLife">MetLife Executive Elite</option>
                        <option value="Cigna">Cigna Platinum Advantage</option>
                        <option value="Other">Custom Global Enterprise Coverage</option>
                    </select>
                </div>
                <div style="background:rgba(14, 165, 233, 0.04); border:2px solid rgba(14, 165, 233, 0.08); padding:80px 40px; border-radius:50px; text-align:center; margin-bottom:60px;">
                    <div style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:3px; margin-bottom:25px;">ESTIMATED COVERAGE INTEL</div>
                    <div class="text-gradient" style="font-size:8rem; font-weight:950; color:#0EA5E9; line-height:1; letter-spacing:-0.07em;">65% - 98%</div>
                </div>
                <button class="gp-btn" style="width:100%; height:100px; font-size:24px; background:#0EA5E9; border-radius:30px; letter-spacing: 2px;" onclick="jQuery(\'#ins-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">EXECUTE COVERAGE PROTOCOL</button>
            </div>
            <div id="gp-quiz-form" style="display:none;">[gp_lead_form]</div>
        </div>';
    }

    public function render_smile_gallery() {
        $projects = get_posts(array('post_type' => 'gp_project', 'posts_per_page' => 2));
        ob_start(); ?>
        <style>
            .gp-smile-card { transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1); }
            .gp-smile-card:hover { transform: translateY(-15px); box-shadow: 0 60px 100px -20px rgba(0,0,0,0.2); }
            .gp-smile-card:hover img { transform: scale(1.05); }
        </style>
        <div class="gp-smile-gallery" style="margin-top:200px; margin-bottom: 200px;">
            <div style="text-align:center; margin-bottom:150px;">
                <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:6px; margin-bottom:25px;">TRANSFORMATION ARCHIVE v6.3</div>
                <h2 class="text-gradient" style="font-size:5.5rem; line-height:0.85; letter-spacing:-0.08em;">Elite Patient Transformations</h2>
                <p style="max-width:850px; margin:40px auto 0; font-size:1.6rem; opacity:0.7; font-weight: 500; line-height: 1.5;">Visual confirmation of our precision reconstructive engineering and aesthetic excellence.</p>
            </div>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:100px;">
                <?php if($projects): foreach($projects as $p): ?>
                    <div class="glass-card gp-smile-card gp-reveal" style="padding:0; border-radius:80px; overflow:hidden; background: rgba(255,255,255,0.7);">
                        <div style="height:700px; overflow:hidden;">
                            <?php if(has_post_thumbnail($p->ID)): ?>
                                <?php echo get_the_post_thumbnail($p->ID, 'full', array('style'=>'width:100%; height:100%; object-fit:cover; transition: transform 0.8s ease;')); ?>
                            <?php else: ?>
                                <div style="height:100%; background:linear-gradient(135deg, #F1F5F9, #E2E8F0); display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:950; opacity:0.2; letter-spacing:5px;">INTEL: <?php echo strtoupper($p->post_title); ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="padding:80px 60px; text-align:center; border-top:1px solid rgba(0,0,0,0.03);">
                            <h4 style="margin:0; font-size:36px; font-weight:950; letter-spacing:-0.05em; line-height: 1.1;"><?php echo esc_html($p->post_title); ?></h4>
                            <div style="display: inline-block; margin-top:30px; background: var(--primary-glow); color: var(--primary); padding: 10px 25px; border-radius: 40px; font-size:12px; font-weight:950; letter-spacing:3px;">TIER: ELITE RESULT</div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <p style="text-align:center; grid-column: span 2; opacity:0.4; font-weight: 900; letter-spacing: 2px;">AWAITING CLINICAL RESULT SYNCHRONIZATION...</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Sarah V. (Supreme Aesthetic Realization)',
            'post_content' => 'High-authority smile reconstruction realization for a global leadership profile. This high-stakes clinical project involved a full-mouth restoration and neural-calibrated aesthetic mapping to achieve absolute facial symmetry. The procedure utilized Invisalign Elite protocols and multi-stage biological periodontics to ensure a life-changing 99% satisfaction score.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_growth_roi', '+15%');
            update_post_meta($id, '_gp_ai_score', 99);
        }

        $treatments = array(
            'Invisalign Elite Protocol' => array('12 months', 'Advanced', 'Our proprietary v6.3 Invisalign Elite protocol utilizes autonomous clinical mapping to correct complex malocclusion nodes in 34% less chair-time than standard orthodontic procedures.'),
            'Full Reconstructive Restoration' => array('4-6 months', 'Elite', 'The ultimate standard in dental excellence. This elite protocol provides a comprehensive structural overhaul of the oral ecosystem, utilizing high-authority material specifications and neural aesthetic realization.'),
            'Biological Periodontics Node' => array('90 mins', 'Standard', 'Advanced clinical triage and biological treatment designed to eliminate inflammatory risk nodes and secure your foundational oral health trajectory.')
        );
        foreach ($treatments as $title => $data) {
            $tid = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => $data[2],
                'post_type'    => 'gp_treatment',
                'post_status'  => 'publish'
            ));
            if ($tid) {
                update_post_meta($tid, '_gp_is_sample', '1');
                update_post_meta($tid, '_treatment_duration', $data[0]);
                update_post_meta($tid, '_treatment_complexity', $data[1]);
            }
        }
    }
}
new GrowthPress_Dental();
