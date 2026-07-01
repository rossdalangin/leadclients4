<?php
/**
 * Template Name: Mission & Strategy (About)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:120px; padding-bottom:120px;">
    <div class="container">
        <div class="wp-block-columns are-vertically-aligned-center gp-reveal" style="gap:100px; margin-bottom:140px;">
            <div class="wp-block-column" style="flex-basis:55%;">
                <span class="eyebrow">OPERATIONAL VISION</span>
                <h1 class="text-gradient headline-xl" style="margin-bottom:45px;">
                    <?php echo esc_html( get_theme_mod('gp_about_headline', 'Engineering Market Dominance') ); ?>
                </h1>
                <div style="font-size:1.5rem; line-height:1.6; opacity:0.8; margin-bottom:55px; font-weight: 500;">
                    <?php echo nl2br( esc_html( get_theme_mod('gp_about_text', 'We are dedicated to building the worlds most advanced business growth operating systems, empowering high-ticket firms with autonomous intelligence.') ) ); ?>
                </div>
                <div style="display:flex; gap:20px;">
                    <a href="/contact" class="gp-btn">Uplink With Our Team</a>
                    <div style="display:flex; align-items:center; gap:15px; padding-left:20px; border-left:1px solid var(--border);">
                        <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; text-transform:uppercase;">System Status</div>
                        <div style="font-size:14px; font-weight:950; color:#10B981;">CORE ACTIVE</div>
                    </div>
                </div>
            </div>
            <div class="wp-block-column gp-reveal gp-reveal-stagger-1">
                <div class="glass-card" style="padding:15px; border-radius:45px; box-shadow: 0 60px 120px -30px var(--primary-glow);">
                    <div style="height:550px; background:var(--secondary); border-radius:35px; display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative; overflow:hidden;">
                        <div style="position:absolute; top:-10%; left:-10%; width:120%; height:120%; background:radial-gradient(circle at center, var(--primary) 0%, transparent 70%); opacity:0.1;"></div>
                        <div style="font-size:10rem; position:relative; z-index:2; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));">🧠</div>
                        <div style="margin-top:30px; font-size:11px; font-weight:950; color:white; opacity:0.4; letter-spacing:4px; z-index:2;">NEURAL NODE v6.3</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="gp-reveal" style="margin-top:140px;">
            <div class="glass-card" style="background:#f0fdf4; border-left:5px solid #10b981; margin-bottom:100px; text-align:center; padding:30px;">
                <p style="margin:0; font-size:15px; color:#166534;"><strong>Strategic Vision Node:</strong> <?php echo esc_html(get_theme_mod('gp_about_vision_node', "Our mission is to move firms from 'Manual Latency' to 'Autonomous Realization'. v6.3 Elite architecture is the standard for high-performance service delivery.")); ?></p>
            </div>
            <div style="text-align:center; margin-bottom:100px;">
                <span class="eyebrow">CORE ARCHITECTURE</span>
                <h2 class="text-gradient headline-lg">The Ecosystem Framework</h2>
            </div>
            <div class="wp-block-columns" style="gap:50px;">
                <?php for($i=1; $i<=3; $i++):
                    $border = ($i==1?'var(--primary)':($i==2?'#10B981':'var(--accent)'));
                    ?>
                    <div class="wp-block-column glass-card" style="padding:60px; text-align:center; border-top: 6px solid <?php echo $border; ?>;">
                        <div style="font-size:4rem; margin-bottom:30px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));"><?php echo esc_html(get_theme_mod("gp_about_benefit_icon_$i")); ?></div>
                        <h3 style="margin-bottom:20px;"><?php echo esc_html(get_theme_mod("gp_about_benefit_title_$i")); ?></h3>
                        <p style="opacity:0.7; font-size: 1.1rem; line-height: 1.7;"><?php echo esc_html(get_theme_mod("gp_about_benefit_desc_$i")); ?></p>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <div class="gp-reveal" style="margin-top:120px;">
            <?php echo do_shortcode(get_theme_mod('gp_about_shortcode', '[gp_stats_bar]')); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
