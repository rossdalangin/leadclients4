<?php
/**
 * Template Name: Strategic Services Hub
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:120px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
            <span class="eyebrow">OPERATIONAL INFRASTRUCTURE</span>
            <h1 class="text-gradient headline-xl" style="margin-bottom:35px;">
                <?php echo esc_html( get_theme_mod('gp_services_headline', 'Elite Service Infrastructure') ); ?>
            </h1>
            <p style="font-size:1.4rem; opacity:0.8; max-width:850px; margin:0 auto; font-weight: 500; line-height: 1.5;">
                <?php echo esc_html( get_theme_mod('gp_services_subheadline', 'Proprietary methodologies engineered for market dominance and high-ticket returns.') ); ?>
            </p>
        </div>

        <div class="gp-reveal gp-reveal-stagger-1">
            <?php echo do_shortcode(get_theme_mod('gp_services_shortcode', '[gp_service_grid]')); ?>
        </div>

        <div style="margin-top:160px; display:grid; grid-template-columns: 1fr 1.2fr; gap:80px; align-items: center;" class="gp-reveal">
            <div>
                <span class="eyebrow">METHODOLOGY</span>
                <h2 class="text-gradient headline-lg" style="margin-bottom:30px;">Engineered for High-Stakes Realization</h2>
                <p style="font-size: 1.2rem; opacity:0.7; margin-bottom:40px;">Our infrastructure is built on a 14-node relational architecture that ensures no lead is ignored and every project is tracked with surgical precision.</p>
                <ul class="gp-list">
                    <li>Autonomous Triage Protocols</li>
                    <li>Neural Sentiment Analysis</li>
                    <li>Regional Hub Synchronization</li>
                    <li>Secure Asset Management</li>
                </ul>
                <div style="margin-top:40px; background:#f0f9ff; border-left:4px solid #0ea5e9; padding:20px; border-radius:10px;">
                    <p style="margin:0; font-size:13px; color:#0369a1;"><strong>Success Pattern:</strong> <?php echo esc_html(get_theme_mod('gp_services_pattern', 'Service firms that integrate the Full Ecosystem Radar into their discovery process report a 44% increase in perceived authority during initial briefings.')); ?></p>
                </div>
            </div>
            <div class="glass-card" style="padding: 60px;">
                <?php echo do_shortcode('[gp_ecosystem_radar]'); ?>
            </div>
        </div>

        <div style="margin-top:120px;" class="gp-reveal">
            <div class="glass-card" style="padding:100px 60px; text-align:center; border:2px solid var(--primary);">
                <h2 class="text-gradient">Secure Your Strategic Slot</h2>
                <p style="margin-bottom:50px; opacity:0.7;">Ready to scale your <?php echo get_option('growthpress_niche', 'business'); ?> operation? Secure a strategy session to determine which service node aligns with your growth targets. <strong>Success Pattern:</strong> 85% of our top-tier clients start with a Performance Blueprint session.</p>
                <?php echo do_shortcode(get_theme_mod('gp_booking_shortcode', '[gp_booking_form]')); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
