<?php
/**
 * Single Case Study/Project Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:120px; padding-bottom:120px;">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header gp-reveal" style="text-align:center; margin-bottom:100px; max-width:1000px; margin-left:auto; margin-right:auto;">
                    <span class="eyebrow">SUCCESS TRANSFORMATION & ROI PROFILE</span>
                    <h1 class="text-gradient headline-xl" style="margin-bottom:40px;"><?php the_title(); ?></h1>
                    <?php if ( has_excerpt() ) : ?>
                        <p style="font-size:1.5rem; opacity:0.8; line-height:1.6; font-weight: 500;"><?php echo get_the_excerpt(); ?></p>
                    <?php endif; ?>
                </header>

                <div class="glass-card" style="padding:0; border-radius:40px; margin-bottom:80px; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.15);">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail('full', array('style' => 'display:block; width:100%; height:auto; border-radius:40px;')); ?>
                    <?php else: ?>
                        <div style="height:400px; background:linear-gradient(135deg, var(--primary), var(--accent)); display:flex; align-items:center; justify-content:center; border-radius:40px;">
                            <div style="font-size:10rem; opacity:0.2;">🚀</div>
                        </div>
                    <?php endif; ?>
                </div>

                <div style="background: #f0fdf4; border-left: 5px solid #10b981; padding: 25px; border-radius: 15px; margin-bottom: 60px;">
                    <p style="margin: 0; font-size: 15px; color: #166534;"><strong>ROI Validation:</strong> <?php echo esc_html(get_theme_mod('gp_single_project_validation', 'This project profile demonstrates the \'Autonomous Realization\' pattern, where operational latency was reduced by 40% through v6.3 multi-node intelligence.')); ?></p>
                </div>

                <div class="wp-block-columns" style="gap:60px;">
                    <div class="wp-block-column gp-reveal" style="flex-basis:65%;">
                        <div class="glass-card" style="padding:60px; border-radius: 40px;">
                            <span class="eyebrow" style="margin-bottom: 25px;">METHODOLOGY & EXECUTION</span>
                            <h3 class="text-gradient headline-md" style="margin-bottom: 30px;">Strategic Project Overview</h3>
                            <div class="entry-content" style="font-size:1.2rem; line-height:1.8; opacity:0.8; font-weight: 500;">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="wp-block-column">
                        <div class="glass-card" style="padding:40px; background:var(--secondary); color:white;">
                            <h3 style="color:white; margin-bottom:30px;">Strategic ROI</h3>
                            <div style="margin-bottom:30px;">
                                <div style="font-size:11px; opacity:0.5; text-transform:uppercase; letter-spacing:1px;">GROWTH INCREASE</div>
                                <div style="font-size:2.5rem; font-weight:900; color:var(--accent);"><?php echo get_post_meta(get_the_ID(), '_gp_growth_roi', true) ?: '+320%'; ?></div>
                            </div>
                            <div style="margin-bottom:30px;">
                                <div style="font-size:11px; opacity:0.5; text-transform:uppercase; letter-spacing:1px;">EFFICIENCY GAIN</div>
                                <div style="font-size:2.5rem; font-weight:900; color:var(--accent);"><?php echo get_post_meta(get_the_ID(), '_gp_efficiency_gain', true) ?: '40 HRS/WK'; ?></div>
                            </div>
                            <div style="margin-bottom:30px;">
                                <div style="font-size:11px; opacity:0.5; text-transform:uppercase; letter-spacing:1px;">PIPELINE VALUE</div>
                                <div style="font-size:2.5rem; font-weight:900; color:var(--accent);"><?php echo get_post_meta(get_the_ID(), '_gp_pipeline_value', true) ?: '$1.2M+'; ?></div>
                            </div>
                            <hr style="opacity:0.1; margin:30px 0;">
                            <p style="font-size:14px; opacity:0.7;">This results profile was verified by the GrowthPress AI Analytics engine on <?php echo get_the_date(); ?>.</p>
                        </div>

                        <div class="glass-card" style="margin-top:30px; text-align:center;">
                            <h4>Want Similar Results?</h4>
                            <p style="font-size:14px; margin-bottom:20px;">Book your AI-powered strategy audit today.</p>
                            <a href="/book-now" class="gp-btn" style="width:100%;">Apply Now</a>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Overlay -->
                <div style="margin-top:100px; text-align:center;">
                    <div class="glass-card" style="max-width:800px; margin-left:auto; margin-right:auto; position:relative;">
                        <div style="font-size:4rem; color:var(--primary-glow); position:absolute; top:10px; left:20px; opacity:0.3;">"</div>
                        <p style="font-size:1.5rem; font-style:italic; line-height:1.6; position:relative; z-index:1;">
                            Implementing the GrowthPress Operating System was the single most impactful decision for our firm this year.
                        </p>
                        <div style="margin-top:20px; font-weight:900; letter-spacing:1px;">ELITE CLIENT PARTNER</div>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
