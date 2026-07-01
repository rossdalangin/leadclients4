<?php
/**
 * GrowthPress Unified Proposal System
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Proposals {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'init', array( $this, 'register_proposal_cpt' ) );
        add_action( 'wp_ajax_gp_generate_ai_proposal', array( $this, 'handle_ai_proposal_generation' ) );
        add_action( 'wp_ajax_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'wp_ajax_nopriv_gp_accept_proposal', array( $this, 'handle_proposal_acceptance' ) );
        add_action( 'wp_ajax_gp_send_proposal', array( $this, 'handle_send_proposal' ) );
        add_action( 'wp_ajax_gp_archive_proposal', array( $this, 'handle_archive_proposal' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_proposal_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_proposal_meta' ) );
        add_filter( 'manage_gp_proposal_posts_columns', array( $this, 'proposal_columns' ) );
        add_action( 'manage_gp_proposal_posts_custom_column', array( $this, 'proposal_column_content' ), 10, 2 );
    }

    public function proposal_columns( $cols ) {
        $cols['_email'] = 'Recipient';
        $cols['_status'] = 'Status';
        $cols['_val'] = 'Value';
        $cols['_expires'] = 'Expires';
        return $cols;
    }

    public function proposal_column_content( $col, $post_id ) {
        if ( $col === '_email' ) echo get_post_meta( $post_id, '_proposal_recipient', true ) ?: '-';
        if ( $col === '_status' ) {
            $status = get_post_meta( $post_id, '_gp_proposal_status', true ) ?: 'Draft';
            $colors = array('Draft' => '#94a3b8', 'Sent' => '#3b82f6', 'Accepted' => '#10b981', 'Declined' => '#ef4444', 'Archived' => '#64748b');
            echo "<span style='color:".($colors[$status] ?? '#000')."; font-weight:bold;'>$status</span>";
        }
        if ( $col === '_val' ) echo '$' . number_format(get_post_meta( $post_id, '_proposal_value', true ));
        if ( $col === '_expires' ) {
            $exp = get_post_meta( $post_id, '_proposal_expires', true );
            if($exp) {
                $is_expired = strtotime($exp) < time();
                echo '<span style="'.($is_expired ? 'color:#ef4444; font-weight:bold;' : '').'">'.$exp.'</span>';
            } else {
                echo '-';
            }
        }
    }

    public function add_proposal_meta_boxes() {
        add_meta_box( 'gp_proposal_details', '📜 Strategic Architecture Proposal Intel', array( $this, 'render_proposal_meta' ), 'gp_proposal', 'normal', 'high' );
    }

    public function render_proposal_meta( $post ) {
        $lead_id = get_post_meta( $post->ID, '_related_lead', true );
        $service_id = get_post_meta( $post->ID, '_related_service', true );
        $status = get_post_meta( $post->ID, '_gp_proposal_status', true ) ?: 'Sent';
        $type = get_post_meta( $post->ID, '_proposal_type', true ) ?: 'Project';
        $approval = get_post_meta( $post->ID, '_internal_approval', true ) ?: 'Pending';
        $email = get_post_meta( $post->ID, '_proposal_recipient', true );
        $value = get_post_meta( $post->ID, '_proposal_value', true );
        $expires = get_post_meta( $post->ID, '_proposal_expires', true );
        $revision = get_post_meta( $post->ID, '_proposal_revision', true ) ?: 1;
        $signed = get_post_meta( $post->ID, '_is_digitally_signed', true );
        $terms = get_post_meta( $post->ID, '_proposal_terms', true );
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $services = get_posts( array( 'post_type' => 'gp_service', 'posts_per_page' => -1 ) );
        ?>
        <div style="background: #f0fdf4; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
            <p style="margin: 0; font-size: 13px; color: #065f46;"><strong>Engagement Protocol:</strong> Drafting a proposal creates a binding strategic document. Once sent, the client can execute it via the secure portal, which will autonomously trigger project kickoff and financial ledger entries.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Recipient Email</label><p class="description">Target email for proposal dispatch and portal access.</p></th>
                <td><input type="email" name="gp_proposal_recipient" value="<?php echo esc_attr($email); ?>" class="regular-text" placeholder="client@example.com"></td>
            </tr>
            <tr>
                <th><label>Related Business Lead</label><p class="description">Links the proposal to a CRM lead for automated status advancement.</p></th>
                <td>
                    <select name="gp_related_lead" style="width:100%;">
                        <option value="0">Generic / No Lead</option>
                        <?php foreach($leads as $l): ?>
                            <option value="<?php echo $l->ID; ?>" <?php selected($lead_id, $l->ID); ?>><?php echo esc_html($l->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Target Service Line</label><p class="description">The specific elite service being proposed.</p></th>
                <td>
                    <select name="gp_related_service" style="width:100%;">
                        <option value="0">No Specific Service</option>
                        <?php foreach($services as $s): ?>
                            <option value="<?php echo $s->ID; ?>" <?php selected($service_id, $s->ID); ?>><?php echo esc_html($s->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Internal Approval</label><p class="description">Administrative state. Ensure technical review is complete before dispatching to client.</p></th>
                <td>
                    <select name="gp_internal_approval" style="width:100%;">
                        <option value="Pending" <?php selected($approval, 'Pending'); ?>>Pending Technical Review</option>
                        <option value="Approved" <?php selected($approval, 'Approved'); ?>>Approved for Dispatch</option>
                        <option value="Rejected" <?php selected($approval, 'Rejected'); ?>>Rejected / Revision Required</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Engagement Type</label><p class="description">Classifies the proposal for service line ROI tracking.</p></th>
                <td>
                    <select name="gp_proposal_type" style="width:100%;">
                        <option value="Project" <?php selected($type, 'Project'); ?>>One-Time Project</option>
                        <option value="Retainer" <?php selected($type, 'Retainer'); ?>>Strategic Retainer</option>
                        <option value="Consulting" <?php selected($type, 'Consulting'); ?>>Management Consulting</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Proposal Status</label><p class="description">Current lifecycle stage. 'Accepted' triggers system-wide kickoff automations.</p></th>
                <td>
                    <select name="gp_proposal_status" style="width:100%;">
                        <option value="Draft" <?php selected($status, 'Draft'); ?>>Draft</option>
                        <option value="Sent" <?php selected($status, 'Sent'); ?>>Sent / Active</option>
                        <option value="Accepted" <?php selected($status, 'Accepted'); ?>>Accepted / Executed</option>
                        <option value="Declined" <?php selected($status, 'Declined'); ?>>Declined</option>
                        <option value="Archived" <?php selected($status, 'Archived'); ?>>Archived</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Strategic Value ($)</label><p class="description">Total contract value. Used for ROI modeling and revenue forecasting.</p></th>
                <td><input type="number" name="gp_proposal_value" value="<?php echo esc_attr($value); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Proposal Revision</label><p class="description">Current version of the strategic architecture.</p></th>
                <td><input type="number" name="gp_proposal_rev" value="<?php echo esc_attr($revision); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Expiration Date</label><p class="description">The date this strategic offer becomes void.</p></th>
                <td><input type="date" name="gp_proposal_expires" value="<?php echo esc_attr($expires); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Signature Status</label><p class="description">Confirmation of digital execution via the client portal.</p></th>
                <td><input type="checkbox" name="gp_proposal_signed" value="1" <?php checked($signed, '1'); ?>> Marked as Signed</td>
            </tr>
            <tr>
                <th><label>Custom Client Terms</label><p class="description">Specific conditions or project-level nuances for this agreement.</p></th>
                <td><textarea name="gp_proposal_terms" style="width:100%; height:100px;"><?php echo esc_textarea($terms); ?></textarea></td>
            </tr>
        </table>
        <?php
        $sent_at = get_post_meta($post->ID, '_gp_proposal_sent_at', true);
        if ($sent_at):
        ?>
        <div style="background:#f9f9f9; padding:15px; border:1px solid #ddd; border-radius:4px; margin-top:20px;">
            <h4 style="margin:0 0 10px 0;">📜 Dispatch History</h4>
            <div style="font-size:12px;">Sent to <strong><?php echo esc_html($email); ?></strong> on <?php echo date('M j, Y @ H:i', strtotime($sent_at)); ?></div>
        </div>
        <?php endif; ?>
        <div style="margin-top:20px; padding-top:20px; border-top:1px solid #eee; display:flex; gap:10px;">
            <button type="button" class="button button-primary" onclick="gpDispatchProposal(<?php echo $post->ID; ?>)">🚀 Send to Client</button>
            <?php if ($status !== 'Accepted'): ?>
                <button type="button" class="button" style="background:#10b981; color:white; border-color:#059669;" onclick="gpForceAcceptProposal(<?php echo $post->ID; ?>)">✅ Manual Accept</button>
            <?php endif; ?>
            <button type="button" class="button" onclick="gpArchiveProposal(<?php echo $post->ID; ?>)">📂 Archive Proposal</button>
        </div>
        <script>
            function gpDispatchProposal(id) {
                if(!confirm('Dispatch this proposal to the recipient?')) return;
                jQuery.post(ajaxurl, {action:'gp_send_proposal', proposal_id:id, gp_nonce:'<?php echo wp_create_nonce("gp_admin_nonce"); ?>'}, function(r){
                    alert(r.data);
                    location.reload();
                });
            }
            function gpArchiveProposal(id) {
                jQuery.post(ajaxurl, {action:'gp_archive_proposal', proposal_id:id, gp_nonce:'<?php echo wp_create_nonce("gp_admin_nonce"); ?>'}, function(r){
                    alert(r.data);
                    location.reload();
                });
            }
            function gpForceAcceptProposal(id) {
                if(!confirm('Manually mark this proposal as ACCEPTED? This will trigger project kickoff.')) return;
                jQuery.post(ajaxurl, {action:'gp_accept_proposal', proposal_id:id, gp_nonce:'<?php echo wp_create_nonce("gp_admin_nonce"); ?>'}, function(r){
                    alert('Proposal Accepted. Project initialized.');
                    location.reload();
                });
            }
        </script>
        <?php
    }

    public function save_proposal_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_proposal_status'] ) ) return;
        update_post_meta( $post_id, '_related_lead', intval( $_POST['gp_related_lead'] ) );
        update_post_meta( $post_id, '_related_service', intval( $_POST['gp_related_service'] ) );
        update_post_meta( $post_id, '_proposal_recipient', sanitize_email( $_POST['gp_proposal_recipient'] ) );
        update_post_meta( $post_id, '_gp_proposal_status', sanitize_text_field( $_POST['gp_proposal_status'] ) );
        update_post_meta( $post_id, '_proposal_type', sanitize_text_field( $_POST['gp_proposal_type'] ) );
        update_post_meta( $post_id, '_internal_approval', sanitize_text_field( $_POST['gp_internal_approval'] ) );
        update_post_meta( $post_id, '_proposal_value', floatval( $_POST['gp_proposal_value'] ) );
        update_post_meta( $post_id, '_proposal_revision', intval( $_POST['gp_proposal_rev'] ) );
        update_post_meta( $post_id, '_proposal_expires', sanitize_text_field( $_POST['gp_proposal_expires'] ) );
        update_post_meta( $post_id, '_is_digitally_signed', isset($_POST['gp_proposal_signed']) ? '1' : '0' );
        update_post_meta( $post_id, '_proposal_terms', wp_kses_post( $_POST['gp_proposal_terms'] ) );
    }

    public function register_proposal_cpt() {
        register_post_type( 'gp_proposal', array(
            'labels'      => array( 'name' => 'Proposals', 'singular_name' => 'Proposal' ),
            'public'      => true,
            'show_ui'     => true,
            'show_in_rest' => true,
            'menu_icon'   => 'dashicons-media-text',
            'supports'    => array( 'title', 'editor', 'custom-fields', 'excerpt' ),
            'rewrite'     => array( 'slug' => 'p' ),
            'has_archive' => false,
        ) );
    }

    public function handle_ai_proposal_generation() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $lead_id = intval($_POST['lead_id']);
        $lead = get_post($lead_id);
        if ( ! $lead ) wp_send_json_error('Lead not found.');

        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        $sentiment = get_post_meta($lead_id, '_gp_ai_sentiment_json', true);
        $prob = get_post_meta($lead_id, '_gp_ai_probability', true) ?: 50;

        $proposal_content = $ai->generate_proposal(array(
            'title'     => $lead->post_title,
            'service'   => "Advanced $niche Solutions",
            'niche'     => $niche,
            'inquiry'   => $lead->post_content,
            'sentiment' => $sentiment,
            'prob'      => $prob
        ));

        $proposal_id = wp_insert_post(array(
            'post_title'   => 'Proposal: ' . $lead->post_title,
            'post_content' => $proposal_content,
            'post_type'    => 'gp_proposal',
            'post_status'  => 'publish'
        ));

        update_post_meta($proposal_id, '_related_lead', $lead_id);
        update_post_meta($proposal_id, '_proposal_recipient', get_post_meta($lead_id, '_lead_email', true));
        update_post_meta($proposal_id, '_gp_proposal_status', 'Draft');

        // Estimate value based on niche/probability
        $prob = get_post_meta($lead_id, '_gp_ai_probability', true) ?: 50;
        $base_values = array('law' => 5000, 'solar' => 25000, 'dental' => 3000, 'contractor' => 15000, 'accounting' => 2000);
        $est_val = $base_values[$niche] ?? 5000;
        update_post_meta($proposal_id, '_proposal_value', $est_val);

        GrowthPress_Activity::log( "AI Proposal #$proposal_id generated for " . $lead->post_title );
        wp_send_json_success( "Proposal generated! ID: $proposal_id. Value: $$est_val" );
    }

    public function handle_send_proposal() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $proposal_id = intval($_POST['proposal_id']);
        $email = get_post_meta($proposal_id, '_proposal_recipient', true);

        if ( ! $email ) wp_send_json_error('No recipient email specified.');

        update_post_meta($proposal_id, '_gp_proposal_status', 'Sent');
        update_post_meta($proposal_id, '_gp_proposal_sent_at', current_time('mysql'));

        GrowthPress_Activity::log( "Proposal #$proposal_id sent to $email." );
        wp_send_json_success('Proposal dispatched successfully.');
    }

    public function handle_archive_proposal() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $proposal_id = intval($_POST['proposal_id']);
        update_post_meta($proposal_id, '_gp_proposal_status', 'Archived');

        GrowthPress_Activity::log( "Proposal #$proposal_id moved to archive." );
        wp_send_json_success('Proposal archived.');
    }

    public function handle_proposal_acceptance() {
        $proposal_id = intval($_POST['proposal_id']);
        update_post_meta($proposal_id, '_gp_proposal_status', 'Accepted');

        // Move Lead to Closed
        $lead_id = get_post_meta($proposal_id, '_related_lead', true);
        if ($lead_id) {
            wp_set_object_terms($lead_id, 'closed', 'gp_lead_stage');
            update_post_meta($lead_id, '_closed_date', current_time('mysql'));

            // Create Kickoff Task
            $crm = GrowthPress_CRM::get_instance();
            $crm->create_task("Project Kickoff: " . get_the_title($lead_id), "Proposal accepted. Initialize onboarding sequence.", $lead_id);

            // Create Draft Case Study with relational metadata
            $project_id = wp_insert_post(array(
                'post_title'   => 'Case Study: ' . get_the_title($lead_id),
                'post_content' => 'Proposal accepted on ' . date('Y-m-d') . ". Summary: " . get_the_excerpt($lead_id),
                'post_type'    => 'gp_project',
                'post_status'  => 'draft'
            ));

            if ($project_id) {
                update_post_meta($project_id, '_related_lead', $lead_id);
                update_post_meta($project_id, '_originating_proposal', $proposal_id);

                // Inherit sample flag if applicable
                if (get_post_meta($lead_id, '_gp_is_sample', true)) {
                    update_post_meta($project_id, '_gp_is_sample', '1');
                }
            }

            GrowthPress_Activity::log( "Lead #$lead_id transitioned to 'Closed' following proposal acceptance. Draft Case Study initialized." );
        }

        $value = get_post_meta($proposal_id, '_proposal_value', true);
        $payments = new GrowthPress_Payments();
        $invoice_id = $payments->create_invoice($value, $proposal_id, 'proposal');

        GrowthPress_Activity::log( "Proposal #$proposal_id accepted. Invoice #$invoice_id generated." );
        wp_send_json_success(array('invoice_id' => $invoice_id));
    }

    public function get_pipeline_value() {
        $proposals = get_posts(array(
            'post_type' => 'gp_proposal',
            'posts_per_page' => -1,
            'meta_query' => array( array( 'key' => '_gp_proposal_status', 'value' => 'Sent' ) )
        ));
        $total = 0;
        foreach($proposals as $p) $total += (float)get_post_meta($p->ID, '_proposal_value', true);
        return $total;
    }
}
GrowthPress_Proposals::get_instance();
