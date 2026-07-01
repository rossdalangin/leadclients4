<?php
/**
 * Single Treatment Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div class="glass-card" style="background:#f0fdf4; border-left:5px solid #10b981; margin-bottom:40px; padding:25px; border-radius:15px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#065f46;"><strong>Protocol Intel:</strong> <?php echo esc_html(get_theme_mod('gp_single_treatment_intel', "This protocol is registered in the v6.3 Clinical Intelligence Hub. Success Pattern: Documenting expected outcomes reduces patient anxiety and increases realization probability.")); ?></p>
        </div>
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="wp-block-columns" style="gap:80px;">
                <div class="wp-block-column" style="flex-basis:60%;">
                    <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">CLINICAL PROTOCOL</div>
                    <h1 class="text-gradient" style="margin-bottom:40px;"><?php the_title(); ?></h1>
                    <div class="entry-content" style="font-size:1.2rem; line-height:1.9; opacity:0.8;">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="wp-block-column">
                    <div class="glass-card" style="padding:50px; border-left: 10px solid var(--primary);">
                        <h3 style="margin-top:0;">Protocol Intel</h3>
                        <div style="margin:30px 0;">
                            <div style="margin-bottom:25px;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:8px;">AVG DURATION</div>
                                <div style="font-size:22px; font-weight:900; color:var(--secondary);"><?php echo get_post_meta(get_the_ID(), '_treatment_duration', true) ?: '60 Mins'; ?></div>
                            </div>
                            <div style="margin-bottom:25px;">
                                <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:8px;">COMPLEXITY</div>
                                <div style="font-size:22px; font-weight:900; color:var(--primary);"><?php echo strtoupper(get_post_meta(get_the_ID(), '_treatment_complexity', true) ?: 'STANDARD'); ?></div>
                            </div>
                        </div>
                        <hr style="opacity:0.05; margin:40px 0;">
                        <h4 style="margin-bottom:25px;">Ready for Triage?</h4>
                        <?php echo do_shortcode('[gp_lead_form]'); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
