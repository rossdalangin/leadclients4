<?php
/**
 * GrowthPress Frontend Display Shortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Display {

    public function __construct() {
        add_shortcode( 'gp_kb_grid', array( $this, 'render_kb_grid' ) );
        add_shortcode( 'gp_case_study_grid', array( $this, 'render_case_study_grid' ) );
        add_shortcode( 'gp_staff_grid', array( $this, 'render_staff_grid' ) );
        add_shortcode( 'gp_service_grid', array( $this, 'render_service_grid' ) );
        add_shortcode( 'gp_inventory_grid', array( $this, 'render_inventory_grid' ) );
        add_shortcode( 'gp_location_grid', array( $this, 'render_location_grid' ) );
        add_shortcode( 'gp_funnel_grid', array( $this, 'render_funnel_grid' ) );
        add_shortcode( 'gp_treatment_grid', array( $this, 'render_treatment_grid' ) );
        add_shortcode( 'gp_ecosystem_radar', array( $this, 'render_ecosystem_radar' ) );
        add_shortcode( 'gp_market_chart', array( $this, 'render_market_chart' ) );
        add_shortcode( 'gp_kb_search', array( $this, 'render_kb_search' ) );
        add_shortcode( 'gp_treatment_search', array( $this, 'render_treatment_search' ) );
        add_action( 'wp_ajax_gp_kb_ai_search', array( $this, 'handle_ai_search' ) );
        add_action( 'wp_ajax_nopriv_gp_kb_ai_search', array( $this, 'handle_ai_search' ) );
    }

    public function render_kb_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_kb', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Knowledge Base', 'gp_kb' );
    }

    public function render_case_study_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_project', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Success Stories', 'gp_project' );
    }

    public function render_staff_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_staff', 'posts_per_page' => -1 ) );
        return $this->render_grid( $posts, 'Strategic Team Nodes', 'gp_staff' );
    }

    public function render_service_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_service', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Service Lines', 'gp_service' );
    }

    public function render_inventory_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_property', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Portfolio Inventory', 'gp_property' );
    }

    public function render_location_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_location', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Service Locations', 'gp_location' );
    }

    public function render_funnel_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_funnel', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Conversion Funnels', 'gp_funnel' );
    }

    public function render_treatment_grid() {
        $posts = get_posts( array( 'post_type' => 'gp_treatment', 'posts_per_page' => 6 ) );
        return $this->render_grid( $posts, 'Clinical Protocols', 'gp_treatment' );
    }

    public function render_ecosystem_radar() {
        $cpts = array(
            'Leads' => 'gp_lead', 'Bookings' => 'gp_appointment', 'Equity' => 'gp_proposal',
            'Revenue' => 'gp_transaction', 'Locations' => 'gp_location', 'Funnels' => 'gp_funnel',
            'Tasks' => 'gp_task', 'KB' => 'gp_kb', 'Services' => 'gp_service',
            'Cases' => 'gp_project', 'Reviews' => 'gp_review', 'Inventory' => 'gp_property',
            'Clinical' => 'gp_treatment', 'Specialists' => 'gp_staff'
        );
        $counts = array();
        foreach($cpts as $label => $type) $counts[$label] = wp_count_posts($type)->publish;

        ob_start(); ?>
        <div class="gp-ecosystem-radar glass-card gp-reveal" style="padding:60px; text-align:center; margin:40px 0;">
            <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">SYSTEM AUTHORITY MATRIX</div>
            <h3 class="text-gradient" style="font-size:2.5rem; margin:0 0 40px 0;">Ecosystem Authority Radar</h3>
            <div style="max-width:600px; margin:0 auto;">
                <canvas id="ecosystemRadarFrontend" height="400"></canvas>
            </div>
            <script>
            jQuery(document).ready(function($) {
                const ctx = document.getElementById('ecosystemRadarFrontend').getContext('2d');
                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: <?php echo json_encode(array_keys($counts)); ?>,
                        datasets: [{
                            label: 'Node Authority',
                            data: <?php echo json_encode(array_values($counts)); ?>,
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: '#4F46E5',
                            pointBackgroundColor: '#4F46E5',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            r: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                pointLabels: { font: { weight: 'bold', size: 10 } }
                            }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            });
            </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_market_chart() {
        ob_start(); ?>
        <div class="gp-market-chart glass-card gp-reveal" style="padding:80px; margin:60px 0; border-radius: 60px;">
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:60px;">
                <div>
                    <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:5px; margin-bottom:15px;">Cinematic Data Visualization</div>
                    <h3 class="text-gradient" style="margin:0; font-size: 3.5rem; letter-spacing: -0.06em; line-height: 1;">Strategic Sector Trajectory</h3>
                </div>
                <div style="text-align:right; display:flex; gap:30px;">
                    <div>
                        <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px;">SECTOR DOMINANCE</div>
                        <div style="font-size:24px; font-weight:950; color:var(--primary);">94.2%</div>
                    </div>
                    <div>
                        <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px;">EQUITY GROWTH</div>
                        <div style="font-size:24px; font-weight:950; color:#10B981;">+32.8%</div>
                    </div>
                </div>
            </div>
            <div style="height:400px; width:100%;">
                <canvas id="gpMarketChart"></canvas>
            </div>
            <script>
            jQuery(document).ready(function($) {
                const ctx = document.getElementById('gpMarketChart').getContext('2d');
                const gradient1 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient1.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
                gradient1.addColorStop(1, 'rgba(79, 70, 229, 0)');

                const gradient2 = ctx.createLinearGradient(0, 0, 0, 400);
                gradient2.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                gradient2.addColorStop(1, 'rgba(16, 185, 129, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [
                            {
                                label: 'Market Authority %',
                                data: [62, 68, 75, 72, 85, 89, 94],
                                borderColor: '#4F46E5',
                                borderWidth: 4,
                                tension: 0.45,
                                fill: true,
                                backgroundColor: gradient1,
                                pointBackgroundColor: '#FFF',
                                pointBorderColor: '#4F46E5',
                                pointBorderWidth: 3,
                                pointRadius: 5,
                                pointHoverRadius: 8
                            },
                            {
                                label: 'Acquisition Velocity',
                                data: [35, 45, 52, 65, 60, 78, 88],
                                borderColor: '#10B981',
                                borderWidth: 4,
                                borderDash: [5, 5],
                                tension: 0.45,
                                fill: true,
                                backgroundColor: gradient2,
                                pointBackgroundColor: '#FFF',
                                pointBorderColor: '#10B981',
                                pointBorderWidth: 3,
                                pointRadius: 5
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { display: false },
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: '900', family: 'Inter', size: 11 }, color: '#94A3B8' }
                            }
                        }
                    }
                });
            });
            </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_treatment_search() {
        ob_start(); ?>
        <div class="gp-treatment-ai-search glass-card" style="padding:60px; max-width:800px; margin:40px auto; border-top: 10px solid var(--primary);">
            <h3 class="text-gradient" style="text-align:center; font-size:2.5rem; margin-bottom:30px;">Clinical Intel Search</h3>
            <div style="position:relative;">
                <input type="text" id="gp-treat-query" placeholder="Search specialized clinical protocols..." style="width:100%; height:70px; border-radius:18px; border:2px solid var(--border); padding:0 30px; font-size:16px;">
                <button onclick="runTreatSearch()" class="gp-btn" style="position:absolute; right:10px; top:10px; height:50px; border-radius:12px; padding:0 25px;">SCAN</button>
            </div>
            <div id="treat-ai-results" style="margin-top:40px; display:none;">
                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-bottom:20px;">NEURAL PROTOCOL RECOMMENDATION</div>
                <div id="treat-ai-output" style="line-height:1.7; font-size:15px; background:#F0F9FF; padding:30px; border-radius:20px; border:1px solid #BAE6FD; color:#0369A1;"></div>
            </div>
        </div>
        <script>
        function runTreatSearch() {
            const q = jQuery('#gp-treat-query').val();
            const out = jQuery('#treat-ai-results').fadeIn().find('#treat-ai-output');
            out.text('CONSULTING CLINICAL REPOSITORY...').css('opacity', 0.5);
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_kb_ai_search', query: q }, function(res) {
                out.html(res.data).css('opacity', 1);
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function render_kb_search() {
        ob_start(); ?>
        <script>
        function gpLogHighIntent(action) {
            const email = localStorage.getItem('gp_lead_email') || 'anonymous@visitor.com';
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_log_behavior', page: 'INTENT: ' + action, email: email });
        }
        </script>
        <div class="gp-kb-ai-search glass-card gp-reveal" style="padding:80px 60px; max-width:1000px; margin:40px auto; border-radius: 48px; box-shadow: 0 40px 100px -20px var(--primary-glow);">
            <div style="text-align:center; margin-bottom:50px;">
                <span class="eyebrow">NEURAL RETRIEVAL HUB</span>
                <h3 class="text-gradient headline-lg" style="margin-bottom:20px;">Intelligence Network Search</h3>
                <p style="opacity:0.6; font-weight:600;">Directly query our indexed technical repository and strategic blueprints.</p>
            </div>
            <div style="position:relative; max-width:800px; margin:0 auto;">
                <input type="text" id="gp-kb-query" placeholder="Ask a technical or strategic question..." style="width:100%; height:80px; border-radius:24px; border:2px solid var(--border); padding:0 40px; font-size:18px; font-weight:600; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                <button onclick="runKBSearch()" class="gp-btn" style="position:absolute; right:12px; top:12px; height:56px; border-radius:16px; padding:0 40px; font-size:12px;">EXECUTE SCAN</button>
            </div>
            <div id="kb-ai-results" style="margin-top:60px; display:none; max-width:800px; margin-left:auto; margin-right:auto;">
                <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:3px; margin-bottom:25px; display:flex; align-items:center; gap:12px;">
                    <div style="width:10px; height:10px; background:#10B981; border-radius:50%; box-shadow:0 0 10px #10B981;"></div> NEURAL INFERENCE RESULT
                </div>
                <div id="kb-ai-output" style="line-height:1.8; font-size:16px; background:rgba(248,250,252,0.8); padding:40px; border-radius:32px; border:1px solid #E2E8F0; color:var(--text); font-weight:500; box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);"></div>
                <div style="margin-top:30px; text-align:center;">
                    <p style="font-size:11px; opacity:0.4; font-weight:800; letter-spacing:1px;">LATENCY: 142MS | SOURCE: INTERNAL KB NODES</p>
                </div>
            </div>
        </div>
        <script>
        function runKBSearch() {
            const q = jQuery('#gp-kb-query').val();
            gpLogHighIntent('KB Search: ' + q);
            const out = jQuery('#kb-ai-results').fadeIn().find('#kb-ai-output');
            out.text('CONSULTING INTERNAL KNOWLEDGE BASE...').css('opacity', 0.5);
            jQuery.post(gp_ajax.ajaxurl, { action: 'gp_kb_ai_search', query: q }, function(res) {
                out.html(res.data).css('opacity', 1);
            });
        }
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_ai_search() {
        $query = sanitize_text_field($_POST['query']);
        $kb_posts = get_posts(array('post_type' => 'gp_kb', 's' => $query, 'posts_per_page' => 3));
        $context = "";
        foreach($kb_posts as $post) $context .= $post->post_title . ": " . strip_tags($post->post_content) . "\n";

        $ai = GrowthPress_AI::get_instance();
        $prompt = "Based on this internal knowledge base data: \n$context\n\n Answer the visitor's question: \"$query\". Provide a professional, system-specific response.";
        $response = $ai->call_ai($prompt, "GrowthPress Knowledge Specialist");

        if ( is_wp_error($response) ) wp_send_json_error();
        wp_send_json_success(nl2br($response));
    }

    private function render_grid( $posts, $title, $type = '' ) {
        if ( empty( $posts ) ) return '';
        ob_start(); ?>
        <style>
            .gp-grid-item {
                transition: transform 0.6s var(--ease-out-expo), box-shadow 0.6s var(--ease-out-expo), border-color 0.4s ease;
                background: linear-gradient(135deg, rgba(var(--surface-rgb), 0.9) 0%, rgba(var(--surface-rgb), 0.7) 100%) !important;
                border: 1px solid var(--glass-border) !important;
            }
            .gp-grid-item:hover {
                transform: translateY(-15px) scale(1.02);
                border-color: var(--primary) !important;
                box-shadow: 0 40px 80px -20px rgba(0,0,0,0.12), 0 20px 40px var(--primary-glow) !important;
            }
            .gp-grid-item .gp-card-footer {
                margin-top: auto;
                padding-top: 30px;
                border-top: 1px solid var(--border);
                display: flex;
                flex-direction: column;
                gap: 20px;
            }
            .gp-grid-item:hover .gp-btn-card {
                background: var(--primary) !important;
                transform: translateY(-3px);
            }
            .gp-btn-card {
                background: var(--secondary) !important;
                text-align: center;
                padding: 18px !important;
                font-size: 12px !important;
                border-radius: 15px !important;
                transition: 0.3s var(--ease-out-expo) !important;
            }
        </style>
        <div class="gp-content-grid-wrapper" style="margin: 100px 0;">
            <div style="text-align: center; margin-bottom: 80px;">
                <span class="eyebrow">ECOSYSTEM NODES</span>
                <h2 class="text-gradient headline-lg"><?php echo esc_html( $title ); ?></h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 50px;">
                <?php $i = 0; foreach ( $posts as $p ) : $i++; $stagger = ($i % 3) + 1; ?>
                    <div class="glass-card gp-grid-item gp-reveal gp-reveal-stagger-<?php echo $stagger; ?>" style="padding: 50px; border-radius: 40px; display: flex; flex-direction: column; position: relative; overflow: hidden;">

                        <?php
                        $badge_text = '';
                        if ( $type === 'gp_project' ) { $badge_text = get_post_meta($p->ID, '_gp_growth_roi', true) . ' ROI'; }
                        elseif ( $type === 'gp_kb' ) { $badge_text = get_post_meta($p->ID, '_kb_intel_level', true); }
                        elseif ( $type === 'gp_treatment' ) { $badge_text = get_post_meta($p->ID, '_treatment_complexity', true); }
                        elseif ( $type === 'gp_location' ) { $badge_text = 'ACTIVE HUB'; }

                        if ($badge_text) : ?>
                            <div class="gp-intel-badge" style="position: absolute; top: 30px; right: 30px; background: var(--primary); color: white; padding: 7px 18px; border-radius: 30px; font-size: 10px; font-weight: 950; z-index: 10; letter-spacing: 1.5px; text-transform: uppercase; box-shadow: 0 10px 20px var(--primary-glow);"><?php echo esc_html($badge_text); ?></div>
                        <?php endif; ?>

                        <?php if ( has_post_thumbnail( $p->ID ) ) : ?>
                            <div style="margin: -50px -50px 40px -50px; height: 260px; overflow: hidden; position: relative;">
                                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 60%, rgba(var(--surface-rgb), 0.9)); z-index: 1;"></div>
                                <?php echo get_the_post_thumbnail( $p->ID, 'medium_large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s var(--ease-out-expo);', 'class' => 'gp-card-img' ) ); ?>
                            </div>
                        <?php elseif ( $type === 'gp_service' ) :
                            $icon = get_post_meta($p->ID, '_gp_service_icon', true) ?: '💎'; ?>
                            <div style="margin: -50px -50px 40px -50px; height: 260px; background: var(--primary-glow); display: flex; align-items: center; justify-content: center; font-size: 7rem; position: relative; overflow: hidden;">
                                <div style="position: absolute; top: -20px; right: -20px; font-size: 15rem; opacity: 0.03; transform: rotate(15deg);"><?php echo $icon; ?></div>
                                <span style="position: relative; z-index: 2;"><?php echo $icon; ?></span>
                            </div>
                        <?php endif; ?>

                        <div style="display: flex; flex-direction: column; flex: 1;">
                            <h3 style="margin: 0 0 20px 0; font-size: 26px; font-weight: 950; letter-spacing: -0.03em; line-height: 1.2; color: var(--secondary);"><?php echo esc_html( $p->post_title ); ?></h3>

                            <?php if ( $type === 'gp_staff' ) :
                                $exp = get_post_meta($p->ID, '_staff_expertise', true);
                                $perf_json = get_post_meta($p->ID, '_staff_performance_json', true);
                                $perf = json_decode($perf_json, true) ?: ['efficiency' => rand(85, 95)];
                                ?>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; background: rgba(0,0,0,0.02); padding: 12px 20px; border-radius: 12px;">
                                    <?php if ($exp) : ?>
                                        <div style="font-size: 11px; font-weight: 950; color: var(--primary); text-transform: uppercase; letter-spacing: 2px;"><?php echo esc_html($exp); ?></div>
                                    <?php endif; ?>
                                    <div style="font-size: 10px; font-weight: 950; color: #10B981; display:flex; align-items:center; gap:5px;"><span style="width:6px; height:6px; background:#10B981; border-radius:50%;"></span> <?php echo $perf['efficiency']; ?>% EFFICIENCY</div>
                                </div>
                            <?php endif; ?>

                            <div style="font-size: 16px; opacity: 0.75; line-height: 1.7; margin-bottom: 35px; flex: 1; font-weight: 500;">
                                <?php echo wp_trim_words( $p->post_content, 25 ); ?>
                            </div>

                            <div class="gp-card-footer">
                                <?php
                                $meta_html = '';
                                if ( $type === 'gp_project' ) {
                                    $val = get_post_meta($p->ID, '_gp_pipeline_value', true);
                                    if ($val) $meta_html = '<div style="display:flex; flex-direction:column;"><span style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing:1px;">PIPELINE EQUITY</span><span style="font-size: 20px; font-weight: 950; color: var(--primary); letter-spacing:-0.03em;">'.esc_html($val).'</span></div>';
                                } elseif ( $type === 'gp_property' ) {
                                    $price = get_post_meta($p->ID, '_gp_price', true);
                                    if ($price) $meta_html = '<div style="display:flex; flex-direction:column;"><span style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing:1px;">ASSET VALUATION</span><span style="font-size: 20px; font-weight: 950; color: #10B981; letter-spacing:-0.03em;">$'.number_format($price).'</span></div>';
                                } elseif ( $type === 'gp_treatment' ) {
                                    $dur = get_post_meta($p->ID, '_treatment_duration', true);
                                    if ($dur) $meta_html = '<div style="display:flex; flex-direction:column;"><span style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing:1px;">CLINICAL DURATION</span><span style="font-size: 20px; font-weight: 950; color: var(--primary); letter-spacing:-0.03em;">'.esc_html($dur).'</span></div>';
                                } elseif ( $type === 'gp_funnel' ) {
                                    $a = (int)get_post_meta($p->ID, '_hits_A', true);
                                    $b = (int)get_post_meta($p->ID, '_hits_B', true);
                                    $meta_html = '<div style="display:flex; flex-direction:column;"><span style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing:1px;">TRAFFIC VELOCITY</span><span style="font-size: 20px; font-weight: 950; color: var(--primary); letter-spacing:-0.03em;">'.($a+$b).' HITS</span></div>';
                                }

                                if ($meta_html) : ?>
                                    <div style="padding: 20px 25px; background: var(--primary-glow); border-radius: 20px; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(79, 70, 229, 0.1);">
                                        <?php echo $meta_html; ?>
                                        <div style="width:40px; height:40px; background:var(--surface); border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow:0 10px 20px rgba(0,0,0,0.05); font-size:18px;">📈</div>
                                    </div>
                                <?php endif; ?>

                                <a href="<?php echo get_permalink( $p->ID ); ?>" class="gp-btn gp-btn-card">EXPLORE INTEL NODE</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}
new GrowthPress_Display();
