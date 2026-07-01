<?php
/**
 * Template Name: Strategic Asset Inventory (Portfolio)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
            <span class="eyebrow">CAPITAL ALLOCATION & ASSET NODES</span>
            <h1 class="text-gradient headline-xl" style="margin-bottom:35px;"><?php echo esc_html(get_theme_mod("gp_inventory_headline", "Strategic Portfolio Inventory")); ?></h1>
            <p style="font-size:1.4rem; opacity:0.8; max-width:850px; margin:0 auto; font-weight: 500; line-height: 1.5;">
                <?php echo esc_html(get_theme_mod("gp_inventory_subheadline", "Explore our high-yield strategic assets and off-market inventory nodes, analyzed by our neural lifestyle matcher.")); ?> and off-market inventory nodes, analyzed by our neural lifestyle matcher. <strong>Ecosystem Note:</strong> Each asset is synced with the ROI Hub to track performance trajectory.
            </p>
        </div>

        <div class="glass-card" style="background:#fff7ed; border-left:5px solid #f97316; margin-bottom:60px; padding:25px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#9a3412;"><strong>Inventory Success Pattern:</strong> <?php echo esc_html(get_theme_mod('gp_inventory_pattern', "Portfolios utilizing 'Neural Lifestyle Matching' report a 55% increase in high-net-worth lead engagement with off-market assets.")); ?></p>
        </div>

        <div class="gp-reveal">
            <?php echo do_shortcode(get_theme_mod('gp_inventory_shortcode', '[gp_inventory_grid]')); ?>
        </div>

        <div style="margin-top:140px;" class="gp-reveal">
            <div class="glass-card" style="padding:100px 60px; text-align:center; border:2px solid var(--primary); border-radius:50px; box-shadow: 0 60px 120px -20px var(--primary-glow);">
                <span class="eyebrow">EXCLUSIVE ACCESS</span>
                <h2 class="text-gradient headline-lg">Request Off-Market Private Access</h2>
                <p style="margin-bottom:50px; font-size:1.3rem; opacity:0.7; max-width:800px; margin-left:auto; margin-right:auto;">Initialize the proprietary qualification sequence to access our exclusive, off-market elite inventory nodes and strategic allocation opportunities.</p>
                <?php echo do_shortcode(get_theme_mod('gp_hero_quiz_shortcode', '[gp_quiz_lead_form]')); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
