<?php
/**
 * GrowthPress Sample Data Management - Elite v6.3 Consolidated
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Sample_Data {

    public static function generate_all_sample_data() {
        $niche = get_option('growthpress_niche', 'business');

        // 0. Generate Strategic Sample Users
        self::generate_sample_users();

        // 1. Generate core CPT sample data
        $location_ids = self::generate_locations();
        $lead_ids = self::generate_leads($location_ids);
        $appt_ids = self::generate_appointments($lead_ids);
        $service_ids = self::generate_services();
        $proposal_ids = self::generate_proposals($lead_ids, $service_ids);
        $project_ids = self::generate_projects($lead_ids);
        self::generate_transactions($proposal_ids, $appt_ids);
        self::generate_funnels();
        self::generate_tasks($lead_ids);
        self::generate_kb();
        self::generate_inventory();
        self::generate_staff($lead_ids);
        self::generate_reviews($project_ids);
        self::generate_treatments();
        self::generate_chat_templates();

        // 2. Call niche-specific sample data
        $class_name = 'GrowthPress_' . str_replace(' ', '', ucwords(str_replace('-', ' ', $niche)));
        if ( class_exists($class_name) ) {
            $instance = new $class_name();
            if ( method_exists($instance, 'generate_sample_data') ) {
                $instance->generate_sample_data();
            }
        }

        // 3. Call Reputation sample data (already covered by generate_reviews but keeping for safety if it adds more)
        if ( class_exists('GrowthPress_Reputation') ) {
            $reputation = new GrowthPress_Reputation();
            if ( method_exists($reputation, 'generate_sample_data') ) {
                $reputation->generate_sample_data();
            }
        }

        // 4. Log the activity
        GrowthPress_Activity::log("Elite Ecosystem Sample Instantiation Completed.");
    }

    public static function generate_everything() {
        $original_niche = get_option('growthpress_niche', 'business');
        $niches = array('dental', 'law', 'contractor', 'roofing', 'solar', 'accounting', 'medical', 'real-estate', 'coaches', 'consultants');
        foreach($niches as $niche) {
            update_option('growthpress_niche', $niche);
            self::generate_all_sample_data();
        }
        update_option('growthpress_niche', $original_niche);
        GrowthPress_Activity::log("Global Multi-Niche Ecosystem Instantiated.");
    }

    public static function remove_all_sample_data() {
        $args = array(
            'post_type'      => array('gp_lead', 'gp_appointment', 'gp_property', 'gp_review', 'gp_proposal', 'gp_transaction', 'gp_funnel', 'gp_location', 'gp_task', 'gp_kb', 'gp_project', 'gp_service', 'gp_treatment', 'gp_staff', 'gp_chat', 'gp_chat_template'),
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'   => '_gp_is_sample',
                    'value' => '1',
                ),
            ),
            'post_status'    => 'any',
        );

        $sample_posts = get_posts($args);
        foreach ($sample_posts as $post) {
            wp_delete_post($post->ID, true);
        }

        // Remove Sample Users
        $users = array('gp_admin', 'gp_staff_1', 'gp_client_1');
        foreach($users as $u) {
            $user = get_user_by('login', $u);
            if($user) wp_delete_user($user->ID);
        }

        GrowthPress_Activity::log("Sample Intelligence Purge Completed.");
    }

    private static function generate_sample_users() {
        $users = array(
            array('login' => 'gp_admin', 'role' => 'administrator', 'email' => 'admin@growthpress.io'),
            array('login' => 'gp_staff_1', 'role' => 'editor', 'email' => 'staff@growthpress.io'),
            array('login' => 'gp_client_1', 'role' => 'subscriber', 'email' => 'client@growthpress.io')
        );

        foreach($users as $u) {
            if ( ! username_exists($u['login']) ) {
                $user_id = wp_insert_user(array(
                    'user_login' => $u['login'],
                    'user_pass'  => 'growthpress123',
                    'user_email' => $u['email'],
                    'role'       => $u['role'],
                    'display_name' => ucwords(str_replace('_', ' ', $u['login']))
                ));
            }
        }
    }

    private static function generate_leads($location_ids = array()) {
        $niche = get_option('growthpress_niche', 'business');
        $niche_data = array(
            'dental' => array(
                array('title' => 'Ethereal Smile Studio', 'content' => 'Requesting full-mouth reconstruction consultation. Interested in Invisalign Elite protocols and autonomous clinical mapping.', 'email' => 'dr.smile@ethereal.com', 'phone' => '555-DENT-01', 'zip' => '90210', 'source' => 'Neural Quiz', 'prob' => 88, 'stage' => 'qualified', 'tag' => 'Cosmetic', 'sentiment' => '{"urgency": 8, "profile": "Aesthetic-Focused Client"}'),
                array('title' => 'City Dental Center', 'content' => 'Emergency dental triage required for a high-volume clinic. Need AI-driven patient routing.', 'email' => 'ops@citydental.org', 'phone' => '555-DENT-02', 'zip' => '10001', 'source' => 'Organic Search', 'prob' => 65, 'stage' => 'new', 'tag' => 'Emergency', 'sentiment' => '{"urgency": 10, "profile": "Urgent Care Provider"}')
            ),
            'law' => array(
                array('title' => 'Sterling & Associates', 'content' => 'Multi-national merger requiring secure AI legal triage and conflict clearance. v6.3 Elite Protocol essential.', 'email' => 'john.sterling@law-global.com', 'phone' => '555-LAW-01', 'zip' => '90210', 'source' => 'Direct Triage', 'prob' => 95, 'stage' => 'qualified', 'tag' => 'Corporate', 'sentiment' => '{"urgency": 9, "profile": "High-Authority Challenger"}'),
                array('title' => 'Pacific Litigation Group', 'content' => 'High-stakes litigation support needed. Evaluating the v6.3 Merit Review Engine.', 'email' => 'contact@pacificlaw.io', 'phone' => '555-LAW-02', 'zip' => '94105', 'source' => 'LinkedIn', 'prob' => 72, 'stage' => 'new', 'tag' => 'Litigation', 'sentiment' => '{"urgency": 7, "profile": "Strategic Partner"}')
            ),
            'solar' => array(
                array('title' => 'Green Horizon Estates', 'content' => 'Full-scale residential solar deployment for 50+ luxury units. Need structural engineering and grid-independence modeling.', 'email' => 'dev@greenhorizon.com', 'phone' => '555-SOLAR-01', 'zip' => '92101', 'source' => 'Google Ads', 'prob' => 91, 'stage' => 'qualified', 'tag' => 'Residential', 'sentiment' => '{"urgency": 8, "profile": "Eco-Conscious Developer"}'),
                array('title' => 'Apex Industrial Solar', 'content' => 'Industrial energy audit for a manufacturing hub. Maximizing federal tax credit realization.', 'email' => 'energy@apex-industrial.com', 'phone' => '555-SOLAR-02', 'zip' => '60601', 'source' => 'Referral', 'prob' => 84, 'stage' => 'booked', 'tag' => 'Commercial', 'sentiment' => '{"urgency": 6, "profile": "Efficiency-Driven Director"}')
            ),
            'medical' => array(
                array('title' => 'Vance Medical Group', 'content' => 'Neural symptom triage node implementation for regional hub. 12 strategic locations to be integrated.', 'email' => 'ops@vance-medical.org', 'phone' => '555-MED-01', 'zip' => '10001', 'source' => 'Organic Search', 'prob' => 89, 'stage' => 'qualified', 'tag' => 'Enterprise', 'sentiment' => '{"urgency": 9, "profile": "Security-Seeking Implementer"}'),
                array('title' => 'Summit Specialist Clinic', 'content' => 'Reducing specialist administrative load with AI-driven patient intake. HIPAA compliance is mandatory.', 'email' => 'admin@summitclinic.io', 'phone' => '555-MED-02', 'zip' => '80202', 'source' => 'Direct Triage', 'prob' => 76, 'stage' => 'new', 'tag' => 'Specialist', 'sentiment' => '{"urgency": 7, "profile": "Tech-Forward Physician"}')
            ),
            'contractor' => array(
                array('title' => 'Highland Estate Overhaul', 'content' => 'Complete modernist transformation of a 12,000 sqft estate. Need structural engineering and luxury finish nodes.', 'email' => 'owner@highland.com', 'phone' => '555-BUILD-01', 'zip' => '90210', 'source' => 'Instagram', 'prob' => 82, 'stage' => 'qualified', 'tag' => 'Residential', 'sentiment' => '{"urgency": 7, "profile": "Luxe Homeowner"}'),
                array('title' => 'Metro Plaza Structural Audit', 'content' => 'Commercial structural integrity audit needed for Q4 property realization.', 'email' => 'facilities@metroplaza.io', 'phone' => '555-BUILD-02', 'zip' => '60601', 'source' => 'Direct Triage', 'prob' => 90, 'stage' => 'booked', 'tag' => 'Commercial', 'sentiment' => '{"urgency": 8, "profile": "Asset Manager"}')
            ),
            'roofing' => array(
                array('title' => 'Slate Protection Project', 'content' => 'Natural slate roof replacement for a historic manor. Drone-assisted audit required.', 'email' => 'manor@heritage.org', 'phone' => '555-ROOF-01', 'zip' => '02108', 'source' => 'Referral', 'prob' => 94, 'stage' => 'qualified', 'tag' => 'Elite', 'sentiment' => '{"urgency": 9, "profile": "Quality-First Owner"}'),
                array('title' => 'Industrial Metal Deployment', 'content' => 'Standing seam metal installation for 3 warehouses. Maximizing ROI and energy efficiency.', 'email' => 'logistics@industrial.com', 'phone' => '555-ROOF-02', 'zip' => '30303', 'source' => 'Google Search', 'prob' => 75, 'stage' => 'new', 'tag' => 'Commercial', 'sentiment' => '{"urgency": 6, "profile": "ROI-Focused CFO"}')
            ),
            'accounting' => array(
                array('title' => 'Global Tax Optimization', 'content' => 'Multi-jurisdictional fiscal restructuring for a Fortune 500 entity. R&D credit realization.', 'email' => 'cfo@globalcorp.com', 'phone' => '555-TAX-01', 'zip' => '10005', 'source' => 'Direct Triage', 'prob' => 98, 'stage' => 'qualified', 'tag' => 'Enterprise', 'sentiment' => '{"urgency": 10, "profile": "Precision Strategist"}'),
                array('title' => 'Wealth Preservation Audit', 'content' => 'High-net-worth family office requiring estate tax mitigation and capital realization.', 'email' => 'legacy@familyoffice.io', 'phone' => '555-TAX-02', 'zip' => '33139', 'source' => 'LinkedIn', 'prob' => 85, 'stage' => 'booked', 'tag' => 'Private', 'sentiment' => '{"urgency": 7, "profile": "Legacy-Focused Founder"}')
            ),
            'real-estate' => array(
                array('title' => 'Modernist Portfolio Acquisition', 'content' => 'Acquiring 5 off-market luxury nodes. Neural lifestyle matching required for appreciation delta.', 'email' => 'investor@capital.com', 'phone' => '555-RE-01', 'zip' => '90069', 'source' => 'Direct Triage', 'prob' => 92, 'stage' => 'qualified', 'tag' => 'Luxury', 'sentiment' => '{"urgency": 8, "profile": "High-Growth Investor"}'),
                array('title' => 'Estate Liquidation Strategy', 'content' => 'Divesting commercial equity hubs in the downtown district. Market delta analysis essential.', 'email' => 'trustee@liquid.org', 'phone' => '555-RE-02', 'zip' => '94104', 'source' => 'Referral', 'prob' => 78, 'stage' => 'new', 'tag' => 'Commercial', 'sentiment' => '{"urgency": 9, "profile": "Efficiency-Driven Trustee"}')
            ),
            'coaches' => array(
                array('title' => '7-Figure Scale Mentorship', 'content' => 'Transitioning from manual latency to autonomous realization. High-ticket authority building.', 'email' => 'founder@scaleup.io', 'phone' => '555-COACH-01', 'zip' => '78701', 'source' => 'Neural Quiz', 'prob' => 88, 'stage' => 'qualified', 'tag' => 'Scaling', 'sentiment' => '{"urgency": 8, "profile": "High-Performance Founder"}'),
                array('title' => 'Market Dominance Sequence', 'content' => 'Behavioral sales triage and elite operational auditing for a consulting firm.', 'email' => 'partner@eliteconsult.com', 'phone' => '555-COACH-02', 'zip' => '60611', 'source' => 'Direct Triage', 'prob' => 70, 'stage' => 'new', 'tag' => 'Authority', 'sentiment' => '{"urgency": 7, "profile": "Growth-Minded Leader"}')
            ),
            'consultants' => array(
                array('title' => 'Operational Efficiency Audit', 'content' => 'Full-scale architectural audit of digital ecosystem. Reducing lifecycle friction.', 'email' => 'ops@ent-tech.com', 'phone' => '555-CONS-01', 'zip' => '98101', 'source' => 'LinkedIn', 'prob' => 84, 'stage' => 'qualified', 'tag' => 'Efficiency', 'sentiment' => '{"urgency": 8, "profile": "Operational Strategist"}'),
                array('title' => 'Change Management Node', 'content' => 'Implementing neural lifecycle optimization for a 500-person firm. Q4 realization targets.', 'email' => 'hr@corp-pivot.io', 'phone' => '555-CONS-02', 'zip' => '10011', 'source' => 'Organic Search', 'prob' => 76, 'stage' => 'new', 'tag' => 'Lifecycle', 'sentiment' => '{"urgency": 6, "profile": "Structural Transformer"}')
            )
        );

        $leads = $niche_data[$niche] ?? array(
            array(
                'title' => 'Elite Strategic Partner',
                'content' => 'We require a high-authority business operating system to scale our high-ticket service firm. v6.3 Elite parameters are a must.',
                'email' => 'ceo@elite-strategy.com',
                'phone' => '555-0001',
                'zip' => '90210',
                'source' => 'Direct Triage',
                'prob' => 90,
                'stage' => 'qualified',
                'tag' => 'Enterprise',
                'sentiment' => '{"urgency": 9, "profile": "Market Orchestrator"}'
            ),
            array(
                'title' => 'Growth Dynamic Corp',
                'content' => 'Interested in reducing operational friction and increasing lead velocity via AI Content Studio propagation.',
                'email' => 'growth@dynamic-corp.io',
                'phone' => '555-0002',
                'zip' => '10001',
                'source' => 'Neural Quiz',
                'prob' => 65,
                'stage' => 'new',
                'tag' => 'Commercial',
                'sentiment' => '{"urgency": 7, "profile": "Efficiency Seeker"}'
            )
        );

        $ids = array();
        $staff_user = get_user_by('login', 'gp_staff_1');
        $staff_id = $staff_user ? $staff_user->ID : (get_users(array('role__in'=>array('author','editor','administrator'), 'fields'=>'ID'))[0] ?? 0);

        $i = 0;
        foreach ($leads as $l) {
            $backdate = date('Y-m-d H:i:s', strtotime("-" . ($i * 5 + rand(1, 4)) . " days"));
            $id = wp_insert_post(array(
                'post_title'   => $l['title'],
                'post_content' => $l['content'],
                'post_type'    => 'gp_lead',
                'post_status'  => 'publish',
                'post_date'    => $backdate
            ));
            $i++;
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_lead_email', $l['email']);

                // Seed Secure Vault
                $vault = array(
                    array('name' => 'Strategic_Growth_Blueprint.pdf', 'time' => date('Y-m-d H:i'), 'status' => 'Encrypted'),
                    array('name' => 'Financial_Modeling_V1.xlsx', 'time' => date('Y-m-d H:i'), 'status' => 'Encrypted')
                );
                update_post_meta($id, '_secure_vault', $vault);
                update_post_meta($id, '_lead_phone', $l['phone']);
                update_post_meta($id, '_lead_zip', $l['zip']);
                update_post_meta($id, '_lead_source', $l['source']);
                update_post_meta($id, '_gp_ai_probability', $l['prob']);
                update_post_meta($id, '_gp_ai_sentiment_json', $l['sentiment']);
                update_post_meta($id, '_gp_ai_closing_tips', "Strategic Advantage: Emphasize the 5-minute response rule. \nROI Pivot: Contrast the cost of their current manual triage latency against our autonomous realized equity spreads.");
                update_post_meta($id, '_gp_ai_discovery_questions', "1. What is the current financial delta of your ignored leads?\n2. How would a 400% increase in response velocity impact your Q4 realization?");
                update_post_meta($id, '_gp_ai_suggested_reply', "Hello " . explode(' ', $l['title'])[0] . ", I saw your inquiry about automation...");
                update_post_meta($id, '_gp_ai_strategic_plan', "1. Execute v6.3 Merit Review Node\n2. Perform Jurisdictional Overlap Audit\n3. Initialize Secure Asset Vault Uplink\n4. Dispatch Strategic Proposal Node\n5. Finalize Retainer Realization");
                update_post_meta($id, '_gp_ai_competitive_edge', "Your firm is the only one in this ZIP sector utilizing autonomous clinical mapping. This reduces intake latency by 44% compared to standard regional competitors.");
                update_post_meta($id, '_gp_behavioral_nudge', "Based on your interest in " . $l['tag'] . " solutions, we have a specialized team ready.");
                update_post_meta($id, '_gp_nurture_sequence', "Day 1: Welcome\nDay 2: Value Proposition\nDay 3: Case Study\nDay 4: Demo Invitation\nDay 5: Final Follow-up");
                $niche = get_option('growthpress_niche', 'business');
                $niche_terms = array(
                    'solar' => array('Engineering Audit', 'Incentive Triage', 'Array Blueprinting', 'Grid Integration'),
                    'dental' => array('Clinical Analysis', 'Aesthetic Mapping', 'Treatment Kickoff', 'Final Restoration'),
                    'law' => array('Conflict Clearance', 'Merit Review', 'Discovery Phase', 'Litigation Protocol'),
                    'medical' => array('HIPAA Intake', 'Symptom Triage', 'Specialist Routing', 'Clinical Review')
                );
                $terms = $niche_terms[$niche] ?? array('Discovery Node', 'Strategic Triage', 'Architecture Design', 'Full Deployment');

                update_post_meta($id, '_gp_growth_roadmap', "## Strategic Phase 1\n- " . $terms[0] . "\n- " . $terms[1] . "\n\n## Strategic Phase 2\n- " . $terms[2] . "\n- " . $terms[3]);
                update_post_meta($id, '_gp_roadmap_milestones', array('0' => 'complete', '1' => 'complete', '2' => 'pending', '3' => 'pending'));
                update_post_meta($id, '_assigned_staff', $staff_id);

                if (!empty($location_ids)) {
                    update_post_meta($id, '_assigned_location', $location_ids[rand(0, count($location_ids)-1)]);
                }

                if($l['stage'] === 'closed') {
                    update_post_meta($id, '_closed_date', date('Y-m-d H:i:s', strtotime('-2 days')));
                }

                $notes = array(
                    array('user' => 'System AI', 'time' => current_time('mysql'), 'text' => 'Lead automatically triaged and scored.')
                );
                update_post_meta($id, '_gp_internal_notes', $notes);

                $behavior = array(
                    array('page' => 'Home', 'time' => date('Y-m-d H:i:s', strtotime('-1 hour'))),
                    array('page' => 'Services', 'time' => date('Y-m-d H:i:s', strtotime('-30 mins')))
                );
                update_post_meta($id, '_behavior_log', $behavior);

                wp_set_object_terms($id, $l['stage'], 'gp_lead_stage');
                wp_set_object_terms($id, $l['tag'], 'gp_lead_tag');
            }
        }
        return $ids;
    }

    private static function generate_appointments($lead_ids = array()) {
        $ids = array();
        $staff_user = get_user_by('login', 'gp_staff_1');
        $staff_id = $staff_user ? $staff_user->ID : (get_users(array('role__in'=>array('author','editor','administrator'), 'fields'=>'ID'))[0] ?? 0);

        $niche = get_option('growthpress_niche', 'business');
        $niche_titles = array(
            'dental'      => 'Aesthetic Mapping Consultation',
            'law'         => 'Legal Merit Review Briefing',
            'solar'       => 'Structural ROI Engineering Audit',
            'medical'     => 'Specialist Triage Assessment',
            'contractor'  => 'Design-Build Architectural Sync',
            'roofing'     => 'Drone-Assisted Structural Audit',
            'accounting'  => 'Fiscal Trajectory Optimization',
            'real-estate' => 'Portfolio Lifestyle Matching',
            'coaches'     => 'Scale Blueprint Session',
            'consultants' => 'Operational Lifecycle Audit'
        );
        $appt_title = $niche_titles[$niche] ?? 'Strategic Strategy Session';

        $id = wp_insert_post(array(
            'post_title'  => $appt_title,
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish',
            'post_date'   => date('Y-m-d H:i:s', strtotime("-3 days"))
        ));
        if ($id) {
            $ids[] = $id;
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_appointment_date', date('Y-m-d 10:00', strtotime('+1 day')));
            update_post_meta($id, '_staff_id', $staff_id);
            update_post_meta($id, '_status', 'Confirmed');
            update_post_meta($id, '_meeting_link', 'https://zoom.us/j/123456789');
            update_post_meta($id, '_is_waiting_list', '0');
            if (!empty($lead_ids)) {
                update_post_meta($id, '_client_email', get_post_meta($lead_ids[0], '_lead_email', true));
                update_post_meta($id, '_related_lead', $lead_ids[0]);
                wp_set_object_terms($lead_ids[0], 'booked', 'gp_lead_stage');
            }
        }

        $id2 = wp_insert_post(array(
            'post_title'  => 'Priority Waiting Session: ' . ($niche_titles[$niche] ?? 'Strategic Strategy Session'),
            'post_type'   => 'gp_appointment',
            'post_status' => 'publish'
        ));
        if ($id2) {
            $ids[] = $id2;
            update_post_meta($id2, '_gp_is_sample', '1');
            update_post_meta($id2, '_is_waiting_list', '1');
            update_post_meta($id2, '_status', 'Pending');
            update_post_meta($id2, '_client_email', 'waiting@example.com');
        }

        return $ids;
    }

    private static function generate_proposals($lead_ids = array(), $service_ids = array()) {
        $ids = array();
        $id = wp_insert_post(array(
            'post_title'   => 'Sample Growth Proposal',
            'post_content' => 'Full architectural blueprint for ecosystem dominance. Including AI Triage implementation and automated follow-up sequences.',
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));
        if ($id) {
            $ids[] = $id;
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_proposal_status', 'Sent');
            update_post_meta($id, '_proposal_type', 'Project');
            update_post_meta($id, '_internal_approval', 'Approved');
            update_post_meta($id, '_proposal_value', 12500);
            update_post_meta($id, '_proposal_revision', 1);
            update_post_meta($id, '_proposal_expires', date('Y-m-d', strtotime('+30 days')));
            update_post_meta($id, '_is_digitally_signed', '0');
            update_post_meta($id, '_proposal_terms', "Standard service terms apply.\n50% deposit required for kickoff.");
            update_post_meta($id, '_gp_proposal_sent_at', current_time('mysql'));

            if (!empty($lead_ids)) {
                $lead_id = end($lead_ids);
                update_post_meta($id, '_related_lead', $lead_id);
                update_post_meta($id, '_proposal_recipient', get_post_meta($lead_id, '_lead_email', true));
            } else {
                update_post_meta($id, '_proposal_recipient', 'prospect@example.com');
            }

            if (!empty($service_ids)) {
                update_post_meta($id, '_related_service', $service_ids[0]);
            }
        }
        return $ids;
    }

    private static function generate_transactions($proposal_ids = array(), $appt_ids = array()) {
        $methods = array('Stripe', 'PayPal', 'Bank');
        $categories = array('Marketing', 'Operations', 'Software', 'Payroll');
        $niche = get_option('growthpress_niche', 'business');
        $niche_items = array(
            'dental'      => 'Full-Mouth Reconstruction Kickoff',
            'law'         => 'Retainer Realization: Corporate Triage',
            'solar'       => 'Array Engineering Deployment Deposit',
            'medical'     => 'Clinical Protocol Implementation',
            'contractor'  => 'Estate Overhaul Phase 1 Release',
            'roofing'     => 'Natural Slate Sourcing Downpayment',
            'accounting'  => 'Tax Delta Optimization Retainer',
            'real-estate' => 'Portfolio Acquisition Fee',
            'coaches'     => 'Market Dominance Mentorship Seat',
            'consultants' => 'Operational Efficiency Audit Fee'
        );
        $item_name = $niche_items[$niche] ?? 'GrowthPress Service License';

        for($i=0; $i<3; $i++) {
            $id = wp_insert_post(array(
                'post_title'  => 'Transaction: ' . $item_name . ' (#' . (100 + $i) . ')',
                'post_type'   => 'gp_transaction',
                'post_status' => 'publish',
                'post_date'   => date('Y-m-d H:i:s', strtotime("-" . ($i * 7) . " days"))
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_amount', rand(500, 5000));
                update_post_meta($id, '_status', $i === 2 ? 'Pending' : 'Paid');
                update_post_meta($id, '_payment_method', $methods[$i]);
                update_post_meta($id, '_transaction_type', $i === 2 ? 'Expense' : 'Revenue');
                update_post_meta($id, '_transaction_category', $categories[$i % 4]);
                update_post_meta($id, '_is_tax_deductible', $i === 2 ? '1' : '0');
                update_post_meta($id, '_payment_reference', 'GP-TRX-' . strtoupper(wp_generate_password(8, false)));
                update_post_meta($id, '_is_verified', $i === 2 ? '0' : '1');
                update_post_meta($id, '_audit_notes', "Automated sample transaction for system calibration.");

                if ($i === 0 && !empty($proposal_ids)) {
                    update_post_meta($id, '_related_id', $proposal_ids[0]);
                } elseif ($i === 1 && !empty($appt_ids)) {
                    update_post_meta($id, '_related_id', $appt_ids[0]);
                }
            }
        }
    }

    private static function generate_locations() {
        $locs = array(
            'Downtown HQ' => '123 Elite Way, Business District',
            'Westside Satellite' => '456 Innovation Blvd, Tech Hub',
            'Eastside Hub' => '789 Growth Terrace, Industry Park'
        );
        $ids = array();
        foreach ($locs as $l => $addr) {
            $id = wp_insert_post(array(
                'post_title'   => $l,
                'post_content' => 'Strategic service node for the ' . explode(' ', $l)[0] . ' district. Featuring high-fidelity triage systems.',
                'post_type'    => 'gp_location',
                'post_status'  => 'publish'
            ));
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_serviced_zips', '90210, 90211, 90212, 10001, 10002');
                update_post_meta($id, '_location_address', $addr);
                update_post_meta($id, '_location_phone', '555-019' . rand(0,9));
                update_post_meta($id, '_location_map_url', 'https://maps.google.com/?q=' . urlencode($addr));
            }
        }
        return $ids;
    }

    private static function generate_funnels() {
        $id = wp_insert_post(array(
            'post_title'  => 'Elite Scaling Funnel',
            'post_type'   => 'gp_funnel',
            'post_status' => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_hits_A', 1540);
            update_post_meta($id, '_hits_B', 1420);
            update_post_meta($id, '_conv_A', 310);
            update_post_meta($id, '_conv_B', 215);
            update_post_meta($id, '_url_A', home_url('/v1-control'));
            update_post_meta($id, '_url_B', home_url('/v2-challenger'));
            update_post_meta($id, '_conversion_goal', 'Lead Capture Quiz');
            update_post_meta($id, '_funnel_ad_spend', 2500);
            update_post_meta($id, '_lead_value_est', 800);
            update_post_meta($id, '_winning_strategy_note', "Variation A performs better due to lower friction in the initial value proposition.");
        }
    }

    private static function generate_tasks($lead_ids = array()) {
        $niche = get_option('growthpress_niche', 'business');
        $niche_tasks = array(
            'dental'      => array('Aesthetic Case Review' => 'High', 'Clinical Lab Sync' => 'Medium', 'Patient Recalibration' => 'High'),
            'law'         => array('Conflict Clearance Audit' => 'High', 'Merit Review Prep' => 'High', 'Jurisdictional Filing' => 'Medium'),
            'solar'       => array('Structural Engineering Auth' => 'High', 'Incentive Triage' => 'Medium', 'Grid Handshake' => 'High'),
            'medical'     => array('HIPAA Vault Audit' => 'High', 'Specialist Routing' => 'High', 'Clinical Peer Review' => 'Medium'),
            'contractor'  => array('Blueprint Triage' => 'High', 'Permit Realization' => 'Medium', 'Material Authority Auth' => 'High'),
            'roofing'     => array('Drone Survey Review' => 'High', 'Material Sourcing' => 'Medium', 'Safety Protocol Auth' => 'High'),
            'accounting'  => array('Fiscal Delta Analysis' => 'High', 'Tax Node Validation' => 'High', 'Capital Realization Sync' => 'Medium'),
            'real-estate' => array('Off-Market Node Audit' => 'High', 'Lifestyle Matching Recal' => 'Medium', 'Portfolio Realization' => 'High'),
            'coaches'     => array('Authority Blueprint Review' => 'High', 'Sales Talk-Track Calibration' => 'High', 'Mentorship Onboarding' => 'Medium'),
            'consultants' => array('Operational Gap Audit' => 'High', 'Lifecycle Efficiency Review' => 'High', 'Change Command Setup' => 'Medium')
        );
        $tasks = $niche_tasks[$niche] ?? array(
            'High-Priority Triage' => 'High',
            'Strategic Onboarding' => 'Medium',
            'Contract Review' => 'High',
            'System Calibration' => 'Low'
        );
        $staff_user = get_user_by('login', 'gp_staff_1');
        $staff_id = $staff_user ? $staff_user->ID : (get_users(array('role__in'=>array('author','editor','administrator'), 'fields'=>'ID'))[0] ?? 0);

        $j = 0;
        foreach($tasks as $title => $prio) {
            $id = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => 'Automated strategic maintenance task generated by the ecosystem engine.',
                'post_type'    => 'gp_task',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_task_priority', $prio);
                update_post_meta($id, '_task_status', rand(0,1) ? 'Pending' : 'Completed');
                update_post_meta($id, '_task_due_date', date('Y-m-d', strtotime('+' . rand(1, 14) . ' days')));

                // Assign every 2nd task to AI node
                if($j % 2 === 0) {
                    update_post_meta($id, '_assigned_staff', 'ai_node');
                } else {
                    update_post_meta($id, '_assigned_staff', $staff_id);
                }
                $j++;
                if (!empty($lead_ids)) {
                    update_post_meta($id, '_related_lead', $lead_ids[rand(0, count($lead_ids)-1)]);
                }
            }
        }
    }

    private static function generate_kb() {
        $niche = get_option('growthpress_niche', 'business');
        $niche_kb = array(
            'dental'      => array('post_title' => 'v6.3 Aesthetic Mapping Protocol', 'post_content' => 'Guidelines for clinical coordinators to execute high-fidelity dental scans and route for neural aesthetic analysis.'),
            'law'         => array('post_title' => 'Enterprise Conflict Clearance', 'post_content' => 'Standardized protocol for clearing multi-national entities within the v6.3 legal triage node.'),
            'solar'       => array('post_title' => 'Structural ROI Modeling', 'post_content' => 'Technical guide for calculating energy independence deltas based on estate-specific load profiles.'),
            'medical'     => array('post_title' => 'HIPAA-Ready Neural Intake', 'post_content' => 'Security standards for managing patient health data within the autonomous triage infrastructure.'),
            'roofing'     => array('post_title' => 'Industrial Material Authority', 'post_content' => 'Comparison of luxury natural slate vs. standing seam metal for long-term estate protection.'),
            'accounting'  => array('post_title' => 'Fiscal Trajectory Standards', 'post_content' => 'Procedures for identifying reclaimable capital nodes and multi-jurisdictional tax deltas.'),
            'real-estate' => array('post_title' => 'Neural Lifestyle Matching', 'post_content' => 'How the AI matches high-net-worth profiles to off-market inventory based on appreciation trajectories.'),
            'coaches'     => array('post_title' => 'Market Dominance Sequence', 'post_content' => 'The 90-day roadmap for transitioning from manual operations to an autonomous growth engine.'),
            'consultants' => array('post_title' => 'Operational Realization Guide', 'post_content' => 'Framework for performing full-scale architectural audits and reducing firm-wide lifecycle friction.')
        );
        $kb_data = $niche_kb[$niche] ?? array('post_title' => 'v6.3 AI Triage Safety Protocols', 'post_content' => 'Guidelines for maintaining high-fidelity intelligence routing across the 14-node architecture.');

        $id = wp_insert_post(array(
            'post_title'   => $kb_data['post_title'],
            'post_content' => $kb_data['post_content'],
            'post_type'    => 'gp_kb',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_kb_intel_level', 'Executive');
            update_post_meta($id, '_kb_access_control', 'Internal');
            wp_set_post_tags($id, array('Intelligence', 'Protocol', 'Executive'));
        }

        $id2 = wp_insert_post(array(
            'post_title'   => 'Enterprise Portal Access Guide',
            'post_content' => 'Comprehensive walkthrough for high-net-worth clients to access their strategic proposals, project velocity charts, and secure financial ledgers through the GrowthPress Client Portal. This guide covers AES-256 encryption standards and multi-factor authentication nodes.',
            'post_type'    => 'gp_kb',
            'post_status'  => 'publish'
        ));
        if ($id2) {
            update_post_meta($id2, '_gp_is_sample', '1');
            update_post_meta($id2, '_kb_intel_level', 'Basic');
            update_post_meta($id2, '_kb_access_control', 'Client');
            wp_set_post_tags($id2, 'Onboarding, Guide, Portal');
        }
    }

    private static function generate_services() {
        $niche = get_option('growthpress_niche', 'business');
        $niche_services = array(
            'dental' => array(
                'Full-Mouth Restoration' => array('🦷', 'High-authority aesthetic mapping and clinical reconstruction protocols.'),
                'Invisalign Elite' => array('✨', 'Precision clear aligner therapy driven by autonomous clinical triage.'),
                'Neural Dental Triage' => array('🤖', 'AI-driven patient routing and emergency symptom analysis.')
            ),
            'law' => array(
                'Corporate Merger Triage' => array('⚖️', 'High-stakes legal auditing and conflict clearance for enterprise acquisitions.'),
                'Litigation Intelligence' => array('🧠', 'AI-driven case merit review and multi-jurisdictional risk mitigation.'),
                'Asset Protection Node' => array('🛡️', 'Sophisticated global asset shielding and trust architecture.')
            ),
            'solar' => array(
                'Energy Equity Audit' => array('☀️', 'Comprehensive structural engineering and ROI modeling for solar arrays.'),
                'Grid Independence Node' => array('⚡', 'Battery backup integration and high-efficiency energy storage deployment.'),
                'Tax Credit Realization' => array('💰', 'Maximizing federal and state incentive capture through fiscal analysis.')
            ),
            'medical' => array(
                'Neural Triage Deployment' => array('🧠', 'HIPAA-ready AI symptom checking and patient routing infrastructure.'),
                'Clinical Protocol Audit' => array('📋', 'Standardizing patient outcomes through high-fidelity clinical nodes.'),
                'Health Intelligence Hub' => array('🏥', 'Consolidated specialist management and automated intake realizations.')
            ),
            'contractor' => array(
                'Estate Transformation' => array('🏗️', 'High-authority modernist renovations and structural overhauls.'),
                'Structural Engineering Node' => array('📐', 'Precision quotation and architectural blueprinting for elite builds.'),
                'Smart Home Integration' => array('🏠', 'Neural-optimized building automation and ecosystem deployment.')
            ),
            'roofing' => array(
                'Natural Slate Deployment' => array('🏔️', 'Luxury roofing installations utilizing high-authority natural materials.'),
                'Drone Audit Protocol' => array('🛸', 'AI-assisted structural surveys and storm damage mitigation modeling.'),
                'Industrial Asset Protection' => array('🛡️', 'High-fidelity commercial roof protection and ROI tracking.')
            ),
            'accounting' => array(
                'Wealth Preservation Hub' => array('🏦', 'Proprietary fiscal strategy and offshore capital optimization.'),
                'Corporate Audit Node' => array('📊', 'High-stakes multi-jurisdictional audits and ROI validation.'),
                'Capital Realization' => array('💸', 'Identifying reclaimable tax nodes and pipeline equity spreads.')
            ),
            'real-estate' => array(
                'Off-Market Acquisition' => array('💎', 'Access to proprietary inventory nodes via neural lifestyle matching.'),
                'Portfolio Delta Analysis' => array('📈', 'Strategic asset valuation and appreciation trajectory modeling.'),
                'Equity Realization Hub' => array('🏢', 'Maximizing commercial asset yields and modernist estate divestment.')
            ),
            'coaches' => array(
                '7-Figure Scale Blueprint' => array('🚀', 'High-ticket authority building and autonomous realization roadmap.'),
                'Behavioral Triage Coaching' => array('🧠', 'Sales psychology training for elite closing velocity.'),
                'Operational Dominance' => array('⚙️', 'Eliminating manual latency and scaling through neural nodes.')
            ),
            'consultants' => array(
                'Architectural Audit' => array('🔍', 'Full-scale structural analysis of digital operational ecosystems.'),
                'Lifecycle Optimization' => array('♻️', 'Reducing conversion friction and maximizing lead-to-revenue velocity.'),
                'Strategic Efficiency Node' => array('⚡', 'Identifying reclaimable operational equity across the firm.')
            )
        );

        $services = $niche_services[$niche] ?? array(
            'Growth Strategy Audit' => array('📈', 'Comprehensive operational intelligence audit for high-performance firms. We identify untapped pipeline equity through behavioral sales optimization and neural triage protocols.'),
            'Neural Ecosystem Deployment' => array('🤖', 'Full-scale architectural deployment of the v6.3 GrowthPress OS. Seamlessly integrate multi-node intelligence across your entire lead-to-revenue lifecycle.'),
            'Performance Blueprinting' => array('⚡', 'Elite blueprinting for high-ticket service firms. Our specialized v6.3 methodology ensures maximum ROI by eliminating operational latency and structural friction.'),
            'Financial Trajectory Analysis' => array('💰', 'Advanced fiscal modeling and trajectory analysis. Identify high-yield capital preservation nodes and long-term equity growth opportunities.')
        );
        $ids = array();
        foreach ($services as $s => $data) {
            $id = wp_insert_post(array(
                'post_title'   => $s,
                'post_content' => $data[1],
                'post_type'    => 'gp_service',
                'post_status'  => 'publish'
            ));
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_service_icon', $data[0]);
                update_post_meta($id, '_gp_is_sample', '1');
            }
        }
        return $ids;
    }

    private static function generate_projects($lead_ids = array()) {
        $niche = get_option('growthpress_niche', 'business');
        $niche_projects = array(
            'dental' => array(
                'Full-Mouth Reconstruction Realization' => array('+180%', '12 HRS/WK', '$45k+'),
                'Invisalign Elite Deployment' => array('+320%', '8 HRS/WK', '$25k+')
            ),
            'law' => array(
                'Corporate Merger Triage' => array('+440%', '20 HRS/WK', '$1.2M+'),
                'Strategic Litigation Merit Audit' => array('+210%', '15 HRS/WK', '$450k+')
            ),
            'solar' => array(
                'Luxury Estate Infrastructure' => array('+550%', '30 HRS/WK', '$120k+'),
                'Industrial Array Optimization' => array('+140%', '22 HRS/WK', '$850k+')
            ),
            'medical' => array(
                'Neural Triage Hub Implementation' => array('+680%', '40 HRS/WK', '$2.1M+'),
                'Clinical Protocol Standardization' => array('+115%', '18 HRS/WK', '$320k+')
            ),
            'contractor' => array(
                'Modernist Estate Overhaul' => array('+240%', '25 HRS/WK', '$2.5M+'),
                'Structural Engineering Realization' => array('+95%', '12 HRS/WK', '$420k+')
            )
        );

        $projects = $niche_projects[$niche] ?? array(
            'Global Enterprise Migration' => array('+420%', '15 HRS/WK', '$2.5M+'),
            'Sustainable Infrastructure Deployment' => array('+215%', '22 HRS/WK', '$1.8M+'),
            'Neural Triage Implementation' => array('+680%', '40 HRS/WK', '$3.2M+')
        );
        $ids = array();
        $i = 0;
        foreach ($projects as $p => $data) {
            $id = wp_insert_post(array(
                'post_title'   => $p . ' (Realized Result)',
                'post_content' => 'Full-scale realization of the ' . $p . ' protocol for a multi-national entity. This project involved a complete architectural overhaul of existing manual systems, resulting in the elimination of operational latency and a verified ROI of ' . $data[0] . '. The deployment utilized 12 specialized node integrations and custom neural modeling.',
                'post_type'    => 'gp_project',
                'post_status'  => 'publish'
            ));
            if ($id) {
                $ids[] = $id;
                update_post_meta($id, '_gp_is_sample', '1');
                if (!empty($lead_ids)) {
                    update_post_meta($id, '_related_lead', $lead_ids[$i % count($lead_ids)]);
                }
                update_post_meta($id, '_gp_growth_roi', $data[0]);
                update_post_meta($id, '_gp_roi_value', (int)str_replace(['$', 'M', '+'], '', $data[2]) * 1000000);
                update_post_meta($id, '_gp_niche_benchmark', '+15%');
                update_post_meta($id, '_gp_ai_score', rand(85, 98));
                update_post_meta($id, '_gp_efficiency_gain', $data[1]);
                update_post_meta($id, '_gp_pipeline_value', $data[2]);
                wp_set_post_tags($id, 'Enterprise, Transformation, ROI');
            }
        }
        return $ids;
    }

    private static function generate_staff($lead_ids = array()) {
        $specialists = array(
            'Marcus Thorne' => array('Behavioral Sales Psychology', 'Senior Associate'),
            'Elena Vance' => array('Operational Automation', 'Principal Strategist'),
            'David Chen' => array('High-Ticket Triage', 'Managing Director')
        );
        foreach ($specialists as $name => $data) {
            $id = wp_insert_post(array(
                'post_title'   => $name,
                'post_content' => 'Elite human capital node specialized in ' . strtolower($data[0]) . '. Proven track record of system-wide ROI optimization.',
                'post_type'    => 'gp_staff',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                if (!empty($lead_ids)) {
                    update_post_meta($id, '_related_lead', $lead_ids[rand(0, count($lead_ids)-1)]);
                }
                update_post_meta($id, '_staff_expertise', $data[0]);
                update_post_meta($id, '_staff_seniority', $data[1]);
                update_post_meta($id, '_staff_performance_json', json_encode([
                    'efficiency' => rand(85, 98),
                    'conversion' => rand(80, 95),
                    'technical' => rand(90, 99),
                    'speed' => rand(88, 97),
                    'strategy' => rand(85, 96)
                ]));
            }
        }
    }

    private static function generate_inventory() {
        $items = array('Elite Strategic Asset #1', 'High-Yield Node #2', 'Dominance District Hub');
        foreach ($items as $item) {
            $id = wp_insert_post(array(
                'post_title'   => $item,
                'post_content' => 'Premium ' . strtolower($item) . ' for ecosystem expansion. This asset is positioned in a high-growth sector.',
                'post_type'    => 'gp_property',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_price', rand(1500000, 8000000));
                update_post_meta($id, '_gp_sqft', rand(2000, 15000));
                update_post_meta($id, '_gp_lifestyle_tags', 'Modernist, Enterprise, Elite');
            }
        }
    }

    private static function generate_reviews($project_ids = array()) {
        $reviews = array(
            'The autonomous triage is 10x more efficient than our old manual process.' => 'Director of Growth',
            'Seamless client portal experience. Our enterprise partners love the transparency.' => 'Managing Partner',
            'Market dominance was achieved within 3 quarters of implementation.' => 'CEO, Nexus Corp'
        );
        $sources = array('Google', 'Trustpilot', 'Direct');
        $i = 0;
        foreach ($reviews as $content => $author) {
            $id = wp_insert_post(array(
                'post_title'   => $author,
                'post_content' => $content,
                'post_type'    => 'gp_review',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_rating', 5);
                update_post_meta($id, '_gp_client_name', $author);
                update_post_meta($id, '_gp_review_source', $sources[$i % 3]);

                if (!empty($project_ids)) {
                    update_post_meta($id, '_related_project', $project_ids[$i % count($project_ids)]);
                }
                $i++;
            }
        }
    }

    private static function generate_treatments() {
        $treatments = array(
            'Advanced Neural Triage' => array('15 mins', 'Routine'),
            'Ecosystem Migration Protocol' => array('45 mins', 'Standard'),
            'High-Stakes Clinical Audit' => array('90 mins', 'Elite'),
            'Structural ROI Analysis' => array('60 mins', 'Advanced')
        );
        foreach ($treatments as $title => $data) {
            $id = wp_insert_post(array(
                'post_title'   => $title,
                'post_content' => 'Standardized elite protocol for ' . strtolower($title) . '. This procedure ensures maximum system fidelity and operational excellence.',
                'post_type'    => 'gp_treatment',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_treatment_duration', $data[0]);
                update_post_meta($id, '_treatment_complexity', $data[1]);
            }
        }
    }

    private static function generate_chat_templates() {
        $niche = get_option('growthpress_niche', 'business');
        $niche_templates = array(
            'dental' => array(
                array('title' => 'Invisalign Inquiry', 'content' => 'We offer Invisalign Elite protocols. Our neural triage system will map your aesthetic journey during the first session. Would you like to check our availability?', 'keywords' => 'invisalign, aligner, straight'),
                array('title' => 'Emergency Triage', 'content' => 'If you are experiencing acute pain, our specialist routing is active. Please book an "Emergency Triage" slot immediately for priority clinical handling.', 'keywords' => 'emergency, pain, hurt, broken')
            ),
            'law' => array(
                array('title' => 'Retainer Question', 'content' => 'Our corporate litigation nodes operate on a high-value retainer model. We provide multi-jurisdictional risk mitigation and AI-driven case merit analysis.', 'keywords' => 'retainer, cost, price, fee'),
                array('title' => 'Conflict Clearance', 'content' => 'We utilize v6.3 conflict clearance protocols to ensure enterprise-grade legal integrity. Initial clearance takes approximately 24 hours.', 'keywords' => 'conflict, clear, background')
            ),
            'solar' => array(
                array('title' => 'Incentive Modeling', 'content' => 'Our structural ROI engineering identifies all available federal and state tax credits. Most clients realize a 100% ROI within 5-7 years.', 'keywords' => 'tax, credit, incentive, roi, money'),
                array('title' => 'Grid Independence', 'content' => 'We specialize in grid-independence modeling using high-efficiency battery storage nodes. This ensures operational continuity during outages.', 'keywords' => 'battery, storage, outage, grid, off-grid')
            ),
            'medical' => array(
                array('title' => 'HIPAA Security', 'content' => 'All health intelligence data is stored in our secure, HIPAA-ready neural vault. Your clinical records are only accessible to authorized specialists.', 'keywords' => 'hipaa, privacy, secure, safe, data'),
                array('title' => 'Specialist Routing', 'content' => 'Our triage node handles specialized outpatient protocols. Once you book a session, you will be routed to the appropriate clinical expert.', 'keywords' => 'specialist, doctor, expert, route')
            ),
            'contractor' => array(
                array('title' => 'Permit Realization', 'content' => 'Our autonomous design-to-build protocol includes full permit management and structural engineering authentication to reduce project latency.', 'keywords' => 'permit, code, city, engineering'),
                array('title' => 'Material Authority', 'content' => 'We source only high-authority materials for modernist estate overhauls, ensuring absolute structural dominance and long-term appreciation.', 'keywords' => 'material, wood, steel, finish, luxury')
            ),
            'roofing' => array(
                array('title' => 'Drone Audit', 'content' => 'We execute AI-assisted drone surveys to identify structural deltas. This reduces audit friction by 40% and provides high-fidelity storm damage reports.', 'keywords' => 'drone, survey, audit, inspect, fly'),
                array('title' => 'Slate Expertise', 'content' => 'Our industrial deployment teams specialize in natural slate installations, which offer the highest level of asset protection and aesthetic authority.', 'keywords' => 'slate, natural, stone, luxury')
            ),
            'accounting' => array(
                array('title' => 'Tax Delta', 'content' => 'We specialize in identifying reclaimable capital nodes through multi-jurisdictional tax delta analysis. Our focus is long-term wealth preservation.', 'keywords' => 'tax, capital, save, refund, irs'),
                array('title' => 'Fiscal Trajectory', 'content' => 'Our v6.3 Wealth Preservation Engine models your 10-year fiscal trajectory, accounting for high-stakes corporate audits and capital realization.', 'keywords' => 'audit, trajectory, future, wealth, plan')
            ),
            'real-estate' => array(
                array('title' => 'Off-Market Nodes', 'content' => 'Access proprietary off-market inventory through our Neural Lifestyle Matcher. We target high-growth appreciation nodes for elite capital deployment.', 'keywords' => 'off-market, hidden, secret, exclusive, deal'),
                array('title' => 'Portfolio Delta', 'content' => 'Our acquisition specialists utilize AI-driven market delta analysis to pair high-net-worth individuals with modernist estates.', 'keywords' => 'portfolio, asset, investment, estate')
            ),
            'coaches' => array(
                array('title' => 'Scaling Roadmap', 'content' => 'Our Performance Engine provides the 12-month roadmap for 7-figure high-ticket scaling. We focus on transitioning from manual latency to autonomous realization.', 'keywords' => 'scale, growth, roadmap, plan, million'),
                array('title' => 'Authority Building', 'content' => 'We empower founders to capture absolute sector authority through neural content automation and high-stakes closing tactics.', 'keywords' => 'authority, brand, expert, closing, sales')
            ),
            'consultants' => array(
                array('title' => 'Operational Audit', 'content' => 'We identify 18+ hours per week in reclaimable operational equity by eliminating lifecycle friction across your digital ecosystem.', 'keywords' => 'audit, equity, time, friction, efficiency'),
                array('title' => 'Change Command', 'content' => 'Our Management Consultants perform full-scale architectural audits to modernize your ecosystem and maximize conversion velocity.', 'keywords' => 'change, manage, velocity, modern')
            )
        );

        $templates = $niche_templates[$niche] ?? array(
            array('title' => 'General Inquiry', 'content' => 'Welcome to the GrowthPress ecosystem. Our neural-calibrated OS is ready to handle your specialized technical inquiries.', 'keywords' => 'help, information, about')
        );

        foreach ($templates as $tpl) {
            $id = wp_insert_post(array(
                'post_title'   => $tpl['title'],
                'post_content' => $tpl['content'],
                'post_type'    => 'gp_chat_template',
                'post_status'  => 'publish'
            ));
            if ($id) {
                update_post_meta($id, '_gp_is_sample', '1');
                update_post_meta($id, '_gp_template_keywords', $tpl['keywords']);
            }
        }
    }
}
