<?php
/**
 * Template Name: Results & ROI Gallery
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:120px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
            <span class="eyebrow">VERIFIED TRAJECTORIES</span>
            <h1 class="text-gradient headline-xl" style="margin-bottom:35px;">
                <?php echo esc_html( get_theme_mod('gp_results_headline', 'Verified Results & ROI Profiles') ); ?>
            </h1>
            <p style="font-size:1.4rem; opacity:0.8; max-width:850px; margin:0 auto; font-weight: 500; line-height: 1.5;">
                <?php echo esc_html( get_theme_mod('gp_results_subheadline', 'Visual confirmation of our precision engineering and client success trajectories.') ); ?>
            </p>
        </div>

        <div class="gp-reveal gp-reveal-stagger-1">
            <?php echo do_shortcode(get_theme_mod('gp_results_shortcode', '[gp_case_study_grid]')); ?>
        </div>

        <div style="margin-top:140px; display:grid; grid-template-columns: 1fr 1fr; gap:60px;" class="gp-reveal">
            <div class="glass-card" style="padding:70px; border-radius: 50px;">
                <span class="eyebrow">MARKET AUTHORITY</span>
                <h3 class="text-gradient headline-md" style="margin-bottom:25px;">Strategic Testimonials</h3>
                <p style="opacity:0.7; margin-bottom:40px; font-size: 1.1rem;">Verified feedback from our enterprise partners regarding autonomous triage and execution velocity.</p>
                <div style="background:rgba(0,0,0,0.02); padding:40px; border-radius:30px; border:1px solid var(--border);">
                    <?php echo do_shortcode('[gp_review_feed]'); ?>
                </div>
            </div>
            <div class="glass-card" style="padding:70px; background:var(--secondary); color:white; border:none; border-radius: 50px; box-shadow: 0 50px 100px -20px var(--primary-glow);">
                <span class="eyebrow" style="color:var(--accent);">SECURE YOUR SPOT</span>
                <h3 style="color:white; margin-bottom:25px;" class="headline-md">Join the Elite</h3>
                <p style="opacity:0.7; margin-bottom:45px; font-size: 1.1rem;">Ready to generate your own high-ticket ROI profile? Initialize the neural qualification sequence today.</p>
                <?php echo do_shortcode('[gp_lead_form]'); ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
