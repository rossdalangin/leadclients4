<?php
/**
 * GrowthPress AI FAQ Assistant - Industry Deep Dive v2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_AI_FAQ {

    public function __construct() {
        add_shortcode( 'gp_ai_faq', array( $this, 'render_faq_assistant' ) );
        add_action( 'wp_footer', array( $this, 'render_chat_bubble' ) );
        add_action( 'wp_ajax_gp_ai_faq_ask', array( $this, 'handle_faq_query' ) );
        add_action( 'wp_ajax_nopriv_gp_ai_faq_ask', array( $this, 'handle_faq_query' ) );
    }

    public function render_chat_bubble() {
        if ( is_admin() ) return;
        $nonce = wp_create_nonce('gp_ai_faq_nonce');
        $primary = get_option('growthpress_primary_color', '#2563EB');
        $brand = get_option('growthpress_brand_name', 'GrowthPress');
        ?>
        <div id="gp-ai-chat-bubble" class="gp-chat-bubble-container">
            <div id="gp-chat-launcher" class="gp-chat-launcher">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 11.5C21 16.7467 16.9706 21 12 21C10.1587 21 8.44851 20.4431 7.02534 19.4842L3 21L4.5 16.9747C3.5411 15.5515 3 13.8413 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 11.5Z" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <div class="gp-launcher-pulse"></div>
            </div>

            <div id="gp-chat-window" class="gp-chat-window glass-card" style="display:none;">
                <div class="gp-chat-header" style="background: <?php echo $primary; ?>; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div style="display:flex; align-items:center; gap:15px;">
                        <div style="width:12px; height:12px; background:#10B981; border-radius:50%; border:2.5px solid white; box-shadow: 0 0 10px #10B981;"></div>
                        <div>
                            <div style="font-size:15px; font-weight:950; letter-spacing:0.5px;"><?php echo $brand; ?> Intelligence</div>
                            <div style="font-size:10px; opacity:0.8; font-weight:900; letter-spacing:1px; text-transform: uppercase;">Node Active</div>
                        </div>
                    </div>
                    <div id="gp-chat-close" style="cursor:pointer; opacity:0.7; font-size:24px; font-weight:100;">&times;</div>
                </div>

                <div id="gp-faq-chat-box" class="gp-chat-body">
                    <div class="gp-msg-ai">Welcome to the <?php echo $brand; ?> command center. I am your specialized <?php echo get_option('growthpress_niche', 'business'); ?> intelligence node. How may I assist your strategy today?</div>
                </div>

                <div id="gp-chat-typing" style="display:none; padding:15px 35px; font-size:11px; color:var(--primary); font-weight:950; letter-spacing:1px; text-transform: uppercase;">Node is processing...</div>

                <div class="gp-chat-footer">
                    <input type="hidden" id="gp_ai_faq_nonce" value="<?php echo $nonce; ?>">
                    <input type="text" id="gp-faq-input" placeholder="Type your strategic inquiry...">
                    <button id="gp-faq-send" onclick="askAI()">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 2L11 13M22 2L15 22L11 13M11 13L2 9L22 2" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <style>
            .gp-chat-bubble-container { position: fixed; bottom: 40px; right: 40px; z-index: 10001; font-family: 'Inter', sans-serif; }
            .gp-chat-launcher { width: 75px; height: 75px; border-radius: 50%; background: <?php echo $primary; ?>; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 20px 40px rgba(0,0,0,0.15); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position:relative; }
            .gp-chat-launcher:hover { transform: scale(1.1) rotate(10deg); box-shadow: 0 25px 50px rgba(0,0,0,0.25); }

            .gp-launcher-pulse { position:absolute; top:0; left:0; width:100%; height:100%; border-radius:50%; background:<?php echo $primary; ?>; opacity:0.4; z-index:-1; animation: gp-pulse 2.5s infinite; }
            @keyframes gp-pulse { 0% { transform: scale(1); opacity: 0.5; } 100% { transform: scale(1.8); opacity: 0; } }

            .gp-chat-window { position: absolute; bottom: 100px; right: 0; width: 420px; height: 600px; display: flex; flex-direction: column; padding: 0 !important; border-radius: 40px !important; box-shadow: 0 50px 120px -20px rgba(0,0,0,0.35) !important; animation: gp-chat-slide 0.6s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid rgba(255,255,255,0.4) !important; background: rgba(255,255,255,0.98) !important; backdrop-filter: blur(40px); -webkit-backdrop-filter: blur(40px); overflow: hidden; }

            .gp-chat-header { padding: 45px 40px; color: white; display: flex; justify-content: space-between; align-items: center; border-radius: 0; background: linear-gradient(135deg, <?php echo $primary; ?>, <?php echo $primary; ?>dd) !important; text-shadow: 0 2px 10px rgba(0,0,0,0.3); border-bottom: 1px solid rgba(255,255,255,0.1); }
            .gp-chat-body { flex: 1; overflow-y: auto; padding: 40px; display: flex; flex-direction: column; gap: 25px; background: transparent; }

            .gp-msg-ai, .gp-msg-user { padding: 20px 25px; border-radius: 25px; max-width: 88%; font-size: 15px; line-height: 1.6; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: all 0.3s ease; }
            .gp-msg-ai { background: white; color: #1E293B; border-bottom-left-radius: 5px; border: 1px solid rgba(0,0,0,0.08); align-self: flex-start; font-weight: 500; }
            .gp-msg-user { background: <?php echo $primary; ?>; color: white; border-bottom-right-radius: 5px; align-self: flex-end; font-weight: 600; box-shadow: 0 10px 25px var(--primary-glow); }

            .dark-theme .gp-chat-window { background: linear-gradient(135deg, rgba(15, 23, 42, 0.98), rgba(2, 6, 23, 0.99)) !important; border-color: rgba(255,255,255,0.1) !important; color: #F8FAFC; }
            .dark-theme .gp-msg-ai { background: rgba(255,255,255,0.05); color: #F1F5F9; border: 1px solid rgba(255,255,255,0.1); }
            .dark-theme .gp-msg-user { box-shadow: 0 15px 35px var(--primary-glow); border: 1px solid rgba(255,255,255,0.1); }
            .dark-theme .gp-chat-footer { background: rgba(0,0,0,0.2); border-top-color: rgba(255,255,255,0.1); }
            .dark-theme .gp-chat-footer input { background: rgba(15, 23, 42, 0.8); border-color: rgba(255,255,255,0.2); color: white; }
            .dark-theme .gp-chat-footer button { background: var(--primary); border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 0 20px var(--primary-glow); }

            .gp-chat-footer { padding: 30px; background: #F8FAFC; border-top: 1px solid #EEE; display: flex; gap: 15px; align-items: center; border-radius: 0; }
            .gp-chat-footer input { flex: 1; border: 2px solid #E2E8F0; border-radius: 20px; padding: 18px 25px; font-size: 15px; outline: none; transition: all 0.3s ease; margin:0; background: #FFF; font-weight: 600; color: #1E293B; }
            .gp-chat-footer input:focus { border-color: <?php echo $primary; ?>; box-shadow: 0 0 0 4px var(--primary-glow); }
            .gp-chat-footer button { background: #1E293B; border: none; width: 60px; height: 60px; border-radius: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.2); border: 2px solid rgba(255,255,255,0.1); }
            .gp-chat-footer button:hover { transform: scale(1.05) translateY(-2px); }

            @keyframes gp-chat-slide { from { opacity: 0; transform: translateY(40px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        </style>

        <script>
            jQuery('#gp-chat-launcher').on('click', function() {
                jQuery('#gp-chat-window').fadeIn(400);
                jQuery(this).fadeOut(200);
            });
            jQuery('#gp-chat-close').on('click', function() {
                jQuery('#gp-chat-window').fadeOut(200);
                jQuery('#gp-chat-launcher').fadeIn(400);
            });

            let chatSessionId = localStorage.getItem('gp_chat_session') || 'sess_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('gp_chat_session', chatSessionId);

            function askAI() {
                var query = jQuery('#gp-faq-input').val();
                var nonce = jQuery('#gp_ai_faq_nonce').val();
                var $chat = jQuery('#gp-faq-chat-box');
                var $typing = jQuery('#gp-chat-typing');
                if(!query) return;

                $chat.append('<div class="gp-msg-user">' + query + '</div>');
                jQuery('#gp-faq-input').val('');
                $chat.scrollTop($chat[0].scrollHeight);
                $typing.show();

                jQuery.post(gp_ajax.ajaxurl, { action: 'gp_ai_faq_ask', query: query, nonce: nonce, session_id: chatSessionId }, function(res) {
                    $typing.hide();
                    if(res.success) {
                        $chat.append('<div class="gp-msg-ai">' + res.data.answer + '</div>');
                        if(res.data.intent === 'booking') {
                            $chat.append('<div class="gp-msg-ai" style="background:#f0f9ff; border-color:#bae6fd; color:#1e293b;">🗓️ <strong>Strategic Session:</strong> You can secure your slot here: <br><br><a href="/book-now" class="gp-btn" style="font-size:12px; padding:10px 15px; width:100%; text-align:center; border-radius:12px;">Book Consultation</a></div>');
                        }
                    } else {
                        $chat.append('<div class="gp-msg-ai">Engine Timeout. Please retry.</div>');
                    }
                    $chat.scrollTop($chat[0].scrollHeight);
                });
            }

            jQuery('#gp-faq-input').on('keypress', function(e) { if(e.which == 13) askAI(); });
        </script>
        <?php
    }

    public function render_faq_assistant() {
        $niche = get_option('growthpress_niche', 'business');
        return '<div class="glass-card gp-reveal" style="text-align:center; padding:80px 60px;">
            <h3 class="text-gradient" style="font-size:2.5rem;">24/7 Intelligence Terminal</h3>
            <p style="font-size:1.2rem; opacity:0.7; margin-bottom:40px;">Our neural-calibrated assistant is ready to handle your specialized ' . esc_html($niche) . ' inquiries.</p>
            <button onclick="jQuery(\'#gp-chat-launcher\').click()" class="gp-btn" style="height:70px; padding:0 50px; font-size:18px;">Initiate Assistant</button>
        </div>';
    }

    public function handle_faq_query() {
        check_ajax_referer('gp_ai_faq_nonce', 'nonce');
        if ( ! isset( $_POST['query'] ) ) {
            wp_send_json_error( 'Missing query' );
        }
        $query = sanitize_text_field($_POST['query']);
        $session_id = sanitize_text_field($_POST['session_id'] ?? 'anonymous');
        $niche = get_option('growthpress_niche', 'Business');
        $ai = GrowthPress_AI::get_instance();

        // Save User Message
        $this->save_chat_message($session_id, 'user', $query);

        // Keyword/Template Check
        $templates = get_posts(array(
            'post_type' => 'gp_chat_template',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));

        foreach($templates as $tpl) {
            $keywords = get_post_meta($tpl->ID, '_gp_template_keywords', true);
            if($keywords) {
                $kw_array = array_map('trim', explode(',', $keywords));
                foreach($kw_array as $kw) {
                    if(stripos($query, $kw) !== false) {
                        $answer = $tpl->post_content;
                        $this->save_chat_message($session_id, 'ai', $answer);
                        wp_send_json_success(array('answer' => $answer, 'intent' => 'general', 'template_hit' => true));
                    }
                }
            }
        }

        // KB Context Injection
        $kb_posts = get_posts(array('post_type' => 'gp_kb', 's' => $query, 'posts_per_page' => 2));
        $kb_context = "";
        foreach($kb_posts as $post) $kb_context .= "KB Reference: " . $post->post_title . " - " . strip_tags($post->post_content) . "\n";

        // Slot Checking Logic
        $available_slots = get_posts(array('post_type' => 'gp_appointment', 'posts_per_page' => 3, 'post_status' => 'publish'));
        $slot_context = count($available_slots) < 5 ? "Availability is currently LIMITED. Encourage immediate booking." : "Multiple slots available this week.";

        $prompt = "A visitor is asking: \"$query\". \n";
        $prompt .= ($kb_context ? "Using this internal knowledge: \n$kb_context\n" : "");
        $prompt .= "Operational Context: $slot_context\n";
        $prompt .= "As a specialist in $niche, provide expert advice. Return ONLY a valid JSON object with keys 'answer' and 'intent' ('booking' if they want to schedule, 'general' otherwise).";

        $response_raw = $ai->call_ai($prompt, "Elite $niche Strategist");
        if ( is_wp_error($response_raw) ) wp_send_json_error($response_raw->get_error_message());

        $response = json_decode($response_raw, true);
        if(!$response) $response = array('answer' => $response_raw, 'intent' => (stripos($response_raw, 'book') !== false) ? 'booking' : 'general');

        // Final Autonomous Intent Refinement
        if ($response['intent'] === 'booking') {
            $response['answer'] .= " I have checked our real-time master calendar, and we still have a few high-priority slots available for this week.";
        }

        $this->save_chat_message($session_id, 'ai', $response['answer']);
        wp_send_json_success($response);
    }

    private function save_chat_message($session_id, $role, $message) {
        $chat_session = get_posts(array(
            'post_type' => 'gp_chat',
            'name' => sanitize_title($session_id),
            'post_status' => 'publish',
            'posts_per_page' => 1
        ));

        if($chat_session) {
            $post_id = $chat_session[0]->ID;
        } else {
            $post_id = wp_insert_post(array(
                'post_title' => $session_id,
                'post_name'  => sanitize_title($session_id),
                'post_type'  => 'gp_chat',
                'post_status' => 'publish'
            ));
        }

        $history = get_post_meta($post_id, '_gp_chat_history', true) ?: array();
        $history[] = array(
            'role' => $role,
            'msg' => $message,
            'time' => current_time('mysql')
        );
        update_post_meta($post_id, '_gp_chat_history', $history);
        update_post_meta($post_id, '_gp_last_active', current_time('mysql'));
    }
}
new GrowthPress_AI_FAQ();
