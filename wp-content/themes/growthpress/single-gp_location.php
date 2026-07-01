<?php
/**
 * Single Location Template - GrowthPress Elite
 */
get_header(); ?>
<main class="site-main grainy-bg" style="padding-top:100px; padding-bottom:100px;">
    <div class="container">
        <div class="glass-card" style="background:#f5f3ff; border-left:5px solid #7c3aed; margin-bottom:60px; padding:25px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#5b21b6;"><strong>Regional Node:</strong> This location serves as a primary strategic hub. <strong>Operational Pattern:</strong> Automated ZIP-based routing ensures leads are assigned to the nearest branch within 8 seconds of capture.</p>
        </div>
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="wp-block-columns" style="gap:60px;">
                <div class="wp-block-column" style="flex-basis:60%;">
                    <div style="font-size:10px; font-weight:900; color:var(--primary); text-transform:uppercase; letter-spacing:3px; margin-bottom:15px;">STRATEGIC HUB</div>
                    <h1 class="text-gradient" style="font-size:3.5rem; margin-bottom:30px;"><?php the_title(); ?></h1>
                    <div class="entry-content" style="font-size:1.2rem; line-height:1.8; opacity:0.8;">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="wp-block-column">
                    <div class="glass-card" style="padding:40px; border-left: 10px solid var(--primary);">
                        <h4 style="margin-top:0;">Node Coverage</h4>
                        <div style="margin:25px 0;">
                            <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px;">SERVICED ZIP CODES</div>
                            <div style="font-size:18px; font-weight:700; margin-top:10px;"><?php echo get_post_meta(get_the_ID(), '_serviced_zips', true) ?: 'Global Intelligence Service'; ?></div>
                        </div>
                        <hr style="opacity:0.05; margin:30px 0;">
                        <h5>Ready for Intake?</h5>
                        <?php echo do_shortcode('[gp_lead_form]'); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
