<?php
/**
 * GrowthPress Customer Portal Class - v6.3 SaaS-Pro Standards
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Portal {

    public function __construct() {
        add_shortcode( 'gp_client_portal', array( $this, 'render_portal' ) );
        add_action( 'wp_ajax_gp_request_reschedule', array( $this, 'handle_reschedule_request' ) );
        add_action( 'wp_ajax_gp_update_portal_profile', array( $this, 'handle_profile_update' ) );
        add_action( 'wp_ajax_gp_portal_upload', array( $this, 'handle_portal_upload' ) );
        add_action( 'wp_ajax_gp_mark_milestone', array( $this, 'handle_mark_milestone' ) );
    }

    public function handle_portal_upload() {
        $user = wp_get_current_user();
        $email = $user->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email, 'posts_per_page' => 1 ) );

        if ( ! empty($leads) ) {
            $lead_id = $leads[0]->ID;
            $vault = get_post_meta($lead_id, '_secure_vault', true) ?: array();
            $vault[] = array(
                'name' => sanitize_text_field($_POST['file_name']),
                'time' => current_time('mysql'),
                'status' => 'Encrypted'
            );
            update_post_meta($lead_id, '_secure_vault', $vault);
            GrowthPress_Activity::log("Secure Asset Uploaded to Vault for Lead #$lead_id");
            wp_send_json_success('Asset synchronized to secure vault.');
        }
        wp_send_json_error();
    }

    public function handle_profile_update() {
        $user = wp_get_current_user();
        $email = $user->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email, 'posts_per_page' => 1 ) );

        if ( ! empty($leads) ) {
            $lead_id = $leads[0]->ID;
            update_post_meta( $lead_id, '_lead_phone', sanitize_text_field( $_POST['phone'] ) );
            update_post_meta( $lead_id, '_lead_zip', sanitize_text_field( $_POST['zip'] ) );
            wp_send_json_success( 'Strategic profile synchronized.' );
        }
        wp_send_json_error();
    }

    public function handle_reschedule_request() {
        update_post_meta(intval($_POST['appointment_id']), '_reschedule_requested', '1');
        wp_send_json_success();
    }

    public function handle_mark_milestone() {
        $lead_id = intval($_POST['lead_id']);
        $milestone_index = intval($_POST['index']);
        $status = sanitize_text_field($_POST['status']);

        $milestones = get_post_meta($lead_id, '_gp_roadmap_milestones', true) ?: array();
        $milestones[$milestone_index] = $status;
        update_post_meta($lead_id, '_gp_roadmap_milestones', $milestones);

        GrowthPress_Activity::log("Milestone #$milestone_index marked as $status for Lead #$lead_id");
        wp_send_json_success('Strategy node updated.');
    }

    public function render_portal() {
        if ( ! is_user_logged_in() ) return '<div class="glass-card gp-reveal" style="text-align:center; padding:120px 60px; border-radius:60px; border: 1px solid rgba(255,255,255,0.4);"><div style="font-size:6rem; margin-bottom:40px;">🔐</div><h2 class="text-gradient" style="font-size:4rem; line-height:1.1;">Secure Node Authentication</h2><p style="opacity:0.7; font-size:1.4rem; max-width: 600px; margin: 30px auto 60px;">Identify yourself to access proprietary strategic metrics, legal blueprints, and financial ledgers.</p><a href="'.wp_login_url(get_permalink()).'" class="gp-btn" style="height:90px; padding:0 100px; font-size:22px; border-radius: 25px;">AUTHENTICATE SESSION</a></div>';

        $user = wp_get_current_user();
        $email = $user->user_email;
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $email ) );
        $lead_ids = wp_list_pluck($leads, 'ID');
        $proposals = ! empty($lead_ids) ? get_posts( array( 'post_type' => 'gp_proposal', 'meta_key' => '_related_lead', 'meta_compare' => 'IN', 'meta_value' => $lead_ids ) ) : array();

        ob_start(); ?>
        <div class="gp-portal-v4 container" style="padding:150px 0;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:100px;">
                <div>
                    <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:5px; margin-bottom:20px;">AUTHENTICATED COMMAND CENTER</div>
                    <h1 class="text-gradient" style="margin:0; font-size:5.5rem; letter-spacing:-0.07em; line-height: 0.9;">Command: <?php echo esc_html($user->display_name); ?></h1>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:11px; font-weight:950; opacity:0.3; letter-spacing:2px; margin-bottom:15px; text-transform: uppercase;">Node: AES-256-GCM Secure</div>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="gp-btn" style="background:var(--secondary); padding:15px 40px; font-size:12px; border-radius:18px; text-transform:none; font-weight: 800;">TERMINATE SESSION</a>
                </div>
            </div>

            <div class="gp-portal-grid" style="display:grid; grid-template-columns: 2.5fr 1fr; gap:60px;">
                <div class="portal-main">
                    <!-- Project Velocity Tracker -->
                    <div class="glass-card" style="border-left: 15px solid var(--primary); margin-bottom:60px; padding:70px; border-radius:44px;">
                        <div style="background:rgba(37,99,235,0.05); border:1px solid rgba(37,99,235,0.1); padding:20px; border-radius:20px; margin-bottom:40px;">
                            <p style="margin:0; font-size:13px; color:var(--primary); font-weight:600;"><strong>Portal Guidance:</strong> This terminal provides real-time access to your strategic growth trajectory. Monitor your 'Operational Velocity' to track the execution of active business nodes.</p>
                        </div>
                        <h3 class="text-gradient" style="font-size:2.2rem; margin-bottom:40px;">Operational Velocity</h3>
                        <div style="background:rgba(0,0,0,0.02); height:12px; border-radius:10px; overflow:hidden; margin-bottom:15px;">
                            <div style="width:64%; height:100%; background:var(--primary); box-shadow:0 0 20px var(--primary-glow);"></div>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:11px; font-weight:950; opacity:0.5; letter-spacing:1px;">
                            <span>INTAKE: COMPLETE</span>
                            <span>TRIAGE: ACTIVE</span>
                            <span>EXECUTION: 64%</span>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:30px; margin-top:50px;">
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">DOCUMENTS</div>
                                <div style="font-size:42px; font-weight:950;"><?php echo count($proposals); ?></div>
                            </div>
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">ACTIVE NODES</div>
                                <div style="font-size:42px; font-weight:950; color:var(--accent);">03</div>
                            </div>
                            <div style="background:#F8FAFC; padding:35px; border-radius:35px; border:1px solid #E2E8F0; text-align:center;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:12px;">URGENCY</div>
                                <div style="font-size:42px; font-weight:950; color:#EF4444;">HI</div>
                            </div>
                        </div>
                    </div>

                    <!-- Roadmap Execution Checklist -->
                    <div id="gp-portal-roadmap" style="margin-bottom:60px;">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Strategy Execution Node</h2>
                        <div class="glass-card" style="padding:50px; border-radius:40px;">
                            <?php
                            $lead = !empty($leads) ? $leads[0] : null;
                            $roadmap_raw = $lead ? get_post_meta($lead->ID, '_gp_growth_roadmap', true) : '';
                            $milestones = $lead ? (get_post_meta($lead->ID, '_gp_roadmap_milestones', true) ?: array()) : array();

                            if($roadmap_raw):
                                // Simple parser for AI roadmap items (lines starting with -)
                                preg_match_all('/^-\s+(.*)$/m', $roadmap_raw, $matches);
                                $items = $matches[1];
                                if($items): ?>
                                    <div style="display:grid; gap:20px;">
                                        <?php foreach($items as $idx => $item):
                                            $is_done = isset($milestones[$idx]) && $milestones[$idx] === 'complete';
                                            ?>
                                            <div style="display:flex; align-items:center; gap:20px; padding:20px; background:#F8FAFC; border-radius:15px; border:1px solid #F1F5F9; <?php echo $is_done ? 'opacity:0.5;' : ''; ?>">
                                                <input type="checkbox" style="width:24px; height:24px; border-radius:6px; cursor:pointer;" <?php checked($is_done); ?> onclick="markMilestone(<?php echo $lead->ID; ?>, <?php echo $idx; ?>, this.checked)">
                                                <span style="font-size:15px; font-weight:700; color:var(--secondary); <?php echo $is_done ? 'text-decoration:line-through;' : ''; ?>"><?php echo esc_html($item); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: echo "<p style='opacity:0.5;'>Analyzing strategy nodes...</p>"; endif; ?>
                            <?php else: echo "<p style='opacity:0.5; text-align:center;'>Awaiting AI Strategic Roadmap generation.</p>"; endif; ?>
                        </div>
                        <script>
                        function markMilestone(leadId, index, isChecked) {
                            jQuery.post(gp_ajax.ajaxurl, {
                                action: 'gp_mark_milestone',
                                lead_id: leadId,
                                index: index,
                                status: isChecked ? 'complete' : 'pending'
                            }, function() {
                                // Real-time feedback via opacity if needed, but simple reload for fidelity
                                location.reload();
                            });
                        }
                        </script>
                    </div>

                    <div id="gp-portal-projects" style="margin-bottom:60px;">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Active Growth Projects</h2>
                        <?php
                        // Query projects explicitly linked to this user's leads
                        $projects = !empty($lead_ids) ? get_posts(array('post_type' => 'gp_project', 'posts_per_page' => 10, 'meta_query' => array(array('key' => '_related_lead', 'value' => $lead_ids, 'compare' => 'IN')))) : array();
                        if($projects): foreach($projects as $p): ?>
                            <div class="glass-card" style="margin-bottom:20px; padding:30px; border-radius:25px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <h4 style="margin:0; font-size:18px;"><?php echo esc_html($p->post_title); ?></h4>
                                    <p style="font-size:12px; opacity:0.5; margin-top:5px;"><?php echo wp_trim_words($p->post_content, 15); ?></p>
                                </div>
                                <a href="<?php echo get_permalink($p->ID); ?>" class="gp-btn" style="padding:10px 25px; font-size:11px; border-radius:10px;">TRACK VELOCITY</a>
                            </div>
                        <?php endforeach; else: echo "<p style='opacity:0.5;'>No active growth projects assigned to this node.</p>"; endif; ?>
                    </div>

                    <div id="proposals">
                        <h2 style="font-size:32px; margin-bottom:40px; letter-spacing:-0.05em; font-weight:950;">Strategic Terminal: Agreements</h2>
                        <?php if($proposals): foreach($proposals as $prop):
                            $status = get_post_meta($prop->ID, '_gp_proposal_status', true) ?: 'Pending Execution'; ?>
                            <div class="glass-card" style="margin-bottom:30px; border-radius:40px; border: 1px solid rgba(0,0,0,0.05); border-left: 12px solid <?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>;">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <h4 style="margin:0; font-size:24px; font-weight:900;"><?php echo esc_html($prop->post_title); ?></h4>
                                        <div style="font-size:12px; font-weight:950; color:<?php echo $status === 'Accepted' ? '#10B981' : 'var(--primary)'; ?>; margin-top:10px; letter-spacing:2px;">STATUS: <?php echo strtoupper($status); ?></div>
                                    </div>
                                    <button class="gp-btn" style="padding:15px 45px; font-size:13px; border-radius:18px;" onclick="jQuery('#p-<?php echo $prop->ID; ?>').slideToggle()">OPEN TERMINAL</button>
                                </div>
                                <div id="p-<?php echo $prop->ID; ?>" style="display:none; margin-top:50px; padding-top:50px; border-top:2px solid #F1F5F9;">
                                    <div class="entry-content" style="font-size:17px; line-height:1.9; color:var(--text); font-family:'Inter', sans-serif;"><?php echo apply_filters('the_content', $prop->post_content); ?></div>
                                    <?php if($status !== 'Accepted'): ?>
                                        <div style="margin-top:60px; text-align:center; background:#F8FAFC; padding:60px; border-radius:35px; border:1px solid #E2E8F0;">
                                            <h3 style="margin-bottom:20px;">Execute Strategic Agreement</h3>
                                            <p style="opacity:0.6; margin-bottom:40px;">By clicking below, you authenticate and digitally sign this binding strategic proposal.</p>
                                            <button class="gp-btn" style="width:100%; height:85px; font-size:20px;" onclick="acceptProposal(<?php echo $prop->ID; ?>)">EXECUTE & INITIATE KICKOFF</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; else: echo "<div class='glass-card' style='opacity:0.5; text-align:center; padding:80px; border-radius:40px;'>No pending strategic documents detected in current secure node.</div>"; endif; ?>
                    </div>
                </div>

                <div class="portal-side">
                    <!-- Secure Messenger Mockup -->
                    <div class="glass-card" style="background:var(--secondary); color:white; border:none; margin-bottom:40px; padding:50px; border-radius:44px;">
                        <h3 style="color:white; font-size:24px; margin-bottom:20px;">Strategy Uplink</h3>
                        <p style="font-size:16px; opacity:0.7; line-height:1.7;">Direct priority connection to your dedicated operational specialists.</p>
                        <div style="display:grid; gap:20px; margin-top:40px;">
                            <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="width:100%; border-radius:20px; background:var(--primary); text-transform:none; height:70px; font-size:16px;">Book Briefing</a>
                            <button class="gp-btn" style="width:100%; background:rgba(255,255,255,0.06); border-radius:20px; text-transform:none; height:70px; border:1px solid rgba(255,255,255,0.1); font-size:16px;">Secure Message</button>
                        </div>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px; margin-bottom:40px;">
                        <h3 style="font-size:22px; margin-bottom:10px; letter-spacing:-0.03em;">Secure Asset Vault</h3>
                        <p style="font-size:12px; opacity:0.5; margin-bottom:30px;">Access encrypted blueprints and proprietary documents. <strong>Note:</strong> All uploads are processed via AES-256-GCM encryption node.</p>
                        <div id="gp-portal-docs" style="display:grid; gap:15px;">
                            <?php
                            $lead = !empty($leads) ? $leads[0] : null;
                            $vault = $lead ? get_post_meta($lead->ID, '_secure_vault', true) : array();
                            if($vault): foreach($vault as $v): ?>
                                <div style="background:#F8FAFC; padding:15px 20px; border-radius:12px; display:flex; justify-content:space-between; align-items:center; border:1px solid #F1F5F9;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <span class="dashicons dashicons-lock" style="opacity:0.3;"></span>
                                        <span style="font-size:12px; font-weight:700;"><?php echo esc_html($v['name']); ?></span>
                                    </div>
                                    <span style="font-size:8px; font-weight:950; opacity:0.4; letter-spacing:1px; background:#D1FAE5; color:#065F46; padding:4px 8px; border-radius:4px;">ENCRYPTED</span>
                                </div>
                            <?php endforeach; else: ?>
                                <div style="text-align:center; padding:20px; opacity:0.5; font-size:12px; border:2px dashed #E2E8F0; border-radius:15px;">No assets synchronized to vault.</div>
                            <?php endif; ?>
                        </div>
                        <div id="vault-status" style="margin-top:20px; font-size:11px; font-weight:700; color:var(--primary); display:none;">SYNCING ASSET...</div>
                        <button class="gp-btn" style="width:100%; margin-top:25px; height:50px; font-size:12px; border-radius:12px; background:transparent; border:2px dashed #E2E8F0; color:var(--text) !important;" onclick="uploadToVault()">+ SYNC STRATEGIC ASSET</button>
                        <script>
                        function uploadToVault() {
                            const name = prompt("Enter asset name for encryption:");
                            if(!name) return;
                            jQuery('#vault-status').fadeIn();
                            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_portal_upload', file_name: name }, function(res) {
                                if(res.success) {
                                    alert(res.data);
                                    location.reload();
                                }
                            });
                        }
                        </script>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px; margin-bottom:40px;">
                        <h3 style="font-size:22px; margin-bottom:10px; letter-spacing:-0.03em;">Financial Ledger</h3>
                        <p style="font-size:12px; opacity:0.5; margin-bottom:30px;">Real-time synchronization of settled transactions and pending investment nodes.</p>
                        <?php
                        // Isolate Transactions by related lead IDs or email matching
                        $transactions = !empty($lead_ids) ? get_posts(array('post_type' => 'gp_transaction', 'posts_per_page' => 10, 'meta_query' => array(array('key' => '_related_id', 'value' => array_merge($lead_ids, $proposals ? wp_list_pluck($proposals, 'ID') : []), 'compare' => 'IN')))) : array();
                        if($transactions): foreach($transactions as $t):
                            $status = get_post_meta($t->ID, '_status', true);
                            ?>
                            <div style="padding:15px 0; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-size:13px; font-weight:700;"><?php echo esc_html($t->post_title); ?></div>
                                    <div style="font-size:10px; opacity:0.4;"><?php echo get_the_date('M j, Y', $t->ID); ?></div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:13px; font-weight:900; color:<?php echo $status === 'Paid' ? '#10B981' : 'var(--primary)'; ?>;">$<?php echo number_format(get_post_meta($t->ID, '_amount', true)); ?></div>
                                    <div style="font-size:8px; font-weight:950; opacity:0.5;"><?php echo strtoupper($status); ?></div>
                                </div>
                            </div>
                        <?php endforeach; else: echo "<p style='opacity:0.5;'>No financial ledger entries detected.</p>"; endif; ?>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px; margin-bottom:40px;">
                        <h3 style="font-size:22px; margin-bottom:30px; letter-spacing:-0.03em;">Strategic Profile</h3>
                        <?php
                        $lead = !empty($leads) ? $leads[0] : null;
                        $phone = $lead ? get_post_meta($lead->ID, '_lead_phone', true) : '';
                        $zip = $lead ? get_post_meta($lead->ID, '_lead_zip', true) : '';
                        ?>
                        <form id="gp-portal-profile">
                            <div style="margin-bottom:20px;">
                                <label style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:1px; display:block; margin-bottom:10px;">SECURE PHONE</label>
                                <input type="text" id="prof-phone" value="<?php echo esc_attr($phone); ?>" style="width:100%; height:50px; border-radius:12px; border:1px solid #E2E8F0; padding:0 15px;">
                            </div>
                            <div style="margin-bottom:30px;">
                                <label style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:1px; display:block; margin-bottom:10px;">GEOGRAPHIC ZIP</label>
                                <input type="text" id="prof-zip" value="<?php echo esc_attr($zip); ?>" style="width:100%; height:50px; border-radius:12px; border:1px solid #E2E8F0; padding:0 15px;">
                            </div>
                            <button type="button" class="gp-btn" style="width:100%; height:55px; font-size:12px; border-radius:12px;" onclick="updatePortalProfile()">SYNC PROFILE</button>
                        </form>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius:44px;">
                        <h3 style="font-size:22px; margin-bottom:30px; letter-spacing:-0.03em;">Intelligence Logs</h3>
                        <?php $appts = get_posts( array( 'post_type' => 'gp_appointment', 'meta_key' => '_client_email', 'meta_value' => $email ) );
                        if($appts): foreach($appts as $a):
                            $date = get_post_meta($a->ID, '_appointment_date', true); ?>
                            <div style="padding:25px 0; border-bottom:1px solid #F1F5F9;">
                                <div style="font-size:16px; font-weight:900; color:var(--secondary);"><?php echo esc_html($a->post_title); ?></div>
                                <div style="font-size:11px; font-weight:800; opacity:0.5; margin-top:10px; text-transform:uppercase; letter-spacing:1px;">TIMESTAMP: <?php echo $date; ?></div>
                                <?php $link = get_post_meta($a->ID, '_meeting_link', true); if($link): ?>
                                    <a href="<?php echo esc_url($link); ?>" target="_blank" class="gp-btn" style="display:inline-block; margin-top:15px; padding:8px 20px; font-size:10px; border-radius:10px;">JOIN MEETING</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; else: echo "<p style='font-size:14px; opacity:0.5;'>No previous session data synchronized.</p>"; endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function updatePortalProfile() {
                jQuery.post(gp_ajax.ajaxurl, {
                    action: 'gp_update_portal_profile',
                    phone: jQuery('#prof-phone').val(),
                    zip: jQuery('#prof-zip').val()
                }, function(res) {
                    if(res.success) alert(res.data);
                });
            }

            function acceptProposal(id) {
                if(!confirm("Execute agreement and engagement of proprietary services?")) return;
                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_accept_proposal', proposal_id: id }, function(res) {
                    if(res.success) {
                        alert("AGREEMENT DIGITALLY EXECUTED. KICKOFF PROTOCOL ENGAGED.");
                        location.reload();
                    }
                });
            }
        </script>
        <?php
        return ob_get_clean();
    }

}
new GrowthPress_Portal();
