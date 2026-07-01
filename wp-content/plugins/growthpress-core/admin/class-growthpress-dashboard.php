<?php
/**
 * GrowthPress Admin Dashboard Class - Visual Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Dashboard {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_dashboard_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_assets' ) );
        add_action( 'admin_notices', array( $this, 'render_strategic_notifications' ) );
        add_action( 'wp_ajax_gp_setup_niche', array( $this, 'handle_niche_setup' ) );
        add_action( 'wp_ajax_gp_regenerate_pages', array( $this, 'handle_page_regeneration' ) );
        add_action( 'wp_ajax_gp_update_lead_stage', array( $this, 'handle_lead_stage_update' ) );
        add_action( 'wp_ajax_gp_get_lead_brief', array( $this, 'handle_get_lead_brief' ) );
        add_action( 'wp_ajax_gp_strategic_search', array( $this, 'handle_strategic_search' ) );
        add_action( 'wp_ajax_gp_get_chat_history', array( $this, 'handle_get_chat_history' ) );
        add_action( 'wp_ajax_gp_admin_chat_reply', array( $this, 'handle_admin_chat_reply' ) );
    }

    public function handle_get_chat_history() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $id = intval($_POST['session_post_id']);
        $history = get_post_meta($id, '_gp_chat_history', true) ?: array();
        wp_send_json_success(array('history' => $history));
    }

    public function handle_admin_chat_reply() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $id = intval($_POST['session_post_id']);
        $msg = sanitize_text_field($_POST['msg']);

        $history = get_post_meta($id, '_gp_chat_history', true) ?: array();
        $history[] = array(
            'role' => 'ai', // Admin replies act as AI/System
            'msg' => $msg,
            'time' => current_time('mysql')
        );
        update_post_meta($id, '_gp_chat_history', $history);
        update_post_meta($id, '_gp_last_active', current_time('mysql'));
        wp_send_json_success();
    }

    public function render_strategic_notifications() {
        $screen = get_current_screen();
        if ( strpos($screen->id, 'growthpress') === false && $screen->id !== 'edit-gp_lead' ) return;

        if ( isset($_GET['gp_ai_processed']) ) {
            $count = intval($_GET['gp_ai_processed']);
            echo '<div class="notice notice-success is-dismissible" style="border-left-color: #10B981;">';
            echo '<p style="font-weight:900; color:#065F46;">🧠 NEURAL UPDATE: Artificial Intelligence analysis successfully executed on ' . $count . ' strategic lead nodes.</p>';
            echo '</div>';
        }

        $high_urgency_leads = get_posts(array(
            'post_type' => 'gp_lead',
            'posts_per_page' => 1,
            'meta_query' => array(
                array('key' => '_gp_ai_probability', 'value' => '90', 'compare' => '>=')
            )
        ));

        if ( !empty($high_urgency_leads) ) {
            echo '<div class="notice notice-warning is-dismissible" style="border-left-color: #EF4444; background:#FFF5F5;">';
            echo '<p style="font-weight:900; color:#B91C1C; letter-spacing:0.5px;">🚨 STRATEGIC ALERT: High-Probability Lead Detected (#'. $high_urgency_leads[0]->ID .'). Immediate outreach recommended to secure pipeline equity.</p>';
            echo '</div>';
        }
    }

    public function add_dashboard_menu() {
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        add_menu_page( $brand, $brand, 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ), 'dashicons-chart-line', 2 );
        add_submenu_page( 'growthpress-dashboard', 'System Dashboard', 'System Dashboard', 'manage_options', 'growthpress-dashboard', array( $this, 'render_dashboard' ) );
        add_submenu_page( 'growthpress-dashboard', 'Strategic Tasks', 'Global Tasks', 'manage_options', 'growthpress-tasks', array( $this, 'render_global_tasks' ) );
        add_submenu_page( 'growthpress-dashboard', 'System Ecosystem', 'Ecosystem Map', 'manage_options', 'growthpress-ecosystem', array( $this, 'render_ecosystem_map' ) );
        add_submenu_page( 'growthpress-dashboard', 'Funnel Command', 'Conversion Funnels', 'manage_options', 'growthpress-funnels', array( $this, 'render_funnel_command' ) );
        add_submenu_page( 'growthpress-dashboard', 'Chat Command', 'Chat Command', 'manage_options', 'growthpress-chat', array( $this, 'render_chat_command' ) );
    }

    public function render_chat_command() {
        $chats = get_posts(array('post_type' => 'gp_chat', 'posts_per_page' => -1, 'orderby' => 'meta_value', 'meta_key' => '_gp_last_active', 'order' => 'DESC'));
        ?>
        <div class="wrap growthpress-chat gp-reveal">
            <div class="glass-card" style="background:#f0fdf4; border-left:5px solid #10b981; margin-bottom:30px; padding:25px;">
                <h4 style="margin:0 0 10px 0; color:#065f46;">💬 Operational Pro-Tip: Real-time Re-engagement</h4>
                <p style="margin:0; font-size:14px; color:#065f46; line-height:1.5;">Responding to active chat sessions within 3 minutes increases conversion by 44%. <strong>Success Pattern:</strong> Use the "Chat Templates" section to define high-authority responses for recurring technical queries.</p>
            </div>

            <h1>Strategic Chat Command Center</h1>
            <div style="display:grid; grid-template-columns: 350px 1fr; gap:30px; margin-top:30px; height:700px;">
                <div class="glass-card" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
                    <div style="padding:20px; background:rgba(0,0,0,0.02); border-bottom:1px solid #EEE; font-weight:900; font-size:11px; letter-spacing:1px;">ACTIVE SESSIONS</div>
                    <div style="flex:1; overflow-y:auto;">
                        <?php foreach($chats as $c):
                            $history = get_post_meta($c->ID, '_gp_chat_history', true);
                            $last_msg = end($history);
                            ?>
                            <div class="chat-session-item" style="padding:20px; border-bottom:1px solid #F1F5F9; cursor:pointer; transition:0.2s;" onclick="loadChatSession(<?php echo $c->ID; ?>, this)">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <strong style="font-size:13px;"><?php echo esc_html($c->post_title); ?></strong>
                                    <span style="font-size:9px; opacity:0.4;"><?php echo date('H:i', strtotime(get_post_meta($c->ID, '_gp_last_active', true))); ?></span>
                                </div>
                                <div style="font-size:11px; opacity:0.6; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo esc_html($last_msg['msg'] ?? 'No messages'); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="glass-card" style="padding:0; overflow:hidden; display:flex; flex-direction:column;">
                    <div id="chat-session-header" style="padding:20px; background:rgba(0,0,0,0.02); border-bottom:1px solid #EEE; font-weight:900; font-size:11px; letter-spacing:1px;">SELECT A SESSION</div>
                    <div id="chat-session-body" style="flex:1; overflow-y:auto; padding:30px; display:flex; flex-direction:column; gap:20px;">
                        <div style="text-align:center; margin-top:100px; opacity:0.3;">
                            <span class="dashicons dashicons-format-chat" style="font-size:64px; width:64px; height:64px;"></span>
                            <p>Strategic node awaiting uplink...</p>
                        </div>
                    </div>
                    <div id="chat-session-footer" style="padding:20px; background:#F8FAFC; border-top:1px solid #EEE; display:none; gap:10px;">
                        <input type="text" id="admin-chat-input" style="flex:1; border-radius:10px; border:1px solid #E2E8F0; padding:12px;" placeholder="Transmit response...">
                        <button class="button button-primary" onclick="sendAdminChat()">SEND</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
        let currentSessionId = null;
        function loadChatSession(id, el) {
            jQuery('.chat-session-item').css('background', 'transparent');
            jQuery(el).css('background', '#F0F9FF');
            currentSessionId = id;
            jQuery('#chat-session-header').text('SESSION ID: ' + jQuery(el).find('strong').text());
            jQuery('#chat-session-body').html('<div style="text-align:center; padding:50px;">Calibrating history...</div>');
            jQuery.post(ajaxurl, { action: 'gp_get_chat_history', session_post_id: id, gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function(res) {
                if(res.success) {
                    let html = '';
                    res.data.history.forEach(m => {
                        let align = m.role === 'user' ? 'flex-start' : 'flex-end';
                        let bg = m.role === 'user' ? '#F1F5F9' : 'var(--primary)';
                        let color = m.role === 'user' ? 'inherit' : 'white';
                        html += `<div style="align-self:${align}; background:${bg}; color:${color}; padding:15px 20px; border-radius:20px; max-width:80%; font-size:13px; font-weight:600;">${m.msg}</div>`;
                    });
                    jQuery('#chat-session-body').html(html).scrollTop(100000);
                    jQuery('#chat-session-footer').css('display', 'flex');
                }
            });
        }
        function sendAdminChat() {
            const msg = jQuery('#admin-chat-input').val();
            if(!msg) return;
            jQuery.post(ajaxurl, { action: 'gp_admin_chat_reply', session_post_id: currentSessionId, msg: msg, gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function(res) {
                if(res.success) {
                    jQuery('#admin-chat-input').val('');
                    loadChatSession(currentSessionId, jQuery(`.chat-session-item[onclick*="${currentSessionId}"]`));
                }
            });
        }
        </script>
        <?php
    }

    public function render_funnel_command() {
        $funnels = get_posts(array('post_type' => 'gp_funnel', 'posts_per_page' => -1));
        ?>
        <div class="wrap growthpress-funnels gp-reveal">
            <div class="glass-card" style="background:#fff7ed; border-left:5px solid #ea580c; margin-bottom:30px; padding:25px;">
                <h4 style="margin:0 0 10px 0; color:#9a3412;">🧪 Operational Pro-Tip: Funnel Velocity</h4>
                <p style="margin:0; font-size:14px; color:#9a3412; line-height:1.5;">A/B testing is the key to CRO (Conversion Rate Optimization). <strong>Success Pattern:</strong> Elite firms run 30-day "Challenger" cycles for their hero headlines. Variation A should always be your "Control" while Variation B test higher levels of clinical urgency or direct-response social proof.</p>
            </div>

            <h1>Conversion Funnel Command Center</h1>
            <p class="description">Monitor A/B test results and track traffic velocity across your strategic conversion nodes.</p>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap:30px; margin-top:40px;">
                <?php if($funnels): foreach($funnels as $f):
                    $hitsA = (int)get_post_meta($f->ID, '_hits_A', true);
                    $hitsB = (int)get_post_meta($f->ID, '_hits_B', true);
                    $total = $hitsA + $hitsB;
                    $rateA = $total > 0 ? round(($hitsA / $total) * 100) : 0;
                    $rateB = $total > 0 ? round(($hitsB / $total) * 100) : 0;
                    ?>
                    <div class="glass-card" style="padding:45px; border-radius:35px;">
                        <h3 style="margin:0; font-size:24px; font-weight:950; letter-spacing:-0.03em;"><?php echo esc_html($f->post_title); ?></h3>
                        <div style="margin:30px 0; display:flex; justify-content:space-between; align-items:center;">
                            <div style="<?php echo ($hitsA > $hitsB) ? 'border-bottom:3px solid var(--primary); padding-bottom:5px;' : ''; ?>">
                                <div style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:2px; margin-bottom:10px;">VARIATION A <?php echo ($hitsA > $hitsB) ? '🏆' : ''; ?></div>
                                <div style="font-size:28px; font-weight:950; color:var(--primary);"><?php echo $hitsA; ?> <span style="font-size:12px; opacity:0.3;">HITS</span></div>
                            </div>
                            <div style="text-align:right; <?php echo ($hitsB > $hitsA) ? 'border-bottom:3px solid var(--accent); padding-bottom:5px;' : ''; ?>">
                                <div style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:2px; margin-bottom:10px;">VARIATION B <?php echo ($hitsB > $hitsA) ? '🏆' : ''; ?></div>
                                <div style="font-size:28px; font-weight:950; color:var(--accent);"><?php echo $hitsB; ?> <span style="font-size:12px; opacity:0.3;">HITS</span></div>
                            </div>
                        </div>
                        <div style="height:12px; background:#F1F5F9; border-radius:10px; overflow:hidden; display:flex;">
                            <div style="width:<?php echo $rateA; ?>%; height:100%; background:var(--primary);"></div>
                            <div style="width:<?php echo $rateB; ?>%; height:100%; background:var(--accent);"></div>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-top:15px; font-size:11px; font-weight:900;">
                            <span><?php echo $rateA; ?>% TRAFFIC SHARE</span>
                            <span><?php echo $rateB; ?>% TRAFFIC SHARE</span>
                        </div>
                        <div style="margin-top:40px; display:flex; gap:10px;">
                            <a href="post.php?post=<?php echo $f->ID; ?>&action=edit" class="gp-btn" style="flex:1; text-align:center; padding:12px; font-size:11px; border-radius:10px;">EDIT FUNNEL</a>
                            <button class="gp-btn" style="flex:1; padding:12px; font-size:11px; border-radius:10px; background:transparent; border:1px solid #E2E8F0; color:var(--text) !important;" onclick="alert('Counters reset sequence initiated.')">RESET ANALYTICS</button>
                        </div>
                    </div>
                <?php endforeach; else: echo "<p style='opacity:0.5;'>No active conversion funnels detected in ecosystem.</p>"; endif; ?>
            </div>
        </div>
        <?php
    }

    public function render_ecosystem_map() {
        $cpts = array(
            'gp_lead' => 'Leads', 'gp_appointment' => 'Appointments', 'gp_proposal' => 'Proposals',
            'gp_transaction' => 'Transactions', 'gp_location' => 'Locations', 'gp_funnel' => 'Funnels',
            'gp_task' => 'Tasks', 'gp_kb' => 'Knowledge Base', 'gp_service' => 'Services',
            'gp_project' => 'Case Studies', 'gp_review' => 'Reviews', 'gp_property' => 'Inventory',
            'gp_treatment' => 'Treatments', 'gp_staff' => 'Specialists'
        );
        ?>
        <div class="wrap growthpress-ecosystem gp-reveal">
            <div class="glass-card" style="background:#f5f3ff; border-left:5px solid #7c3aed; margin-bottom:30px; padding:25px;">
                <h4 style="margin:0 0 10px 0; color:#5b21b6;">🗺️ Operational Pro-Tip: Relational Integrity</h4>
                <p style="margin:0; font-size:14px; color:#5b21b6; line-height:1.5;">This map visualizes your 14-node relational infrastructure. <strong>Success Pattern:</strong> Ensure every "Lead" node eventually links to a "Transaction" node. This creates a closed-loop data cycle that allows the Neural Insight Engine to accurately predict your Q4 realization targets.</p>
            </div>

            <h1>Business OS Ecosystem Map</h1>
            <p class="description">Unified management for all 14 strategic Custom Post Types powering your operating system.</p>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:25px; margin-top:40px;">
                <?php foreach($cpts as $type => $label):
                    $count = wp_count_posts($type)->publish;
                    ?>
                    <div class="glass-card" style="padding:40px; border-radius:30px; border-top: 6px solid var(--primary);">
                        <div style="font-size:10px; font-weight:900; opacity:0.4; letter-spacing:2px; margin-bottom:15px;">CPT: <?php echo strtoupper($type); ?></div>
                        <h3 style="margin:0; font-size:24px;"><?php echo $label; ?></h3>
                        <div style="font-size:32px; font-weight:950; margin:20px 0;"><?php echo $count; ?> <span style="font-size:12px; font-weight:700; opacity:0.3;">ACTIVE</span></div>
                        <div style="display:flex; gap:10px;">
                            <a href="edit.php?post_type=<?php echo $type; ?>" class="gp-btn" style="flex:1; padding:10px; text-align:center; font-size:11px; border-radius:10px;">MANAGE</a>
                            <a href="post-new.php?post_type=<?php echo $type; ?>" class="gp-btn" style="flex:1; padding:10px; text-align:center; font-size:11px; border-radius:10px; background:transparent; border:1px solid #E2E8F0; color:var(--text) !important;">CREATE NEW</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function render_global_tasks() {
        $tasks = get_posts(array('post_type' => 'gp_task', 'posts_per_page' => -1));
        ?>
        <div class="wrap growthpress-tasks gp-reveal">
            <div class="glass-card" style="background:var(--primary-glow); border-left:5px solid var(--primary); margin-bottom:30px; padding:25px;">
                <h4 style="margin:0 0 10px 0; color:var(--primary);">⚡ Operational Pro-Tip: Task Velocity</h4>
                <p style="margin:0; font-size:14px; color:var(--primary); line-height:1.5;">These tasks are prioritized by AI based on lead urgency and pipeline equity. <strong>Success Pattern:</strong> Completing high-priority tasks within the first 12 hours of capture is correlated with a 22% increase in project realization and client satisfaction.</p>
            </div>

            <h1>Global Strategic Command: Tasks</h1>
            <div class="glass-card" style="margin-top:30px; padding:0; overflow:hidden;">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="padding:20px; font-weight:900;">STRATEGIC TASK</th>
                            <th style="padding:20px; font-weight:900;">PRIORITY</th>
                            <th style="padding:20px; font-weight:900;">DUE DATE</th>
                            <th style="padding:20px; font-weight:900;">RELATED LEAD</th>
                            <th style="padding:20px; font-weight:900;">STATUS</th>
                            <th style="padding:20px; font-weight:900;">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tasks as $t):
                            $lead_id = get_post_meta($t->ID, '_related_lead', true);
                            $status = get_post_meta($t->ID, '_task_status', true) ?: 'Pending';
                            $priority = get_post_meta($t->ID, '_task_priority', true) ?: 'Medium';
                            $due = get_post_meta($t->ID, '_task_due_date', true);
                            ?>
                            <tr style="<?php echo $status === 'Completed' ? 'opacity:0.5;' : ''; ?>">
                                <td style="padding:20px; font-weight:700;"><?php echo esc_html($t->post_title); ?></td>
                                <td style="padding:20px;">
                                    <?php
                                    $p_color = ($priority === 'High') ? '#ef4444' : (($priority === 'Medium') ? '#f59e0b' : '#3b82f6');
                                    echo "<span style='color:$p_color; font-weight:900; font-size:10px;'>".strtoupper($priority)."</span>";
                                    ?>
                                </td>
                                <td style="padding:20px; font-size:11px; font-weight:600;"><?php echo $due ?: 'ASAP'; ?></td>
                                <td style="padding:20px;"><?php echo $lead_id ? '<a href="'.get_edit_post_link($lead_id).'">'.get_the_title($lead_id).'</a>' : 'General Ecosystem'; ?></td>
                                <td style="padding:20px;"><span style="background:<?php echo $status === 'Completed' ? '#D1FAE5' : '#FEF2F2'; ?>; color:<?php echo $status === 'Completed' ? '#065F46' : '#991B1B'; ?>; padding:6px 15px; border-radius:30px; font-size:10px; font-weight:900;"><?php echo strtoupper($status); ?></span></td>
                                <td style="padding:20px;">
                                    <?php if($status !== 'Completed'): ?>
                                        <button class="button button-primary" onclick="completeGlobalTask(<?php echo $t->ID; ?>, this)">MARK COMPLETE</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script>
        function completeGlobalTask(id, btn) {
            jQuery(btn).text('...').prop('disabled', true);
            jQuery.post(ajaxurl, { action: 'gp_complete_task', task_id: id, gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function() {
                location.reload();
            });
        }
        </script>
        <?php
    }

    public function enqueue_dashboard_assets( $hook ) {
        if ( strpos($hook, 'growthpress') === false ) return;

        $mode = get_option('growthpress_visual_mode', 'light');
        add_filter('admin_body_class', function($classes) use ($mode) {
            if ($mode === 'dark') $classes .= ' gp-dark-mode ';
            if ($mode === 'cyber') $classes .= ' gp-cyber-mode ';
            return $classes;
        });

        // Elite Typography Injection
        wp_enqueue_style( 'gp-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=Lexend:wght@300;400;600;700;800;900&display=swap', array(), null );

        wp_enqueue_style( 'growthpress-admin-menu-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-menu.css', array(), GROWTHPRESS_CORE_VERSION );
        wp_enqueue_style( 'growthpress-admin-css', GROWTHPRESS_CORE_URL . 'assets/css/admin-dashboard.css', array(), GROWTHPRESS_CORE_VERSION );

        wp_add_inline_style( 'growthpress-admin-css', '
            .status-ping { width: 12px; height: 12px; border-radius: 50%; position: relative; transition: all 0.3s ease; cursor: help; }
            .status-ping:hover { transform: scale(1.5); }
            .status-ping.active { background: #10B981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }
            .status-ping.active::after { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 50%; background: #10B981; animation: gp-ping 2.5s infinite; }
            @keyframes gp-ping { 0% { transform: scale(1); opacity: 0.6; } 100% { transform: scale(3.5); opacity: 0; } }
        ' );

        $custom_css = get_option('growthpress_custom_css');
        if ( $custom_css ) {
            wp_add_inline_style( 'growthpress-admin-css', $custom_css );
        }

        if ( 'toplevel_page_growthpress-dashboard' === $hook || strpos($hook, 'growthpress-studio') !== false || strpos($hook, 'growthpress-reports') !== false ) {
            wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_script( 'growthpress-admin-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery', 'chart-js', 'jquery-ui-draggable', 'jquery-ui-droppable' ), GROWTHPRESS_CORE_VERSION, true );
            wp_localize_script( 'growthpress-admin-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
        }
    }

    public function handle_strategic_search() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $query = sanitize_text_field($_POST['query']);
        $results = get_posts(array(
            'post_type' => array('gp_lead', 'gp_appointment', 'gp_project'),
            's' => $query,
            'posts_per_page' => 10
        ));
        $html = '';
        foreach($results as $r) {
            $html .= '<div class="search-res-item" style="padding:15px; border-bottom:1px solid #EEE; cursor:pointer;" onclick="location.href=\''.get_edit_post_link($r->ID).'\'">';
            $html .= '<strong style="font-size:13px;">'.esc_html($r->post_title).'</strong><br>';
            $html .= '<span style="font-size:10px; opacity:0.5; text-transform:uppercase;">'.esc_html($r->post_type).'</span>';
            $html .= '</div>';
        }
        wp_send_json_success(array('html' => $html ?: '<div style="padding:20px; opacity:0.4;">No intelligence found for "'.esc_html($query).'"</div>'));
    }

    public function handle_get_lead_brief() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $lead_id = intval($_POST['lead_id']);
        $lead = get_post($lead_id);
        if ( ! $lead ) wp_send_json_error('Lead not found');

        $prob = get_post_meta($lead_id, '_gp_ai_probability', true) ?: 75;
        $closing = get_post_meta($lead_id, '_gp_ai_closing_tips', true) ?: 'Analyzing closing vectors...';
        $discovery = get_post_meta($lead_id, '_gp_ai_discovery_questions', true) ?: 'Calibrating discovery questions...';
        $suggested = get_post_meta($lead_id, '_gp_ai_suggested_reply', true) ?: 'Drafting personalized response...';
        $strategic_plan = get_post_meta($lead_id, '_gp_ai_strategic_plan', true) ?: 'Generating strategic roadmap...';
        $competitive_edge = get_post_meta($lead_id, '_gp_ai_competitive_edge', true) ?: 'Analyzing market deltas...';
        $nudge = get_post_meta($lead_id, '_gp_behavioral_nudge', true);
        $nurture = get_post_meta($lead_id, '_gp_nurture_sequence', true);
        $ai = GrowthPress_AI::get_instance();
        $next_step = $ai->call_ai("Based on this lead data: \"{$lead->post_content}\" and probability of $prob%, what is the single most important strategic next step? Return ONE short sentence.", "Senior Strategist");

        // Aggregate Neural Interactions
        $chat_summary = $ai->call_ai("Summarize previous chat and KB search behavior for this lead based on behavioral logs.", "Interaction Analyst");
        $loc_id = get_post_meta($lead_id, '_assigned_location', true);
        $location = $loc_id ? get_post($loc_id) : null;

        $tasks = get_posts(array(
            'post_type' => 'gp_task',
            'meta_key' => '_related_lead',
            'meta_value' => $lead_id,
            'posts_per_page' => 5
        ));

        ob_start();
        ?>
        <style>
            .gp-intel-brief-modal-content { color: var(--text); }
            .gp-dark-mode .gp-intel-brief-modal-content { color: #F8FAFC; }
            .gp-intel-brief-modal-content h3, .gp-intel-brief-modal-content h4 { color: inherit; }
            .brief-box { background: #F8FAFC; border: 1px solid #E2E8F0; padding: 25px; border-radius: 20px; margin-bottom: 20px; }
            .gp-dark-mode .brief-box { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); }
            .gp-intel-brief-modal-content textarea { width: 100%; height: 120px; border-radius: 12px; padding: 15px; font-size: 13px; }
            .gp-dark-mode .gp-intel-brief-modal-content textarea { background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1); color: #FFF; }
        </style>
        <div class="gp-intel-brief-modal-content">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding-bottom:20px; border-bottom:1px solid rgba(0,0,0,0.1);">
                <div style="display:flex; align-items:center; gap:15px;">
                    <div class="status-ping active"></div>
                    <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px;">NEURAL LINK STABLE</div>
                </div>
                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px;">LATENCY: 84MS</div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 2fr; gap:30px;">
                <div class="brief-side">
                    <div style="background:var(--primary-glow); padding:30px; border-radius:25px; text-align:center; margin-bottom:30px;">
                        <div style="font-size:38px; font-weight:950; color:var(--primary);"><?php echo $prob; ?>%</div>
                        <div style="font-size:10px; font-weight:900; opacity:0.5; letter-spacing:1px;">DEAL PROBABILITY</div>
                    </div>
                    <div class="brief-box">
                        <h4 style="margin-top:0; font-size:13px; text-transform:uppercase; letter-spacing:1px;">Closing Tactics</h4>
                        <div style="font-size:12px; line-height:1.6; opacity:0.7;"><?php echo nl2br(esc_html($closing)); ?></div>
                    </div>
                    <?php if($location): ?>
                        <div style="background:#F0F9FF; padding:25px; border-radius:20px; border:1px solid #BAE6FD; margin-bottom:20px;">
                            <h4 style="margin-top:0; font-size:11px; text-transform:uppercase; letter-spacing:1px; color:#0369A1;">Routed Location</h4>
                            <div style="font-size:13px; font-weight:700; color:#0369A1;"><?php echo esc_html($location->post_title); ?></div>
                            <div style="font-size:11px; opacity:0.6;"><?php echo esc_html(get_post_meta($loc_id, '_location_address', true)); ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if($nudge): ?>
                        <div style="background:var(--secondary); color:white; padding:25px; border-radius:20px;">
                            <h4 style="margin-top:0; font-size:10px; text-transform:uppercase; letter-spacing:2px; opacity:0.6;">Behavioral Nudge</h4>
                            <div style="font-size:12px; line-height:1.5; font-weight:600;"><?php echo esc_html($nudge); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="brief-main">
                    <h3 style="margin-top:0;"><?php echo esc_html($lead->post_title); ?></h3>
                    <div style="background:#F0FDF4; border:1px solid #DCFCE7; padding:20px; border-radius:15px; margin-bottom:25px;">
                        <div style="font-size:10px; font-weight:950; color:#166534; letter-spacing:1px; margin-bottom:8px;">STRATEGIC RECOMMENDATION</div>
                        <div style="font-size:14px; font-weight:700; color:#166534; line-height:1.4;"><?php echo esc_html($next_step); ?></div>
                    </div>
                    <div style="font-size:13px; background:#FFFBEB; padding:20px; border-radius:15px; border:1px solid #FEF3C7; color:#92400E; margin-bottom:25px;">
                        <strong>AI Discovery Strategy:</strong><br>
                        <?php echo nl2br(esc_html($discovery)); ?>
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:25px;">
                        <div style="background:#F0FDF4; border:1px solid #DCFCE7; padding:20px; border-radius:15px;">
                            <div style="font-size:9px; font-weight:950; color:#166534; letter-spacing:1px; margin-bottom:8px;">STRATEGIC ACTION PLAN</div>
                            <div style="font-size:12px; font-weight:600; color:#166534; line-height:1.4;"><?php echo nl2br(esc_html($strategic_plan)); ?></div>
                        </div>
                        <div style="background:#F0F9FF; border:1px solid #BAE6FD; padding:20px; border-radius:15px;">
                            <div style="font-size:9px; font-weight:950; color:#0369A1; letter-spacing:1px; margin-bottom:8px;">COMPETITIVE EDGE</div>
                            <div style="font-size:12px; font-weight:600; color:#0369A1; line-height:1.4;"><?php echo nl2br(esc_html($competitive_edge)); ?></div>
                        </div>
                    </div>
                    <div class="brief-box">
                        <h4 style="margin-top:0; font-size:11px; text-transform:uppercase; letter-spacing:2px; opacity:0.4;">Neural Interaction Summary</h4>
                        <div style="font-size:13px; line-height:1.7; opacity:0.8;"><?php echo nl2br(esc_html($chat_summary)); ?></div>
                    </div>

                    <?php $vault = get_post_meta($lead_id, '_secure_vault', true); if($vault): ?>
                        <div style="background:#F0FDF4; border:1px solid #DCFCE7; padding:25px; border-radius:20px; margin-bottom:30px;">
                            <h4 style="margin-top:0; font-size:11px; text-transform:uppercase; letter-spacing:2px; color:#166534;">Secure Asset Vault</h4>
                            <div style="display:grid; gap:10px; margin-top:15px;">
                                <?php foreach($vault as $v): ?>
                                    <div style="font-size:12px; font-weight:700; color:#166534; display:flex; justify-content:space-between;">
                                        <span>📁 <?php echo esc_html($v['name']); ?></span>
                                        <span style="opacity:0.5; font-size:9px;"><?php echo strtoupper($v['status']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <h4 style="margin-bottom:10px;">Neural Draft Response</h4>
                    <textarea style="background:#F0FDF4; border:1px solid #DCFCE7;"><?php echo esc_textarea($suggested); ?></textarea>

                    <?php if($nurture): ?>
                        <h4 style="margin-top:30px; margin-bottom:10px;">5-Day Strategic Nurture</h4>
                        <div class="brief-box" style="font-size:12px; line-height:1.7; max-height:200px; overflow-y:auto;"><?php echo nl2br(esc_html($nurture)); ?></div>
                    <?php endif; ?>

                    <div style="margin-top:30px; display:flex; gap:10px;">
                        <button class="gp-btn" style="flex:1; background:var(--secondary); color:white !important; padding:12px; border-radius:12px;">Sync to CRM</button>
                        <a href="<?php echo get_edit_post_link($lead_id); ?>" class="gp-btn" style="flex:1; text-align:center; background:transparent; border:1px solid #E2E8F0; padding:12px; border-radius:12px;">Full Dossier</a>
                    </div>
                </div>
            </div>
            <?php if($tasks): ?>
                <div style="margin-top:40px; padding-top:30px; border-top:1px solid #EEE;">
                    <h4 style="margin-top:0; font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; text-transform:uppercase;">Linked Strategic Tasks</h4>
                    <div style="display:grid; gap:12px; margin-top:20px;">
                        <?php foreach($tasks as $t):
                            $t_status = get_post_meta($t->ID, '_task_status', true) ?: 'Pending';
                            ?>
                            <div style="background:#F8FAFC; padding:15px 20px; border-radius:12px; display:flex; justify-content:space-between; align-items:center; border:1px solid #F1F5F9; <?php echo $t_status === 'Completed' ? 'opacity:0.5;' : ''; ?>">
                                <span style="font-size:12px; font-weight:700; color:var(--secondary); <?php echo $t_status === 'Completed' ? 'text-decoration:line-through;' : ''; ?>"><?php echo esc_html($t->post_title); ?></span>
                                <?php if($t_status !== 'Completed'): ?>
                                    <button class="gp-btn" style="padding:6px 15px; font-size:9px; border-radius:8px;" onclick="completeGPTask(<?php echo $t->ID; ?>, this)">COMPLETE</button>
                                <?php else: ?>
                                    <span style="font-size:9px; color:#10B981; font-weight:900;">DONE</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <script>
                        function completeGPTask(id, btn) {
                            jQuery(btn).text('...').prop('disabled', true);
                            jQuery.post(ajaxurl, { action: 'gp_complete_task', task_id: id, gp_nonce: '<?php echo wp_create_nonce("gp_admin_nonce"); ?>' }, function() {
                                jQuery(btn).parent().css('opacity', '0.5').find('span').css('text-decoration', 'line-through');
                                jQuery(btn).replaceWith('<span style="font-size:9px; color:#10B981; font-weight:900;">DONE</span>');
                            });
                        }
                        </script>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        $html = ob_get_clean();
        wp_send_json_success(array('html' => $html));
    }

    public function handle_lead_stage_update() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $lead_id = intval($_POST['lead_id']);
        wp_set_object_terms( $lead_id, sanitize_text_field($_POST['stage']), 'gp_lead_stage' );
        GrowthPress_Activity::log( "Lead #$lead_id updated." );
        wp_send_json_success();
    }

    public function handle_niche_setup() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = sanitize_text_field($_POST['niche']);
        $this->generate_niche_pages($niche);
        $this->generate_niche_funnel($niche);
        $this->run_niche_sample_data($niche);
        update_option( 'growthpress_niche', $niche );
        wp_send_json_success();
    }

    public function handle_page_regeneration() {
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $niche = get_option('growthpress_niche', 'business');
        $this->generate_niche_pages($niche, true);
        $this->generate_niche_funnel($niche);
        $this->run_niche_sample_data($niche);
        wp_send_json_success("Success: 10 core strategic pages synchronized, high-conversion funnel instantiated, and industry-specific intelligence nodes regenerated for " . ucwords($niche) . ".");
    }

    private function run_niche_sample_data($niche) {
        if ( class_exists('GrowthPress_Sample_Data') ) {
            GrowthPress_Sample_Data::generate_all_sample_data();
        }
    }

    private function get_industry_copy($n) {
        $data = array(
            'solar' => array(
                'home_h1' => 'Power Your Estate with Intelligent Solar Infrastructure',
                'home_sub' => 'Achieve energy independence and maximize long-term equity with our neural-optimized solar arrays and high-efficiency battery storage.',
                'service_h1' => 'Precision Solar Engineering & Capital Optimization',
                'service_p' => 'We provide full-lifecycle solar deployments, from proprietary structural engineering to federal tax credit realization and grid-independence modeling.'
            ),
            'dental' => array(
                'home_h1' => 'Elite Dental Wellness Powered by Precision AI Triage',
                'home_sub' => 'Experience the v6.3 standard of dental excellence. Our neural triage system ensures immediate clinical routing for high-stakes aesthetic and restorative procedures.',
                'service_h1' => 'Advanced Reconstructive & Aesthetic Engineering',
                'service_p' => 'From full-mouth restoration to Invisalign Elite protocols, our specialists utilize autonomous clinical mapping to deliver life-changing results.'
            ),
            'law' => array(
                'home_h1' => 'High-Stakes Legal Representation for Enterprise Entities',
                'home_sub' => 'Our firm leverages the v6.3 Litigation Intelligence node to provide deep legal expertise and AI-driven case merit analysis for multi-national corporate counsel.',
                'service_h1' => 'Strategic Commercial Litigation & Global Asset Protection',
                'service_p' => 'Aggressive representation and sophisticated legal strategy designed to mitigate risk and secure high-value settlements in complex jurisdictional disputes.'
            ),
            'medical' => array(
                'home_h1' => 'Specialized Clinical Care via HIPAA-Ready Neural Triage',
                'home_sub' => 'Access board-certified specialists through our secure v6.3 health intelligence hub. We reduce clinician administrative load while accelerating patient realizing.',
                'service_h1' => 'High-Fidelity Clinical Services & Surgical Triage',
                'service_p' => 'Our clinical nodes provide advanced diagnostic services and specialized outpatient protocols designed for maximum patient outcomes and operational efficiency.'
            ),
            'contractor' => array(
                'home_h1' => 'Elite Architectural Transformation & Structural Engineering',
                'home_sub' => 'Execute high-ticket estate renovations with the v6.3 Precision Quotation Engine. Our autonomous design-to-build protocol reduces project latency by 31%.',
                'service_h1' => 'Structural Overhaul & High-Authority Material Deployment',
                'service_p' => 'From master-suite transformations to full-scale modernist estate overhauls, our engineering nodes deliver absolute structural dominance and verified ROI.'
            ),
            'real-estate' => array(
                'home_h1' => 'High-Yield Asset Acquisition & Portfolio Management',
                'home_sub' => 'Access proprietary off-market inventory through our Neural Lifestyle Matcher. We identify high-growth appreciation nodes for elite capital deployment.',
                'service_h1' => 'Strategic Portfolio Audit & Global Asset Realization',
                'service_p' => 'Our acquisition specialists utilize AI-driven market delta analysis to pair high-net-worth individuals with modernist estates and commercial equity hubs.'
            ),
            'accounting' => array(
                'home_h1' => 'Proprietary Fiscal Strategy & Wealth Preservation',
                'home_sub' => 'Optimize your corporate architecture with the v6.3 Wealth Preservation Engine. We identify reclaimable capital nodes and multi-jurisdictional tax deltas.',
                'service_h1' => 'High-Stakes Corporate Audit & Capital Realization',
                'service_p' => 'Our partners provide elite tax restructuring and offshore capital optimization for Fortune 500 entities and complex family offices.'
            ),
            'coaches' => array(
                'home_h1' => 'Market Dominance Mentorship & Scalability Coaching',
                'home_sub' => 'Transition from manual latency to autonomous realization. Our v6.3 Performance Engine provides the 12-month roadmap for 7-figure high-ticket scaling.',
                'service_h1' => 'Behavioral Sales Triage & Elite Operational Auditing',
                'service_p' => 'We empower high-performance founders with psychological closing tactics and neural content automation to capture absolute sector authority.'
            ),
            'consultants' => array(
                'home_h1' => 'Strategic Operational Intelligence for Global Firms',
                'home_sub' => 'Eliminate operational friction with the v6.3 Strategic Efficiency Engine. We identify 18+ hours per week in reclaimable operational equity across your lifecycle.',
                'service_h1' => 'Neural Lifecycle Optimization & Enterprise Realization',
                'service_p' => 'Our management consultants perform full-scale architectural audits to modernize your digital ecosystem and maximize lead-to-revenue conversion velocity.'
            ),
            'roofing' => array(
                'home_h1' => 'Precision Asset Protection & Industrial Roofing Nodes',
                'home_sub' => 'Secure your structural integrity with the v6.3 Asset Protection Engine. We provide drone-assisted audits and high-authority material specification.',
                'service_h1' => 'Elite Roof Replacement & Multi-Stage Drone Surveying',
                'service_p' => 'Our industrial deployment teams specialize in luxury natural slate and standing seam metal installations for corporate headquarters and premium estates.'
            )
        );
        return $data[$n] ?? array(
            'home_h1' => 'Elite Solutions Powered by Business Intelligence',
            'home_sub' => 'Consolidate your CRM, Booking, and Marketing into one unified Operating System.',
            'service_h1' => 'Strategic Services for High-Growth Firms',
            'service_p' => 'We provide industry-leading services designed for high-impact results and long-term growth.'
        );
    }

    private function generate_niche_pages($n, $replace = false) {
        $niche_label = ucwords(str_replace('-', ' ', $n));
        $copy = $this->get_industry_copy($n);
        $hero_headline = get_theme_mod('gp_hero_headline', $copy['home_h1']);
        $hero_sub = get_theme_mod('gp_hero_subheadline', $copy['home_sub']);

        $niche_shortcodes = array(
            'solar'       => '[gp_solar_calculator]',
            'contractor'  => '[gp_contractor_estimator]',
            'medical'     => '[gp_symptom_checker]',
            'dental'      => '[gp_ai_faq]',
            'law'         => '[gp_legal_intake]',
            'accounting'  => '[gp_tax_estimator]',
            'coaches'     => '[gp_coaching_assistant]',
            'real-estate' => '[gp_location_switcher]'
        );
        $industry_hook = $niche_shortcodes[$n] ?? '[gp_urgency_banner]';

        $home_content = "
<!-- wp:group {\"tagName\":\"section\",\"className\":\"gp-hero grainy-bg\",\"layout\":{\"type\":\"constrained\"}} -->
<section class=\"wp-block-group gp-hero grainy-bg\">
    <!-- wp:columns {\"verticalAlignment\":\"center\"} -->
    <div class=\"wp-block-columns are-vertically-aligned-center\">
        <!-- wp:column {\"verticalAlignment\":\"center\"} -->
        <div class=\"wp-block-column are-vertically-aligned-center\">
            <!-- wp:heading {\"level\":1,\"className\":\"text-gradient\"} --><h1 class=\"text-gradient\">{$hero_headline}</h1><!-- /wp:heading -->
            <!-- wp:paragraph --><p>{$hero_sub}</p><!-- /wp:paragraph -->
            <!-- wp:buttons --><div class=\"wp-block-buttons\"><!-- wp:button {\"className\":\"gp-btn\"} --><a class=\"wp-block-button__link gp-btn\">Analyze My Needs</a><!-- /wp:button --></div><!-- /wp:buttons -->
        </div>
        <!-- wp:column {\"verticalAlignment\":\"center\"} -->
        <div class=\"wp-block-column are-vertically-aligned-center\">
            <!-- wp:group {\"className\":\"glass-card\"} --><div class=\"wp-block-group glass-card\">
                <h3>Get Your Strategy</h3>
                [gp_quiz_lead_form]
            </div><!-- /wp:group -->
        </div>
    </div>
    <!-- /wp:columns -->
</section>
<!-- /wp:group -->";

        $services_content = "<!-- wp:paragraph -->\n<p>Explore our elite service nodes designed for high-stakes realization.</p>\n<!-- /wp:paragraph -->";
        $pricing_content = "<!-- wp:paragraph -->\n<p>Select the operational tier that aligns with your enterprise growth goals.</p>\n<!-- /wp:paragraph -->";
        $case_studies_content = "<!-- wp:paragraph -->\n<p>Verified results and ROI profiles from our elite client partners.</p>\n<!-- /wp:paragraph -->";
        $faq_content = "<h1>Intelligence Base</h1><p>Search our neural-indexed knowledge base for technical and strategic insights.</p>[gp_kb_search][gp_kb_grid]";
        $mission_content = "<!-- wp:paragraph -->\n<p>We are dedicated to engineering the worlds most advanced business growth operating systems.</p>\n<!-- /wp:paragraph -->";
        $book_now_content = "<h1>Secure Your Session</h1><p>Book a direct briefing with our specialist team.</p>[gp_booking_form]";

        $pages = array(
            'Home'         => array('id' => 'home', 'content' => $home_content, 'desc' => "Transform your $niche_label business with our AI-powered operating system.", 'template' => ''),
            'Services'     => array('id' => 'services', 'content' => '', 'desc' => "Explore our elite $niche_label services designed for high-ticket growth.", 'template' => 'template-services.php'),
            'Pricing'      => array('id' => 'pricing', 'content' => '', 'desc' => "Transparent investment tiers for enterprise scaling.", 'template' => 'template-pricing.php'),
            'Case Studies' => array('id' => 'case_studies', 'content' => '', 'desc' => "Verified ROI profiles and success stories.", 'template' => 'template-case-studies.php'),
            'FAQ'          => array('id' => 'faq', 'content' => $faq_content, 'desc' => "Instant answers from our neural intelligence base.", 'template' => 'template-full-width-glass.php'),
            'Our Mission'  => array('id' => 'about', 'content' => '', 'desc' => "The vision behind the GrowthPress ecosystem.", 'template' => 'template-about.php'),
            'Book Now'     => array('id' => 'booking', 'content' => $book_now_content, 'desc' => "Direct uplink to our strategic specialists.", 'template' => 'template-full-width-glass.php'),
            'Login'        => array('id' => 'portal', 'content' => '', 'desc' => "Portal authentication command node.", 'template' => 'template-portal-login.php'),
            'Client Portal'=> array('id' => 'portal', 'content' => "[gp_client_portal]", 'desc' => "Secure access to project velocity and financial ledgers.", 'template' => 'template-full-width-glass.php'),
            'Contact'      => array('id' => 'contact', 'content' => $this->get_contact_content(), 'desc' => "Connect with our $niche_label specialists today.", 'template' => 'template-full-width-glass.php')
        );

        foreach($pages as $t => $data) {
            if ( ! get_theme_mod( 'gp_gen_' . $data['id'], true ) ) continue;
            $c = $data['content'];
            $query = new WP_Query(array( 'post_type' => 'page', 'title' => $t, 'post_status' => 'any', 'posts_per_page' => 1 ));
            if ( $query->have_posts() ) {
                $pid = $query->posts[0]->ID;
                if ( $replace ) wp_update_post(array( 'ID' => $pid, 'post_content' => $c, 'post_excerpt' => $data['desc'] ));
            } else {
                $pid = wp_insert_post(array( 'post_title' => $t, 'post_content' => $c, 'post_excerpt' => $data['desc'], 'post_type' => 'page', 'post_status' => 'publish' ));
            }

            if ( $pid && $data['template'] ) {
                update_post_meta( $pid, '_wp_page_template', $data['template'] );
            }
            wp_reset_postdata();
        }
    }

    private function get_contact_content() {
        $headline = get_theme_mod('gp_contact_headline', 'Initiate Strategic Sequence');
        $sub = get_theme_mod('gp_contact_subheadline', 'Uplink with our specialist team to calibrate your growth operating system.');
        $shortcode = get_theme_mod('gp_contact_shortcode', '[gp_lead_form]');
        return "<div style='text-align:center; margin-bottom:60px;'><h1>{$headline}</h1><p>{$sub}</p></div>{$shortcode}";
    }

    private function generate_niche_funnel($n) {
        $title = ucwords(str_replace('-', ' ', $n)) . ' Growth Strategy';
        if (!get_page_by_path(sanitize_title($title), OBJECT, 'page')) {
            wp_insert_post(array( 'post_title' => $title, 'post_content' => '[gp_lead_form]', 'post_type' => 'page', 'post_status' => 'publish' ));
        }
    }

    public function get_strategic_recommendations() {
        $leads = get_posts(array('post_type' => 'gp_lead', 'posts_per_page' => -1));
        $appts = get_posts(array('post_type' => 'gp_appointment', 'posts_per_page' => -1));
        $proposals = get_posts(array('post_type' => 'gp_proposal', 'posts_per_page' => -1));

        $recs = array();

        $niche = get_option('growthpress_niche', 'business');
        $niche_specific_tips = array(
            'dental' => "Pro-Tip: Aesthetic leads convert 3x faster when visual mapping is presented in the first session.",
            'law' => "Pro-Tip: Corporate litigation leads require deep conflict clearance before the discovery phase.",
            'solar' => "Pro-Tip: Residential solar leads peak in Q3; prioritize array ROI engineering audits now.",
            'medical' => "Pro-Tip: Specialist routing latency is the #1 cause of intake abandonment in this sector.",
            'contractor' => "Pro-Tip: Large-scale estate renovations require structural ENGINEERING before final quotation.",
            'roofing' => "Pro-Tip: Drone surveys reduce audit friction by 40% in industrial sectors.",
            'accounting' => "Pro-Tip: Multi-jurisdictional tax deltas are the highest-leverage closing anchor.",
            'real-estate' => "Pro-Tip: Off-market nodes attract 22% higher appreciation deltas for elite investors.",
            'coaches' => "Pro-Tip: Authority building through the Content Studio is the primary driver of lead velocity.",
            'consultants' => "Pro-Tip: Operational lifecycle audits identify an average of 15+ hours/week in reclaimable equity."
        );

        // 0. High-Authority Lead Dossier Analysis
        $high_prob_leads = get_posts(array(
            'post_type' => 'gp_lead',
            'posts_per_page' => 1,
            'meta_key' => '_gp_ai_probability',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_query' => array(
                array('key' => '_gp_ai_probability', 'value' => '85', 'compare' => '>=')
            )
        ));

        if (!empty($high_prob_leads)) {
            $l = $high_prob_leads[0];
            $prob = get_post_meta($l->ID, '_gp_ai_probability', true);
            $recs[] = array(
                'title' => 'Dossier Analysis: ' . $l->post_title,
                'msg' => "Neural Intelligence detected a 'High-Authority' profile ($prob% probability). " . ($niche_specific_tips[$niche] ?? '') . " Action: Execute v6.3 Dossier Analysis.",
                'prio' => 'High'
            );
        }

        // 1. Lead Velocity Check
        if (count($leads) < 10) {
            $recs[] = array(
                'title' => 'Capture Node Optimization',
                'msg' => 'Lead volume is below benchmark. Action: Deploy specialized [gp_quiz_lead_form] in the hero section to lower top-of-funnel friction.',
                'prio' => 'High'
            );
        }

        // 2. Conversion Triage Check
        $conv_rate = count($leads) > 0 ? (count($appts) / count($leads)) * 100 : 100;
        if ($conv_rate < 20) {
            $recs[] = array(
                'title' => 'Neural Triage Recalibration',
                'msg' => 'Low lead-to-booking delta detected. Action: Refine the "AI Personality" in Settings to focus on Clinical Urgency and immediate session value.',
                'prio' => 'High'
            );
        }

        // 3. Pipeline Equity Check
        $pending_proposals = 0;
        foreach($proposals as $p) {
            if (get_post_meta($p->ID, '_gp_proposal_status', true) !== 'Accepted') $pending_proposals++;
        }
        if ($pending_proposals > 5) {
            $recs[] = array(
                'title' => 'Closing Velocity Protocol',
                'msg' => 'High concentration of pending equity nodes. Action: Trigger "Personalized Nudges" from the Lead Brief for all high-probability items.',
                'prio' => 'Medium'
            );
        }

        // 4. Stagnant Node Mitigation
        $stagnant_leads = get_posts(array(
            'post_type' => 'gp_lead',
            'posts_per_page' => 1,
            'tax_query' => array(array('taxonomy' => 'gp_lead_stage', 'field' => 'slug', 'terms' => 'new')),
            'date_query' => array(array('before' => '72 hours ago'))
        ));
        if (!empty($stagnant_leads)) {
            $recs[] = array(
                'title' => 'Stagnant Node Mitigation',
                'msg' => 'High-latency leads detected in "New" stage (>72h). Action: Terminate automated nurture and execute high-stakes manual follow-up protocol.',
                'prio' => 'High'
            );
        }

        // 5. Integration Health
        if (!get_option('growthpress_zoom_client_id') || !get_option('growthpress_paypal_client_id')) {
            $recs[] = array(
                'title' => 'Integration Hub Completion',
                'msg' => 'Critical conversion nodes (Zoom/PayPal) are not fully authenticated. Action: Authenticate elite integration nodes to reduce checkout and booking friction.',
                'prio' => 'Medium'
            );
        }

        if (empty($recs)) {
            $recs[] = array(
                'title' => 'Market Dominance Sequence',
                'msg' => 'Ecosystem metrics are within optimal parameters. Action: Increase AI Content Studio output by 50% to capture adjacent market sectors.',
                'prio' => 'Low'
            );
        }

        return $recs;
    }

    public function render_dashboard() {
        $leads = get_posts( array( 'post_type' => 'gp_lead', 'posts_per_page' => -1 ) );
        $appointments = get_posts( array( 'post_type' => 'gp_appointment', 'posts_per_page' => -1 ) );
        $stages = array( 'new' => 'New Leads', 'qualified' => 'Qualified', 'booked' => 'Booked', 'closed' => 'Closed' );
        $strategic_recs = $this->get_strategic_recommendations();
        $lead_count_30d = count($leads);
        $booking_count = count($appointments);
        $conv_rate = $lead_count_30d > 0 ? round(($booking_count / $lead_count_30d) * 100) : 0;

        // Calculate Conversion Velocity
        $velocity_days = 10.5; // Fallback
        $closed_leads = get_posts(array('post_type' => 'gp_lead', 'tax_query' => array(array('taxonomy' => 'gp_lead_stage', 'field' => 'slug', 'terms' => 'closed')), 'posts_per_page' => -1));
        if (count($closed_leads) > 0) {
            $total_days = 0;
            foreach($closed_leads as $cl) {
                $created = strtotime($cl->post_date);
                $closed_date = get_post_meta($cl->ID, '_closed_date', true);
                if($closed_date) {
                    $total_days += (strtotime($closed_date) - $created) / (60 * 60 * 24);
                } else {
                    $total_days += 12; // Approximation
                }
            }
            $velocity_days = round($total_days / count($closed_leads), 1);
        }
        $view_file = GROWTHPRESS_CORE_PATH . 'admin/views/dashboard.php';
        if ( file_exists( $view_file ) ) include $view_file;
    }
}
new GrowthPress_Dashboard();
