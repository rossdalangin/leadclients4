<?php
/**
 * Single Service Line Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:140px; padding-bottom:140px;">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="wp-block-columns are-vertically-aligned-center gp-reveal" style="gap:80px;">
                    <div class="wp-block-column" style="flex-basis: 55%;">
                        <span class="eyebrow">ELITE SERVICE LINE</span>
                        <h1 class="text-gradient headline-xl" style="margin-bottom:35px;"><?php the_title(); ?></h1>
                        <div class="entry-content" style="font-size:1.4rem; line-height:1.6; opacity:0.8; margin-bottom:50px; font-weight: 500;">
                            <?php the_content(); ?>
                        </div>
                        <div style="display:flex; gap:25px; align-items: center;">
                            <a href="#booking" class="gp-btn">Secure My Appointment</a>
                            <a href="/pricing" style="font-size:11px; font-weight:950; letter-spacing:2px; text-decoration:none; color:var(--text); opacity:0.5; transition:0.3s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.5'">EXPLORE PLANS &rarr;</a>
                        </div>

                        <div style="margin-top:60px; background:var(--primary-glow); padding:40px; border-radius:30px; border:1px solid rgba(79, 70, 229, 0.1); display:flex; align-items:center; gap:35px; box-shadow: 0 20px 40px -10px var(--primary-glow);">
                            <div style="font-size:4rem; font-weight:950; color:var(--primary); line-height:1; letter-spacing:-0.05em;"><?php echo esc_html(get_theme_mod('gp_single_service_roi_label', '+315%')); ?></div>
                            <div style="font-size:11px; font-weight:950; opacity:0.5; letter-spacing:2px; line-height:1.6; text-transform:uppercase;">AVERAGE HISTORICAL ROI<br>FOR THIS SERVICE NODE</div>
                        </div>
                    </div>
                    <div class="wp-block-column gp-reveal gp-reveal-stagger-1">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="glass-card" style="padding:15px; border-radius:45px; box-shadow: 0 60px 120px -30px rgba(0,0,0,0.2);">
                                <?php the_post_thumbnail('large', array('style' => 'border-radius:35px; display:block; width:100%; height:auto;')); ?>
                            </div>
                        <?php else:
                            $icon = get_post_meta(get_the_ID(), '_gp_service_icon', true) ?: '💎'; ?>
                            <div class="glass-card" style="padding:80px; text-align:center; background:var(--primary-glow); border-radius:45px; position:relative; overflow:hidden;">
                                <div style="position:absolute; top:-10%; right:-10%; font-size:20rem; opacity:0.03; transform:rotate(15deg);"><?php echo $icon; ?></div>
                                <div style="font-size:10rem; margin-bottom:30px; position:relative; z-index:2; filter:drop-shadow(0 20px 40px rgba(0,0,0,0.1));"><?php echo $icon; ?></div>
                                <h3 class="text-gradient headline-md" style="position:relative; z-index:2;">Industry Excellence</h3>
                                <p style="opacity:0.6; font-weight:600; position:relative; z-index:2;">Our <?php the_title(); ?> methodology is built for market dominance and high-ticket realization.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Strategic Benefits Section -->
                <div style="margin-top:160px;" class="gp-reveal">
                    <div style="text-align:center; margin-bottom:100px;">
                        <span class="eyebrow">THE COMPETITIVE EDGE</span>
                        <h2 class="text-gradient headline-lg">The GrowthPress Advantage</h2>
                    </div>
                    <div class="wp-block-columns" style="gap:40px;">
                        <div class="wp-block-column glass-card" style="text-align:center; padding:60px; border-top: 6px solid var(--primary);">
                            <div style="font-size:4rem; margin-bottom:30px; filter:drop-shadow(0 10px 20px rgba(0,0,0,0.1));">🤖</div>
                            <h3 style="margin-bottom:20px;">AI Integration</h3>
                            <p style="opacity:0.7; font-size:1.1rem; line-height:1.7;">Every service is backed by our proprietary triage and qualification engine, ensuring 100% lead synchronization.</p>
                        </div>
                        <div class="wp-block-column glass-card" style="text-align:center; padding:60px; border-top: 6px solid #10B981;">
                            <div style="font-size:4rem; margin-bottom:30px; filter:drop-shadow(0 10px 20px rgba(0,0,0,0.1));">📊</div>
                            <h3 style="margin-bottom:20px;">ROI Focused</h3>
                            <p style="opacity:0.7; font-size:1.1rem; line-height:1.7;">We prioritize high-stakes activities that generate the highest measurable return on investment for your enterprise.</p>
                        </div>
                        <div class="wp-block-column glass-card" style="text-align:center; padding:60px; border-top: 6px solid var(--accent);">
                            <div style="font-size:4rem; margin-bottom:30px; filter:drop-shadow(0 10px 20px rgba(0,0,0,0.1));">⚡</div>
                            <h3 style="margin-bottom:20px;">Rapid Execution</h3>
                            <p style="opacity:0.7; font-size:1.1rem; line-height:1.7;">Proprietary workflows designed to aggressively reduce operational latency and keep you ahead of local competitors.</p>
                        </div>
                    </div>
                </div>

                <!-- Booking Section -->
                <div id="booking" style="margin-top:120px; max-width:800px; margin-left:auto; margin-right:auto;">
                    <div class="glass-card" style="padding:60px; text-align:center; border:2px solid var(--primary);">
                        <h2 class="text-gradient">Ready to Get Started?</h2>
                        <p style="margin-bottom:40px;">Schedule your high-stakes consultation for <?php the_title(); ?> below.</p>
                        <?php echo do_shortcode(get_theme_mod('gp_booking_shortcode', '[gp_booking_form]')); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
