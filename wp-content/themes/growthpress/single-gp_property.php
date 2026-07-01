<?php
/**
 * Single Property Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div class="glass-card" style="background:#fff7ed; border-left:5px solid #f97316; margin-bottom:60px; padding:25px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#9a3412;"><strong>Strategic Asset:</strong> <?php echo esc_html(get_theme_mod('gp_single_property_intel', "This asset is synced with the 'Neural Lifestyle Matcher'. Success Pattern: Elite nodes with verified lifestyle tags report a 55% higher conversion probability.")); ?></p>
        </div>
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="wp-block-columns" style="gap:80px;">
                <div class="wp-block-column" style="flex-basis:60%;">
                    <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">STRATEGIC ASSET</div>
                    <h1 class="text-gradient" style="margin-bottom:40px;"><?php the_title(); ?></h1>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="glass-card" style="padding:10px; border-radius:40px; margin-bottom:60px;">
                            <?php the_post_thumbnail('full', array('style' => 'border-radius:30px; display:block; width:100%; height:auto;')); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content" style="font-size:1.2rem; line-height:1.9; opacity:0.8;">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="wp-block-column">
                    <div class="glass-card" style="padding:50px; border-top: 10px solid var(--primary);">
                        <h3 style="margin-top:0;">Asset Specifications</h3>
                        <div style="margin:30px 0;">
                            <div style="margin-bottom:30px; padding-bottom:20px; border-bottom:1px solid #F1F5F9;">
                                <div style="font-size:11px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:10px;">MARKET VALUATION</div>
                                <div style="font-size:3rem; font-weight:950; color:var(--primary); letter-spacing:-0.05em;">$<?php echo number_format(get_post_meta(get_the_ID(), '_gp_price', true) ?: 2500000); ?></div>
                            </div>
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                                <div>
                                    <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:8px;">TOTAL SQFT</div>
                                    <div style="font-size:18px; font-weight:900;"><?php echo number_format(get_post_meta(get_the_ID(), '_gp_sqft', true) ?: 4500); ?></div>
                                </div>
                                <div>
                                    <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:1px; margin-bottom:8px;">LIFESTYLE</div>
                                    <div style="font-size:14px; font-weight:900; color:var(--secondary);"><?php echo get_post_meta(get_the_ID(), '_gp_lifestyle_tags', true) ?: 'Elite'; ?></div>
                                </div>
                            </div>
                        </div>
                        <hr style="opacity:0.05; margin:40px 0;">
                        <h4 style="margin-bottom:25px;">Secure Private Briefing</h4>
                        <?php echo do_shortcode('[gp_booking_form]'); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
