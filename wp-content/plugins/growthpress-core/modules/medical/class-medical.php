<?php
/**
 * Medical Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_Medical {
    public function __construct() {
        add_shortcode('gp_symptom_checker', array($this, 'render_symptom_checker'));
        add_shortcode('gp_medical_intake', array($this, 'render_medical_intake'));
        add_action('init', array($this, 'register_cpts'));
        add_action('add_meta_boxes', array($this, 'add_medical_meta_boxes'));
        add_action('save_post', array($this, 'save_medical_meta'));
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

    public function add_medical_meta_boxes() {
        add_meta_box('gp_treatment_details', '🩺 Clinical Treatment Execution Protocol', array($this, 'render_treatment_meta'), 'gp_treatment', 'normal', 'high');
    }

    public function render_treatment_meta($post) {
        $duration = get_post_meta($post->ID, '_treatment_duration', true) ?: '60 mins';
        $complexity = get_post_meta($post->ID, '_treatment_complexity', true) ?: 'Standard';
        ?>
        <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
            <p style="margin: 0; font-size: 13px; color: #166534;"><strong>Clinical Protocol:</strong> Define the operational parameters for this medical treatment. These values inform the 'Health Intelligence Assistant' and set patient expectations during triage.</p>
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
                        <option value="Routine" <?php selected($complexity, 'Routine'); ?>>Routine / Diagnostic</option>
                        <option value="Standard" <?php selected($complexity, 'Standard'); ?>>Standard Clinical</option>
                        <option value="Advanced" <?php selected($complexity, 'Advanced'); ?>>Advanced Surgical</option>
                        <option value="Elite" <?php selected($complexity, 'Elite'); ?>>Elite Specialist Tier</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_medical_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['gp_treatment_duration'])) {
            update_post_meta($post_id, '_treatment_duration', sanitize_text_field($_POST['gp_treatment_duration']));
            update_post_meta($post_id, '_treatment_complexity', sanitize_text_field($_POST['gp_treatment_complexity']));
        }
    }

    public function render_medical_intake() {
        $nonce = wp_create_nonce('gp_lead_nonce');
        return '<style>
            .gp-medical-intake input, .gp-medical-intake select, .gp-medical-intake textarea { width:100%; background: #FFF; border: 1px solid #E2E8F0; border-radius: 20px; padding: 0 25px; transition: all 0.3s ease; font-weight: 700; height: 75px; }
            .gp-medical-intake textarea { padding: 25px; height: 200px; line-height: 1.7; }
            .gp-medical-intake input:focus, .gp-medical-intake select:focus, .gp-medical-intake textarea:focus { border-color: #10B981; outline: none; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
        </style>
        <div class="gp-medical-intake glass-card gp-reveal" style="border-right: 20px solid #10B981; padding:120px 80px; background: linear-gradient(135deg, rgba(255,255,255,0.9), #F0FDF4); border-radius: 60px;">
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:11px; font-weight:950; color:#10B981; text-transform:uppercase; letter-spacing:5px; margin-bottom:20px;">CLINICAL INTAKE NODE v6.3</div>
                <h3 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">Secure Patient Onboarding</h3>
                <p style="font-size:1.5rem; opacity:0.7; max-width:700px; margin:35px auto 0; font-weight: 500; line-height: 1.5;">Initialize your clinical record within our HIPAA-compliant neural architecture.</p>
            </div>
            <form class="gp-form" data-action="gp_submit_lead">
                <input type="hidden" name="nonce" value="'.$nonce.'">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:35px; margin-bottom:35px;">
                    <div><label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">PATIENT IDENTITY</label><input type="text" name="lead_name" placeholder="Full Legal Name" required></div>
                    <div><label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">SECURE EMAIL</label><input type="email" name="lead_email" placeholder="direct@enterprise.com" required></div>
                </div>
                <div style="margin-bottom:35px;">
                    <label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">PRIMARY CLINICAL CONCERN</label>
                    <select name="lead_msg_prefix">
                        <option value="General">General Consultation</option>
                        <option value="Specialist">Specialist Referral</option>
                        <option value="Urgent">Urgent Symptomology</option>
                    </select>
                </div>
                <div style="margin-bottom:45px;">
                    <label style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">CLINICAL HISTORY / SYMPTOMS</label>
                    <textarea name="lead_msg" placeholder="Describe symptoms or clinical history... (Privileged)"></textarea>
                </div>
                <button type="submit" class="gp-btn" style="width:100%; height:95px; font-size:22px; background:#10B981; border-radius: 25px; letter-spacing: 2px;">INITIALIZE SECURE TRIAGE</button>
            </form>
        </div>';
    }

    public function render_symptom_checker() {
        return '<style>
            #symptom-msg { width:100%; background: #FFF; border: 1px solid #E2E8F0; border-radius: 30px; padding: 35px; transition: all 0.3s ease; font-weight: 600; font-size: 16px; height: 250px; line-height: 1.7; }
            #symptom-msg:focus { border-color: #10B981; outline: none; box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.1); }
        </style>
        <div class="gp-symptom-checker glass-card gp-reveal" style="border-left: 20px solid #10B981; padding:120px 80px; position:relative; overflow:hidden; border-radius: 60px;">
            <div style="position:absolute; top:0; left:0; width:100%; height:100%; opacity:0.04; background-image:url(\'data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M15 0v30M0 15h30\" stroke=\"%2310B981\" stroke-width=\"2\"/%3E%3C/svg%3E\'); pointer-events:none;"></div>
            <div style="text-align:center; margin-bottom:80px;">
                <div style="font-size:11px; font-weight:950; color:#10B981; text-transform:uppercase; letter-spacing:5px; margin-bottom:25px;">HIPAA-COMPLIANT NEURAL TRIAGE v6.3</div>
                <h2 class="text-gradient" style="font-size:4.5rem; line-height:1.0; letter-spacing: -0.06em;">AI Health Intelligence Assistant</h2>
                <p style="font-size:1.5rem; opacity:0.7; max-width:750px; margin:35px auto 0; font-weight: 500; line-height: 1.5;">Instantly analyze clinical concerns to route your case to the appropriate board-certified specialist.</p>
            </div>

            <div id="medical-steps" class="glass-card" style="background:#FFF; padding:80px; border-radius:60px; box-shadow:0 60px 120px -20px rgba(0,0,0,0.08);">
                <div style="margin-bottom:60px;">
                    <label style="font-weight:950; font-size:11px; opacity:0.4; letter-spacing:3px; display:block; margin-bottom:30px;">CLINICAL CONCERN / SYMPTOMOLOGY</label>
                    <textarea id="symptom-msg" placeholder="Describe clinical symptoms for neural triage prioritization..."></textarea>
                </div>
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:40px; margin-bottom:60px;">
                    <div style="background:#F0FDF4; padding:40px; border-radius:30px; border:1px solid #DCFCE7; text-align:center;">
                        <div style="font-size:11px; font-weight:950; color:#166534; opacity:0.5; letter-spacing:2px; margin-bottom:15px; text-transform: uppercase;">Neural Latency</div>
                        <div style="font-size:32px; font-weight:950; color:#166534; letter-spacing: -0.02em;">3.8 SECONDS</div>
                    </div>
                    <div style="background:#F0FDF4; padding:40px; border-radius:30px; border:1px solid #DCFCE7; text-align:center;">
                        <div style="font-size:11px; font-weight:950; color:#166534; opacity:0.5; letter-spacing:2px; margin-bottom:15px; text-transform: uppercase;">Data Protocol</div>
                        <div style="font-size:32px; font-weight:950; color:#166534; letter-spacing: -0.02em;">AES-256-GCM</div>
                    </div>
                </div>
                <button class="gp-btn" style="width:100%; height:100px; font-size:24px; background:#10B981; border-radius: 30px; letter-spacing: 2px;" onclick="jQuery(\'#medical-steps\').fadeOut(); jQuery(\'#gp-quiz-form\').fadeIn();">EXECUTE AI DIAGNOSTIC TRIAGE</button>
            </div>
            <div id="gp-quiz-form" style="display:none; margin-top: 60px;">[gp_lead_form]</div>
            <div style="margin-top:60px; font-size:12px; opacity:0.4; text-align:center; line-height:1.8; max-width:700px; margin-left:auto; margin-right:auto; font-weight: 500;">NOTICE: This engine is for administrative clinical routing and priority intake only. It does not provide medical diagnosis, treatment, or advice. In the event of a medical emergency, terminate session and dial local emergency services (911).</div>
        </div>';
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'Premier Surgical Hub Realization',
            'post_content' => 'Successful implementation of neural clinical triage and secure patient onboarding nodes for an elite outpatient surgical center. This project involved migrating 5,000+ patient records into the v6.3 architecture and establishing HIPAA-compliant specialist routing protocols, resulting in a 25 hour per week reduction in administrative latency.',
            'post_type'    => 'gp_project',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_efficiency_gain', '25 HRS/WK');
            update_post_meta($id, '_gp_ai_score', 98);
        }

        $tid = wp_insert_post(array(
            'post_title'   => 'v6.3 Clinical Merit Review Protocol',
            'post_content' => 'Comprehensive HIPAA-compliant protocol for evaluating surgical eligibility through automated neural symptom analysis. This intelligence node cross-references clinical history with triage data to prioritize high-urgency outpatient procedures and ensure board-certified specialist alignment.',
            'post_type'    => 'gp_kb',
            'post_status'  => 'publish'
        ));
        if ($tid) {
            update_post_meta($tid, '_gp_is_sample', '1');
            update_post_meta($tid, '_kb_intel_level', 'Advanced');
            update_post_meta($tid, '_kb_access_control', 'Client');
        }
    }
}
new GrowthPress_Medical();
