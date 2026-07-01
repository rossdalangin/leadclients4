<?php
/**
 * Template Name: Team & Specialists Hub
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
            <span class="eyebrow">HUMAN CAPITAL INFRASTRUCTURE</span>
            <h1 class="text-gradient headline-xl" style="margin-bottom:35px;">
                <?php echo esc_html( get_theme_mod('gp_team_headline', 'Specialized Operational Team') ); ?>
            </h1>
            <p style="font-size:1.4rem; opacity:0.8; max-width:850px; margin:0 auto; font-weight: 500; line-height: 1.5;">
                <?php echo esc_html( get_theme_mod('gp_team_subheadline', 'Elite human capital nodes trained in high-stakes operational execution. Each specialist node is monitored for conversion efficiency and customer satisfaction trajectory.') ); ?>
            </p>
        </div>

        <div class="glass-card" style="background:#f0fdf4; border-left:5px solid #10b981; margin-bottom:60px; padding:25px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#166534;"><strong>Human Capital Pattern:</strong> <?php echo esc_html(get_theme_mod('gp_team_pattern', 'Firms with at least 3 authenticated Specialist Nodes report a 28% higher client retention rate due to specialized operational depth.')); ?></p>
        </div>

        <div class="gp-reveal">
            <?php echo do_shortcode(get_theme_mod('gp_team_shortcode', '[gp_staff_grid]')); ?>
        </div>

        <div style="margin-top:140px;" class="gp-reveal">
            <div class="glass-card" style="background:var(--primary); color:white; border:none; padding:100px 60px; text-align:center; border-radius:50px; box-shadow: 0 60px 120px -20px var(--primary-glow);">
                <span class="eyebrow" style="color:rgba(255,255,255,0.6);">NETWORK EXPANSION</span>
                <h2 style="color:white; margin-bottom:30px;" class="headline-lg">Join the GrowthPress Network</h2>
                <p style="opacity:0.8; margin-bottom:50px; font-size:1.3rem; max-width:800px; margin-left:auto; margin-right:auto;">We are always looking for elite specialists and high-performance individuals to join our global human capital infrastructure.</p>
                <a href="/contact" class="gp-btn" style="background:white; color:var(--primary) !important; height:80px; padding:0 60px; display:inline-flex; align-items:center;">Submit Strategic Application</a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
