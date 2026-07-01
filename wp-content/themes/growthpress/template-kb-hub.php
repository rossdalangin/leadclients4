<?php
/**
 * Template Name: Intelligence Repository (KB Hub)
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
            <span class="eyebrow"><?php echo esc_html(get_theme_mod("gp_faq_eyebrow", "TECHNICAL REPOSITORY & KNOWLEDGE BASE")); ?></span>
            <h1 class="text-gradient headline-xl" style="margin-bottom:35px;"><?php echo esc_html(get_theme_mod('gp_faq_headline', 'Neural Intelligence Base')); ?></h1>
            <p style="font-size:1.4rem; opacity:0.8; max-width:850px; margin:0 auto; font-weight: 500; line-height: 1.5;">
                <?php echo esc_html(get_theme_mod('gp_faq_subheadline', 'Search our high-fidelity, neural-indexed knowledge base for technical specifications, strategic blueprints, and ecosystem documentation. Strategic Note: This repository is used by the AI Assistant to provide context-aware responses to your queries.')); ?>
            </p>
        </div>

        <div class="glass-card" style="background:#f0f7ff; border-left:5px solid #2563eb; margin-bottom:60px; padding:25px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#1e40af;"><strong>Intelligence Pattern:</strong> <?php echo esc_html(get_theme_mod('gp_kb_pattern', "A robust technical repository reduces 'Support Node' latency by 65%. Pro-Tip: High-value leads frequently search for 'ROI' and 'Implementation' keywords before booking.")); ?></p>
        </div>

        <div class="gp-reveal" style="margin-bottom:80px;">
            <?php echo do_shortcode('[gp_kb_search]'); ?>
        </div>

        <div class="gp-reveal">
            <?php echo do_shortcode('[gp_kb_grid]'); ?>
        </div>

        <div style="margin-top:140px;" class="gp-reveal">
            <div class="glass-card" style="background:var(--secondary); color:white; border:none; padding:100px 60px; text-align:center; border-radius:44px; box-shadow: 0 60px 120px -20px var(--primary-glow);">
                <span class="eyebrow" style="color:var(--accent);">ASSISTANCE UPLINK</span>
                <h2 style="color:white; margin-bottom:30px;" class="headline-lg">Still Calibrating Your Strategy?</h2>
                <p style="opacity:0.7; margin-bottom:50px; font-size:1.3rem; max-width:800px; margin-left:auto; margin-right:auto;">Our proprietary AI assistant is available 24/7 to provide contextual guidance based on our entire 14-node technical repository and success patterns.</p>
                <button onclick="jQuery('#gp-chat-launcher').click()" class="gp-btn" style="height:80px; padding:0 60px; font-size:18px; background:var(--primary);">Initiate Neural Uplink</button>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
