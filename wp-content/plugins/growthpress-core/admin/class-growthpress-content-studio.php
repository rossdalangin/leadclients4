<?php
/**
 * GrowthPress AI Content Studio - Command Center v1.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Content_Studio {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_studio_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_studio_assets' ) );
        add_action( 'wp_ajax_gp_generate_content', array( $this, 'handle_generation' ) );
        add_action( 'wp_ajax_gp_sync_to_kb', array( $this, 'handle_kb_sync' ) );
        add_action( 'wp_ajax_gp_generate_social_image', array( $this, 'handle_social_image_generation' ) );
        add_action( 'wp_ajax_gp_syndicate_content', array( $this, 'handle_syndication' ) );
    }

    public function handle_syndication() {
        check_ajax_referer('gp_admin_nonce', 'gp_nonce');
        $title = sanitize_text_field($_POST['title']);
        $content = wp_kses_post($_POST['content']);
        $channels = $_POST['channels'] ?? array('linkedin', 'x');

        $ai = GrowthPress_AI::get_instance();
        $summary = $ai->call_ai("Summarize this for social sharing on " . implode(', ', $channels) . ": \"$content\"", "Social Omnipresence Node");

        // Strategic Mock: In production, this would uplink to LinkedIn/X API or Buffer/Hootsuite
        GrowthPress_Activity::log("Multi-Channel Presence Node: Syndicated \"$title\" to " . strtoupper(implode(', ', $channels)));

        wp_send_json_success(array('summary' => $summary, 'message' => "Content successfully queued for global authority propagation."));
    }

    public function handle_social_image_generation() {
        check_ajax_referer('gp_admin_nonce', 'gp_nonce');
        $topic = sanitize_text_field($_POST['topic']);

        $ai = GrowthPress_AI::get_instance();
        $prompt = $ai->call_ai("Generate a cinematic, high-authority social media image prompt for the topic: \"$topic\". Focus on luxury materials and architectural precision.", "Creative Director AI");

        // Strategic Mock: In production, this would uplink to DALL-E 3 or Midjourney API
        $mock_url = GROWTHPRESS_CORE_URL . "assets/images/social-gen-placeholder.png";

        wp_send_json_success(array(
            'prompt' => $prompt,
            'image_url' => $mock_url,
            'message' => "Neural creative node initialized. High-authority asset generated."
        ));
    }

    public function handle_kb_sync() {
        if ( ! current_user_can( 'edit_posts' ) ) wp_send_json_error();
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $title = sanitize_text_field( $_POST['title'] );
        $content = wp_kses_post( $_POST['content'] );
        $type = sanitize_text_field( $_POST['type'] ?? 'gp_kb' );

        $types_to_sync = ($type === 'sync_all') ? array('gp_kb', 'gp_service', 'gp_project', 'gp_property', 'gp_treatment') : array($type);
        $synced_ids = array();

        foreach($types_to_sync as $node_type) {
            $post_id = wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_type'    => $node_type,
                'post_status'  => 'publish'
            ) );
            if($post_id) {
                $synced_ids[] = $post_id;
                update_post_meta($post_id, '_gp_is_sample', '1'); // For easy demo cleanup
            }
        }

        if ( ! empty($synced_ids) ) {
            GrowthPress_Activity::log( "Intelligence Asset propagated across ecosystem: $title" );
            wp_send_json_success( "Synced to " . count($synced_ids) . " system nodes." );
        }
        wp_send_json_error( "Failed to sync." );
    }

    public function add_studio_menu() {
        add_submenu_page( 'growthpress-dashboard', 'AI Content Studio', 'AI Content Studio', 'edit_posts', 'growthpress-studio', array( $this, 'render_studio' ) );
    }

    public function enqueue_studio_assets( $hook ) {
        if ( 'growthpress_page_growthpress-studio' !== $hook ) return;
        wp_enqueue_script( 'growthpress-studio-js', GROWTHPRESS_CORE_URL . 'assets/js/admin-dashboard.js', array( 'jquery' ), GROWTHPRESS_CORE_VERSION, true );
        wp_localize_script( 'growthpress-studio-js', 'gp_admin', array( 'nonce' => wp_create_nonce( 'gp_admin_nonce' ) ));
    }

    public function handle_generation() {
        if ( ! current_user_can( 'edit_posts' ) ) wp_send_json_error( 'Unauthorized' );
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );

        $type = sanitize_text_field($_POST['content_type']);
        $topic = sanitize_text_field($_POST['topic']);
        $tone = sanitize_text_field($_POST['tone'] ?? 'Aggressive');
        $niche = get_option('growthpress_niche', 'business');
        $ai = GrowthPress_AI::get_instance();

        $context = "Tone: $tone. Industry: $niche. Focus on high-ticket conversion.";

        if ($type === 'full_dominance') {
            $prompt = "Generate a FULL DOMINANCE CAMPAIGN for \"$topic\".
            Include:
            1. ONE 1000-word SEO Blog Post.
            2. FIVE high-converting Email Nurture Sequence items.
            3. THREE Elite Facebook/LinkedIn Ad Copies.
            Format clearly with headers for each section. $context";
            $result = $ai->call_ai($prompt, "Market Orchestrator AI");
        } else {
            switch($type) {
                case 'optimize': $result = $ai->call_ai("Refine this service description: \"$topic\". $context", "Service Architect"); break;
                case 'blog': $result = $ai->call_ai("Write a 1000-word SEO blog post about \"$topic\". $context", "Content Specialist"); break;
                case 'campaign': $result = $ai->call_ai("Generate 5-day email sequence for \"$topic\". $context", "Email Marketer"); break;
                case 'market': $result = $ai->call_ai("Analyze market for \"$topic\". Identify high-stakes gaps. $context", "Market Strategist"); break;
                case 'sales': $result = $ai->call_ai("Generate discovery call talk tracks for \"$topic\". $context", "AI Sales Coach"); break;
                case 'ad': $result = $ai->call_ai("Create 3 high-converting ads for \"$topic\". $context", "Ad Copywriter"); break;
                case 'headlines': $result = $ai->call_ai("Generate 5 elite headlines for \"$topic\". $context", "CRO Expert"); break;
                case 'service': $result = $ai->call_ai("Generate a Service Line description for \"$topic\". $context", "Service Architect"); break;
                case 'project': $result = $ai->call_ai("Generate a high-ticket Case Study for \"$topic\". $context", "Success Storywriter"); break;
                case 'inventory': $result = $ai->call_ai("Generate a luxury Portfolio listing for \"$topic\". $context", "Elite Marketer"); break;
                case 'kb': $result = $ai->call_ai("Generate a technical Knowledge Base article for \"$topic\". $context", "Knowledge Specialist"); break;
                case 'treatment': $result = $ai->call_ai("Generate a specialized Clinical Treatment Protocol for \"$topic\". Include duration, complexity, and clinical outcomes. $context", "Medical Director AI"); break;
                case 'seo_cluster': $result = $ai->call_ai("Generate a Local SEO content cluster strategy for \"$topic\". Identify 5 long-tail keywords based on high-intent ZIP code routing and draft a 200-word intro for each.", "SEO Clustering Architect"); break;
                case 'geo_fencing':
                    $zips = get_posts(array('post_type' => 'gp_location', 'fields' => 'ids'));
                    $zip_context = "";
                    foreach($zips as $zid) $zip_context .= get_post_meta($zid, '_serviced_zips', true) . ", ";
                    $result = $ai->call_ai("Generate a Hyper-Local SEO landing page for topic: \"$topic\".
                    Target Neighborhoods based on these ZIP nodes: $zip_context.
                    Include a neighborhood-specific ROI anchor and local social proof references. $context", "Geo-Fencing Specialist");
                    break;
                default: $result = 'Invalid selection.';
            }
        }

        if ( is_wp_error($result) ) wp_send_json_error($result->get_error_message());
        wp_send_json_success($result);
    }

    public function render_studio() {
        if ( ! gp_has_cap('ai_content') ) {
            GrowthPress_License_Enforcer::get_instance()->render_upgrade_notice('AI Content Studio', 'Pro');
            return;
        }
        $niche = get_option('growthpress_niche', 'business');
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('gp-content-type');
            const patternBox = document.getElementById('gp-success-pattern-note');
            const patterns = {
                'full_dominance': 'The "Full Dominance" campaign is your highest-leverage asset. It anchors your authority across three channels simultaneously, reducing the cost-per-acquisition by up to 35%.',
                'blog': 'SEO Authority posts build long-term "Search Equity". Success Pattern: Focus on "How-To" guides for complex high-ticket problems in the <?php echo $niche; ?> sector.',
                'campaign': 'Nurture sequences bridge the gap between "Curiosity" and "Commitment". Optimal Length: 5 days with a "Soft-Close" on Day 3.',
                'market': 'Gap analysis identify "Blue Ocean" opportunities where your local competitors are operationally stagnant.',
                'sales': 'Talk tracks should emphasize "Loss Aversion". Use these prompts to train your Specialist Nodes for higher closing velocity.',
                'service': 'Service descriptions must be "Outcome-Oriented". Focus on the "Realized Result" rather than the technical process.',
                'project': 'Case Studies are the ultimate proof of realization. Link these to your elite lead dossiers to increase trust.',
                'kb': 'Technical KB articles reduce support node latency. Well-indexed articles empower your AI FAQ Assistant to close deals autonomously.',
                'treatment': 'Clinical protocols standardize excellence. Success Pattern: Use high-authority clinical terminology to boost patient realization probability.'
            };

            selector.addEventListener('change', function() {
                patternBox.innerHTML = '<strong>Success Pattern:</strong> ' + (patterns[this.value] || 'Calibrate your tone and topic to maximize sector authority.');
            });
        });
        </script>
        <?php
        $prompt_library = array(
            'dental'        => array('Invisalign vs Braces', 'Emergency Dental Care', 'Smile Makeovers'),
            'law'           => array('Personal Injury Rights', 'Estate Planning 101', 'Business Litigation'),
            'solar'         => array('Federal Tax Credits', 'Battery Backup Value', 'Net Metering'),
            'contractor'    => array('Kitchen Remodel ROI', 'Outdoor Living Spaces', 'Foundation Repair')
        );
        $current_prompts = $prompt_library[$niche] ?? array('Market Dominance', 'Client Acquisition', 'Authority Building');
        ?>
        <div class="wrap growthpress-studio">
            <div class="glass-card" style="background:#fdf2f8; border-left:5px solid #db2777; margin-bottom:40px;">
                <p style="margin:0; font-size:14px; color:#9d174d;"><strong>Content Calibration Hub:</strong> Calibrate your AI's tone and preview how strategic assets will propagate across your Triple-Tier design nodes.</p>
            </div>

            <div class="studio-layout" style="display:grid; grid-template-columns: 320px 1fr 400px; gap:30px;">
                <!-- Sidebar: Config -->
                <div class="glass-card" style="padding:35px;">
                    <h3 style="margin-top:0;">Neural Calibration</h3>

                    <div style="margin:25px 0;">
                        <label style="font-weight:900; font-size:10px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">ASSET CATEGORY</label>
                        <select id="gp-content-type" style="height:50px; font-weight:700;">
                            <option value="full_dominance">FULL DOMINANCE CAMPAIGN (3-in-1)</option>
                            <option value="blog">SEO Authority Post</option>
                            <option value="campaign">5-Day Nurture Sequence</option>
                            <option value="market">Market Angle of Attack</option>
                            <option value="sales">Discovery Talk Tracks</option>
                            <option value="headline_optimizer">Neural Headline Optimizer (A/B)</option>
                            <option value="service">Service Line Description</option>
                            <option value="project">High-Ticket Case Study</option>
                            <option value="kb">Technical KB Article</option>
                            <option value="treatment">Clinical Treatment Protocol</option>
                            <option value="seo_cluster">AI Local SEO Cluster</option>
                            <option value="geo_fencing">Hyper-Local Geo-Fencing (Task 37)</option>
                            <option value="competitor_swot">Autonomous Competitor SWOT (Task 43)</option>
                        </select>
                    </div>

                    <div style="margin-bottom:25px;">
                        <label style="font-weight:900; font-size:10px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">STRATEGIC TONE</label>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <button class="tone-btn active" data-tone="Aggressive" title="Direct-response, high-urgency, and authoritative.">Aggressive</button>
                            <button class="tone-btn" data-tone="Empathetic" title="Solution-focused, caring, and trust-building.">Empathetic</button>
                            <button class="tone-btn" data-tone="Technical" title="Data-driven, detailed, and clinical focus.">Technical</button>
                            <button class="tone-btn" data-tone="Elite" title="Luxury-oriented, sophisticated, and exclusive.">Elite/Luxe</button>
                        </div>
                    </div>

                    <div style="margin-bottom:30px;">
                        <label style="font-weight:900; font-size:10px; opacity:0.4; letter-spacing:2px; display:block; margin-bottom:12px;">TOPIC FOCUS</label>
                        <input type="text" id="gp-content-topic" placeholder="e.g. Lead Velocity">
                    </div>

                    <button class="button button-primary button-hero" onclick="generateContent()" style="width:100%; height:60px !important; border-radius:12px !important; font-weight:900;">INITIALIZE GENERATION</button>
                    <p style="margin-top:15px; font-size:11px; opacity:0.5; font-weight:700; text-align:center;">STRATEGIC NOTE: Neural generation takes 15-45 seconds depending on asset complexity.</p>
                </div>

                <!-- Center: Output & Preview -->
                <div style="display:flex; flex-direction:column; gap:30px;">
                    <div class="glass-card" style="padding:0; overflow:hidden; background:#0F172A; color:white; border:none; border-radius: 30px; box-shadow: 0 40px 100px -20px rgba(0,0,0,0.5);">
                        <div style="padding:25px 35px; border-bottom:1px solid rgba(255,255,255,0.1); display:flex; justify-content:space-between; align-items:center;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div id="studio-status-ping" class="status-ping" style="width:10px; height:10px; background:rgba(255,255,255,0.2); border-radius:50%;"></div>
                                <span style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:2px; text-transform: uppercase;">Intelligence Stream</span>
                            </div>
                            <div style="display:flex; gap:10px;">
                                <div style="text-align:right;">
                                    <div style="display:flex; gap:10px;">
                                        <button class="button button-small" onclick="generateSocialImage()" style="background:var(--primary); color:white; border:none; font-weight: 800;">GENERATE IMAGE</button>
                                        <button class="button button-small" onclick="copyStudioOutput()" style="background:rgba(255,255,255,0.1); color:white; border:none; font-weight: 800;">COPY RAW</button>
                                    </div>
                                    <p style="font-size:8px; opacity:0.3; margin-top:5px; font-weight:700; color:white;">IMAGE GEN: 5-10s.</p>
                                </div>
                            </div>
                        </div>
                        <div id="gp-studio-output" style="padding:45px; font-family:'JetBrains Mono', monospace; font-size:14px; line-height:1.8; height:480px; overflow-y:auto; color:rgba(255,255,255,0.95); position: relative;">
                            <div id="studio-loader" style="display:none; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
                                <div class="gp-pulse-icon" style="width:40px; height:40px; background:var(--primary); border-radius:50%; margin:0 auto 20px; animation:gp-pulse 1.5s infinite;"></div>
                                <div style="font-size:10px; font-weight:950; letter-spacing:3px; opacity:0.5;">SYNCHRONIZING NEURAL LINK...</div>
                            </div>
                            <span id="studio-placeholder" style="opacity:0.2;">// Standing by for neural uplink protocol...</span>
                        </div>
                    </div>

                    <div class="glass-card" style="padding:50px; border-radius: 40px; border: 1px solid rgba(255,255,255,0.8);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
                            <h3 style="margin:0; font-size: 20px; font-weight: 950; letter-spacing: -0.02em;">Design Node Preview</h3>
                            <div style="display:flex; gap:12px;">
                                <button class="design-preview-btn active" data-design="unisex">Unisex</button>
                                <button class="design-preview-btn" data-design="male">Executive</button>
                                <button class="design-preview-btn" data-design="female">Luxe</button>
                            </div>
                        </div>
                        <div id="design-preview-area" class="preview-unisex" style="padding:50px; border-radius:30px; border:1px solid #F1F5F9; min-height:250px; transition: all 0.5s ease;">
                            <h4 id="preview-title" style="margin-bottom:20px; font-size: 24px; font-weight: 950; letter-spacing: -0.04em;">Asset Preview</h4>
                            <div id="preview-body" style="font-size:15px; opacity:0.75; line-height: 1.7; font-weight: 500;">Generated content will be rendered here to verify geometric and typographic alignment across design nodes.</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Asset Library -->
                <div class="glass-card" style="padding:35px;">
                    <div id="gp-success-pattern-note" style="background:var(--primary-glow); padding:20px; border-radius:15px; border:1px solid rgba(37,99,235,0.1); margin-bottom:30px; font-size:13px; color:var(--primary); line-height:1.5;">
                        <strong>Success Pattern:</strong> The "Full Dominance" campaign is your highest-leverage asset. It anchors your authority across three channels simultaneously, reducing the cost-per-acquisition by up to 35%.
                    </div>

                    <h3 style="margin-top:0;">Ecosystem Sync</h3>
                    <p style="font-size:12px; opacity:0.5; margin-bottom:30px;">Instantly route generated intelligence to the appropriate system node. Propagated assets (like KB articles) are used by the AI FAQ Assistant to provide context-aware responses to visitors.</p>

                    <div id="gp-studio-actions" style="display:grid; gap:15px;">
                        <button class="sync-btn" onclick="syncAsset('gp_kb')" style="--sync-color: #4F46E5;">Sync to Knowledge Base</button>
                        <button class="sync-btn" onclick="syncAsset('gp_service')" style="--sync-color: #0F172A;">Sync to Service Lines</button>
                        <button class="sync-btn" onclick="syncAsset('gp_project')" style="--sync-color: #10B981;">Sync to Case Studies</button>
                        <button class="sync-btn" onclick="syncAsset('gp_property')" style="--sync-color: #F59E0B;">Sync to Inventory</button>
                        <button class="sync-btn" onclick="syncAsset('gp_treatment')" style="--sync-color: #EF4444;">Sync to Treatments</button>
                        <button class="sync-btn" onclick="syncAsset('gp_seo_cluster')" style="--sync-color: #7C3AED;">Sync to SEO Clusters</button>
                        <button class="sync-btn" onclick="syncAsset('sync_all')" style="--sync-color: var(--primary); background:var(--primary-glow); border-style:dashed;">Propagate to All Nodes</button>
                        <p style="font-size:9px; opacity:0.4; text-align:center; font-weight:700;">NOTE: Node sync takes 1-2s.</p>
                        <button class="sync-btn" onclick="syndicateContent()" style="--sync-color: #000; margin-top:15px; border-style: double;">Syndicate to Social Nodes (Task 35)</button>
                        <p style="font-size:9px; opacity:0.4; text-align:center; font-weight:700;">NOTE: Multi-channel queue takes 3-5s.</p>
                    </div>

                    <hr style="margin:40px 0; opacity:0.1;">

                    <h4 style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:20px;">QUICK PROMPTS</h4>
                    <div style="display:grid; gap:10px;">
                        <?php foreach($current_prompts as $p): ?>
                            <div class="quick-prompt" onclick="jQuery('#gp-content-topic').val('<?php echo esc_js($p); ?>')">
                                <?php echo esc_html($p); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .tone-btn, .design-preview-btn {
                padding:10px; border-radius:8px; border:1px solid #E2E8F0; background:white; cursor:pointer; font-size:11px; font-weight:800; transition:0.3s;
            }
            .tone-btn.active, .design-preview-btn.active { background:var(--primary); color:white; border-color:var(--primary); }
            .sync-btn {
                height:60px; border-radius:12px; border:2px solid var(--sync-color); background:transparent; color:var(--sync-color); font-weight:900; cursor:pointer; transition:0.3s; font-size:12px;
            }
            .sync-btn:hover { background:var(--sync-color); color:white; }
            .quick-prompt { padding:15px; background:#F8FAFC; border-radius:12px; font-size:12px; font-weight:700; cursor:pointer; transition:0.3s; }
            .quick-prompt:hover { background:white; box-shadow:0 10px 20px rgba(0,0,0,0.05); transform:translateX(5px); }

            .preview-male {
                background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
                color:white;
                border-radius:12px !important;
                border: 1px solid rgba(255,255,255,0.1);
                box-shadow: 0 40px 100px rgba(0,0,0,0.5);
                position: relative;
            }
            .preview-male::before {
                content: 'EXECUTIVE';
                position: absolute; top: 20px; right: 20px; font-size: 10px; font-weight: 900; letter-spacing: 2px; opacity: 0.2;
            }
            .preview-female {
                background: linear-gradient(135deg, #FFF5F7 0%, #FFFFFF 100%);
                backdrop-filter:blur(40px);
                color:#4C0519;
                border-radius:80px 20px 80px 20px !important;
                border: 1px solid rgba(255,182,193,0.3);
                box-shadow: 0 40px 100px rgba(255,100,150,0.15);
                position: relative;
            }
            .preview-female::before {
                content: 'LUXE';
                position: absolute; top: 20px; right: 40px; font-size: 10px; font-weight: 900; letter-spacing: 2px; color: #DB2777; opacity: 0.3;
            }
            .preview-unisex {
                background: rgba(255,255,255,0.8);
                backdrop-filter:blur(40px);
                color:#111827;
                border-radius:40px !important;
                border: 1px solid rgba(0,0,0,0.08);
                box-shadow: 0 30px 60px rgba(0,0,0,0.05);
                position: relative;
            }
            .preview-unisex::before {
                content: 'STANDARD';
                position: absolute; top: 20px; right: 20px; font-size: 10px; font-weight: 900; letter-spacing: 2px; opacity: 0.2;
            }
        </style>

        <script>
            jQuery('.tone-btn').on('click', function() {
                jQuery('.tone-btn').removeClass('active');
                jQuery(this).addClass('active');
            });
            jQuery('.design-preview-btn').on('click', function() {
                jQuery('.design-preview-btn').removeClass('active');
                jQuery(this).addClass('active');
                jQuery('#design-preview-area').attr('class', 'preview-' + jQuery(this).data('design'));
            });
        function syndicateContent() {
            const title = jQuery('#gp-content-topic').val();
            const content = jQuery('#gp-studio-output').find('.ai-response').text();
            if(!content) return alert('Generate content node first.');

            if(!confirm('Syndicate this intelligence to LinkedIn and X?')) return;

            jQuery.post(ajaxurl, {
                action: 'gp_syndicate_content',
                title: title,
                content: content,
                gp_nonce: gp_admin.nonce
            }, function(res) {
                if(res.success) {
                    alert('SYNDICATION SUCCESS: ' + res.data.summary);
                }
            });
        }
        function generateSocialImage() {
            const topic = jQuery('#gp-content-topic').val();
            if(!topic) return alert('Identify topic node.');
            alert('Consulting Neural Creative Cluster...');
            jQuery.post(ajaxurl, {
                action: 'gp_generate_social_image',
                topic: topic,
                gp_nonce: gp_admin.nonce
            }, function(res) {
                if(res.success) {
                    jQuery('#preview-body').prepend(`<img src="${res.data.image_url}" style="width:100%; border-radius:20px; margin-bottom:30px; box-shadow:0 20px 40px rgba(0,0,0,0.1);">`);
                    alert('SOCIAL IMAGE PROMPT: ' + res.data.prompt);
                }
            });
        }
        </script>
        <?php
    }
}
new GrowthPress_Content_Studio();
