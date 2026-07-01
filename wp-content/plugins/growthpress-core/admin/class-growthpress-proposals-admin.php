<?php
/**
 * GrowthPress Proposals Admin Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Proposals_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_proposals_menu' ) );
    }

    public function add_proposals_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Business Proposals',
            'Proposals',
            'manage_options',
            'growthpress-proposals',
            array( $this, 'render_proposals_page' )
        );
    }

    public function render_proposals_page() {
        $proposals = get_posts(array('post_type' => 'gp_proposal', 'posts_per_page' => -1));
        ?>
        <div class="wrap growthpress-proposals gp-reveal">
            <div class="glass-card" style="background:#f0f7ff; border-left:5px solid #2563eb; margin-bottom:30px;">
                <h4 style="margin:0 0 10px 0; color:#1e40af;">📜 Strategic Context: Proposal Realization</h4>
                <p style="margin:0; font-size:14px; color:#1e40af; line-height:1.5;">Proposals are high-stakes digital agreements that anchor your "Pipeline Equity." Once a client accepts a proposal in the portal, the system automatically moves the lead to 'Closed' and initializes a Project node. <strong>Success Pattern:</strong> Including an "ROI Forecast" within the proposal body increases acceptance velocity by 28% in high-ticket sectors.</p>
            </div>

            <h1>Active Business Proposals</h1>
            <p class="description">Manage and track your high-ticket service quotes. <strong>Pro-Tip:</strong> Use the ROI Forecast module inside each proposal to anchor your value.</p>

            <div class="glass-card" style="margin-top:20px; padding:0; overflow:hidden;">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Proposal Title</th>
                            <th>Related Lead</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($proposals as $p):
                            $status = get_post_meta($p->ID, '_gp_proposal_status', true) ?: 'Sent';
                            $lead_id = get_post_meta($p->ID, '_related_lead', true); ?>
                            <tr>
                                <td><strong><?php echo esc_html($p->post_title); ?></strong></td>
                                <td><?php echo get_the_title($lead_id); ?></td>
                                <td><span class="status-badge"><?php echo $status; ?></span></td>
                                <td><a href="<?php echo get_edit_post_link($p->ID); ?>" class="button button-small">Edit Content</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <style>
            .status-badge { background:#e2e8f0; padding:5px 12px; border-radius:30px; font-size:10px; font-weight:900; text-transform:uppercase; }
            .growthpress-proposals .wp-list-table th { padding: 20px; font-weight: 900; opacity: 0.5; font-size: 11px; letter-spacing: 1px; }
            .growthpress-proposals .wp-list-table td { padding: 20px; vertical-align: middle; }
        </style>
        <?php
    }
}

new GrowthPress_Proposals_Admin();
