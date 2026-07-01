<?php
/**
 * The Front Page Template - GrowthPress v6.3 Elite
 */
get_header();

$niche = get_option('growthpress_niche', 'business');
$hero_headline = get_theme_mod('gp_hero_headline', 'Transform Your Business with AI Intelligence');
$hero_sub = get_theme_mod('gp_hero_subheadline', 'The unified operating system for high-ticket service firms. Scale faster, automate smarter.');
?>

<main id="primary" class="site-main">

    <!-- Cinematic Hero Section -->
    <section class="gp-hero grainy-bg" style="padding: 160px 0 120px; overflow: hidden; position: relative;">
        <!-- Adaptive Glow Node -->
        <div style="position: absolute; top: -10%; right: -5%; width: 50%; height: 70%; background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%); opacity: 0.5; filter: blur(100px); pointer-events: none;"></div>
        <div class="container">
            <div class="wp-block-columns are-vertically-aligned-center" style="gap: 100px;">
                <div class="wp-block-column gp-reveal" style="flex-basis: 55%;">
                    <span class="eyebrow"><?php echo esc_html(get_theme_mod('gp_hero_eyebrow', 'ELITE BUSINESS OS v6.3')); ?></span>
                    <h1 class="text-gradient headline-xl" style="margin-bottom: 40px;"><?php echo esc_html(get_theme_mod('gp_hero_headline', $hero_headline)); ?></h1>
                    <p style="font-size: 1.6rem; opacity: 0.8; margin-bottom: 50px; font-weight: 500; line-height: 1.4;"><?php echo esc_html(get_theme_mod('gp_hero_subheadline', $hero_sub)); ?></p>
                    <div style="display: flex; gap: 25px; align-items: center;">
                        <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn"><?php echo esc_html(get_theme_mod('gp_hero_btn_text', 'Initiate Strategic Setup')); ?></a>
                        <div style="display: flex; align-items: center; gap: 15px; padding-left: 25px; border-left: 1px solid var(--border);">
                            <div style="font-size: 10px; font-weight: 950; opacity: 0.4; letter-spacing: 2px; text-transform: uppercase;">Uplink Status</div>
                            <div style="font-size: 14px; font-weight: 950; color: #10B981;"><?php echo esc_html(get_theme_mod('gp_hero_status_label', 'CORE ACTIVE')); ?></div>
                        </div>
                    </div>
                </div>
                <div class="wp-block-column gp-reveal gp-reveal-stagger-1">
                    <div class="glass-card" style="padding: 60px; border-radius: 60px; box-shadow: 0 80px 150px -30px rgba(0,0,0,0.2);">
                        <h3 style="margin-bottom: 30px; text-align: center;"><?php echo esc_html(get_theme_mod('gp_hero_quiz_headline', 'Get Your Custom Roadmap')); ?></h3>
                        <?php echo do_shortcode(get_theme_mod('gp_hero_quiz_shortcode', '[gp_quiz_lead_form]')); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Decorative Background Node -->
        <div style="position: absolute; bottom: -10%; left: -5%; width: 40%; height: 60%; background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%); opacity: 0.4; filter: blur(80px); pointer-events: none;"></div>
    </section>

    <!-- Trusted By / Stats Bar -->
    <section style="padding: 0; margin-top: -40px; position: relative; z-index: 10;" class="gp-reveal gp-reveal-stagger-2">
        <div class="container">
            <?php echo do_shortcode(get_theme_mod('gp_homepage_stats_shortcode', '[gp_stats_bar]')); ?>
        </div>
    </section>

    <!-- Strategic Process Section -->
    <?php if(get_theme_mod('gp_enable_process', true)): ?>
    <section style="padding: 140px 0; background: var(--bg); border-bottom: 1px solid var(--border);">
        <div class="container">
            <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
                <span class="eyebrow">THE PROTOCOL</span>
                <h2 class="text-gradient headline-lg"><?php echo esc_html(get_theme_mod('gp_process_headline', 'The Journey to Dominance')); ?></h2>
            </div>
            <div class="wp-block-columns" style="gap:60px;">
                <?php for($i=1; $i<=3; $i++): ?>
                <div class="wp-block-column gp-reveal gp-reveal-stagger-<?php echo $i; ?>">
                    <div style="position:relative; padding-left: 80px;">
                        <div style="position:absolute; left:0; top:0; width:60px; height:60px; background:var(--primary-glow); color:var(--primary); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:950;">0<?php echo $i; ?></div>
                        <h3 style="margin-bottom:15px; font-size: 22px;"><?php echo esc_html(get_theme_mod("gp_process_step_title_$i", "Phase 0$i")); ?></h3>
                        <p style="opacity:0.7; font-size: 15px; line-height: 1.6;"><?php echo esc_html(get_theme_mod("gp_process_step_desc_$i", "Description for phase $i.")); ?></p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Strategic Advantage Grid -->
    <section style="padding: 140px 0;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 100px;" class="gp-reveal">
                <span class="eyebrow"><?php echo esc_html(get_theme_mod('gp_delta_eyebrow', 'THE COMPETITIVE DELTA')); ?></span>
                <h2 class="text-gradient headline-lg"><?php echo esc_html(get_theme_mod('gp_delta_headline', 'Engineered for Market Dominance')); ?></h2>
                <p style="font-size: 1.3rem; opacity: 0.7; max-width: 800px; margin: 30px auto 0;"><?php echo esc_html(get_theme_mod('gp_delta_subheadline', 'Our 14-node relational architecture eliminates operational latency and maximizes conversion velocity.')); ?></p>
            </div>

            <div class="wp-block-columns" style="gap: 40px;">
                <?php for($i=1; $i<=3; $i++):
                    $border = ($i==1?'var(--primary)':($i==2?'#10B981':'var(--accent)'));
                    $def_icon = ($i==1?'🧠':($i==2?'📊':'⚡'));
                    $def_title = ($i==1?'Neural Triage':($i==2?'ROI Forecasting':'Velocity Protocol'));
                    ?>
                    <div class="wp-block-column gp-reveal gp-reveal-stagger-<?php echo $i; ?>">
                        <div class="glass-card" style="padding: 60px; height: 100%; border-top: 8px solid <?php echo $border; ?>;">
                            <div style="font-size: 4rem; margin-bottom: 30px;"><?php echo esc_html(get_theme_mod("gp_adv_icon_$i", $def_icon)); ?></div>
                            <h3 style="margin-bottom: 20px;"><?php echo esc_html(get_theme_mod("gp_adv_title_$i", $def_title)); ?></h3>
                            <p style="opacity: 0.7; font-size: 1.1rem; line-height: 1.7;"><?php echo esc_html(get_theme_mod("gp_adv_desc_$i", "Description for advantage item $i")); ?></p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Interactive Ecosystem Section -->
    <?php if(get_theme_mod('gp_enable_homepage_methodology', true)): ?>
    <section class="grainy-bg" style="padding: 140px 0; background: rgba(0,0,0,0.02);">
        <div class="container">
            <div class="wp-block-columns are-vertically-aligned-center" style="gap: 100px;">
                <div class="wp-block-column gp-reveal">
                    <span class="eyebrow"><?php echo esc_html(get_theme_mod('gp_topology_eyebrow', 'SYSTEM TOPOLOGY')); ?></span>
                    <h2 class="text-gradient headline-lg" style="margin-bottom: 35px;"><?php echo esc_html(get_theme_mod('gp_topology_headline', 'A Unified Neural Ecosystem')); ?></h2>
                    <p style="font-size: 1.25rem; opacity: 0.8; margin-bottom: 45px; line-height: 1.6;"><?php echo esc_html(get_theme_mod('gp_topology_subheadline', 'GrowthPress OS isn\'t just a theme—it\'s a high-stakes infrastructure that connects your CRM, Booking, Proposals, and KB articles into one intelligent node.')); ?></p>
                    <ul class="gp-list">
                        <?php for($i=1; $i<=4; $i++): ?>
                            <li><?php echo esc_html(get_theme_mod("gp_topo_list_$i", "Feature Node $i")); ?></li>
                        <?php endfor; ?>
                    </ul>
                    <div style="margin-top: 50px;">
                        <a href="<?php echo home_url('/services'); ?>" class="gp-btn" style="background: var(--secondary);">Explore System Nodes</a>
                    </div>
                </div>
                <div class="wp-block-column gp-reveal gp-reveal-stagger-1">
                    <div class="glass-card" style="padding: 40px; border-radius: 50px; background: rgba(255,255,255,0.4);">
                        <?php echo do_shortcode(get_theme_mod('gp_homepage_radar_shortcode', '[gp_ecosystem_radar]')); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Elite Specialist Preview -->
    <?php if(get_theme_mod('gp_enable_homepage_team', true)): ?>
    <section style="padding: 140px 0; background: rgba(0,0,0,0.01);">
        <div class="container">
            <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
                <span class="eyebrow">HUMAN CAPITAL</span>
                <h2 class="text-gradient headline-lg"><?php echo esc_html(get_theme_mod('gp_homepage_team_headline', 'Human Capital Authority')); ?></h2>
            </div>
            <div class="gp-reveal gp-reveal-stagger-1">
                <?php echo do_shortcode(get_theme_mod('gp_team_shortcode', '[gp_staff_grid]')); ?>
            </div>
            <div style="text-align:center; margin-top:60px;">
                <a href="<?php echo home_url('/team'); ?>" class="gp-btn" style="background:var(--secondary);">View Full Roster</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Social Proof Hub -->
    <?php if(get_theme_mod('gp_enable_homepage_proof', true)): ?>
    <section style="padding: 140px 0; border-top: 1px solid var(--border);">
        <div class="container">
            <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
                <span class="eyebrow">MARKET AUTHORITY</span>
                <h2 class="text-gradient headline-lg"><?php echo esc_html(get_theme_mod('gp_homepage_proof_headline', 'Verified Market Authority')); ?></h2>
            </div>
            <div class="gp-reveal gp-reveal-stagger-1">
                <?php echo do_shortcode('[gp_review_feed]'); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Authority & Results -->
    <section style="padding: 140px 0; position: relative;">
        <!-- Adaptive Glow Node -->
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: radial-gradient(ellipse at bottom, var(--primary-glow) 0%, transparent 70%); opacity: 0.3; pointer-events: none;"></div>
        <div class="container">
            <div style="text-align: center; margin-bottom: 100px;" class="gp-reveal">
                <span class="eyebrow"><?php echo esc_html(get_theme_mod('gp_trajectories_eyebrow', 'VERIFIED TRAJECTORIES')); ?></span>
                <h2 class="text-gradient headline-lg"><?php echo esc_html(get_theme_mod('gp_trajectories_headline', 'Proven ROI Across All Sectors')); ?></h2>
            </div>

            <div class="gp-reveal gp-reveal-stagger-1">
                <?php echo do_shortcode(get_theme_mod('gp_results_shortcode', '[gp_case_study_grid]')); ?>
            </div>

            <div style="text-align: center; margin-top: 80px;" class="gp-reveal">
                <a href="<?php echo home_url('/case-studies'); ?>" style="font-size: 11px; font-weight: 950; letter-spacing: 3px; text-decoration: none; color: var(--text); opacity: 0.4; transition: 0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.4'">VIEW ALL SUCCESS PROFILES &rarr;</a>
            </div>
        </div>
    </section>

    <!-- Niche Specific Intelligence Node -->
    <section class="grainy-bg" style="padding: 140px 0; border-top: 1px solid var(--border);">
        <div class="container">
            <div class="glass-card gp-reveal" style="padding: 100px 80px; text-align: center; border: 2px solid var(--primary);">
                <span class="eyebrow"><?php echo strtoupper($niche); ?> INTELLIGENCE NODE</span>
                <h2 class="text-gradient headline-lg" style="margin-bottom: 50px;"><?php echo esc_html(get_theme_mod('gp_niche_node_headline', 'Calculate Your Growth Potential')); ?></h2>
                <div style="max-width: 900px; margin: 0 auto;">
                    <?php
                    $niche_shortcodes = array(
                        'solar'       => '[gp_solar_calculator]',
                        'contractor'  => '[gp_contractor_estimator]',
                        'medical'     => '[gp_symptom_checker]',
                        'dental'      => '[gp_insurance_optimizer]',
                        'law'         => '[gp_legal_intake]',
                        'accounting'  => '[gp_tax_estimator]',
                        'coaches'     => '[gp_coaching_assistant]',
                        'real-estate' => '[gp_property_matcher]',
                        'roofing'     => '[gp_roofing_estimator]'
                    );
                    echo do_shortcode($niche_shortcodes[$niche] ?? '[gp_consulting_audit]');
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section style="padding: 160px 0; text-align: center;">
        <div class="container gp-reveal">
            <h2 class="text-gradient headline-xl" style="margin-bottom: 40px;"><?php echo esc_html(get_theme_mod('gp_final_cta_headline', 'Ready for Dominance?')); ?></h2>
            <p style="font-size: 1.5rem; opacity: 0.7; max-width: 700px; margin: 0 auto 60px; font-weight: 500;"><?php echo esc_html(get_theme_mod('gp_final_cta_subheadline', 'Initialize your GrowthPress OS node today and join the elite top 1% of ' . $niche . ' firms.')); ?></p>
            <a href="<?php echo home_url(get_theme_mod('gp_header_cta_link', '/book-now')); ?>" class="gp-btn" style="padding: 2rem 6rem; font-size: 16px;">Secure Your Strategic Setup</a>
        </div>
    </section>

</main>

<?php
get_footer();
