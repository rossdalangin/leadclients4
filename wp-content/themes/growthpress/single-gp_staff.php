<?php
/**
 * Single Staff Dossier Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div class="glass-card" style="background:#f0fdf4; border-left:5px solid #10b981; margin-bottom:60px; padding:25px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#166534;"><strong>Specialist Intel:</strong> <?php echo esc_html(get_theme_mod('gp_single_staff_intel', "This dossier profiles a high-performance operational node. Pro-Tip: Schedule a 'Strategic Briefing' directly from the dossier to reduce project kickoff latency.")); ?></p>
        </div>
        <?php while ( have_posts() ) : the_post();
            $expertise = get_post_meta(get_the_ID(), '_staff_expertise', true);
            $seniority = get_post_meta(get_the_ID(), '_staff_seniority', true);
            ?>
            <div class="wp-block-columns are-vertically-aligned-center gp-reveal" style="gap:80px; margin-bottom:100px;">
                <div class="wp-block-column" style="flex-basis:40%;">
                    <div class="glass-card" style="padding:10px; border-radius:50px; box-shadow: 0 50px 100px -20px var(--primary-glow);">
                        <?php if(has_post_thumbnail()): ?>
                            <?php the_post_thumbnail('full', array('style'=>'width:100%; height:auto; border-radius:40px; display:block;')); ?>
                        <?php else: ?>
                            <div style="height:600px; background:var(--secondary); border-radius:40px; display:flex; align-items:center; justify-content:center; font-size:10rem;">👤</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="wp-block-column">
                    <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;">SPECIALIST DOSSIER</div>
                    <h1 class="text-gradient" style="margin-bottom:20px;"><?php the_title(); ?></h1>
                    <div style="display:flex; gap:20px; align-items:center; margin-bottom:40px;">
                        <div style="background:var(--primary-glow); color:var(--primary); padding:8px 20px; border-radius:10px; font-size:12px; font-weight:950; letter-spacing:1px;"><?php echo strtoupper($seniority); ?></div>
                        <div style="font-weight:700; opacity:0.6; font-size:14px; letter-spacing:1px;"><?php echo strtoupper($expertise); ?></div>
                    </div>
                    <h4 style="font-weight: 900; margin-bottom: 15px; opacity: 0.5; font-size: 11px; letter-spacing: 2px; text-transform: uppercase;"><?php echo esc_html(get_theme_mod('gp_staff_bio_label', 'Strategic Biography')); ?></h4>
                    <div class="entry-content" style="font-size:1.2rem; line-height:1.8; opacity:0.8; margin-bottom:50px;">
                        <?php the_content(); ?>
                    </div>
                    <div style="background:var(--secondary); color:white; padding:60px; border-radius:40px; margin-top:50px; position:relative; overflow:hidden;">
                        <div style="position:absolute; top:0; right:0; width:200px; height:200px; background:var(--primary); filter:blur(100px); opacity:0.3; transform:translate(50px, -50px);"></div>
                        <div style="font-size:10px; font-weight:950; letter-spacing:2px; opacity:0.5; margin-bottom:10px;"><?php echo esc_html(get_theme_mod('gp_staff_contact_label', 'Node Authentication')); ?></div>
                        <h3 style="color:white; margin:0 0 10px 0;">Schedule Strategic Briefing</h3>
                        <p style="opacity:0.7; margin-bottom:40px;">Direct uplink to <?php echo explode(' ', get_the_title())[0]; ?> for a 1-on-1 operational audit.</p>
                        <?php echo do_shortcode(get_theme_mod('gp_booking_shortcode', '[gp_booking_form]')); ?>
                    </div>
                </div>
            </div>

            <!-- Performance Stats & Analytics -->
            <div class="gp-reveal" style="margin-top:120px;">
                <div style="display:grid; grid-template-columns: 1fr 2fr; gap:40px;">
                    <div class="glass-card" style="padding:50px;">
                        <h3 style="margin-top:0;">Performance Radar</h3>
                        <canvas id="staffRadar" height="300"></canvas>
                    </div>
                    <div class="glass-card" style="padding:50px; display:grid; grid-template-columns: repeat(2, 1fr); gap:40px; text-align:center;">
                        <div>
                            <div style="font-size:3.5rem; font-weight:950; color:var(--primary); line-height:1;">98%</div>
                            <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-top:15px;">SUCCESS RATE</div>
                        </div>
                        <div>
                            <div style="font-size:3.5rem; font-weight:950; color:var(--primary); line-height:1;">4.2m</div>
                            <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-top:15px;">AVG RESPONSE</div>
                        </div>
                        <div>
                            <div style="font-size:3.5rem; font-weight:950; color:var(--primary); line-height:1;">150+</div>
                            <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-top:15px;">NODES DEPLOYED</div>
                        </div>
                        <div>
                            <div style="font-size:3.5rem; font-weight:950; color:var(--primary); line-height:1;">Elite</div>
                            <div style="font-size:10px; font-weight:950; opacity:0.4; letter-spacing:2px; margin-top:15px;">CERTIFICATION</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $perf_json = get_post_meta(get_the_ID(), '_staff_performance_json', true);
            $perf = json_decode($perf_json, true) ?: ['efficiency'=>90, 'conversion'=>85, 'technical'=>95, 'speed'=>92, 'strategy'=>88];
            ?>
            <script>
            jQuery(document).ready(function($) {
                const ctx = document.getElementById('staffRadar').getContext('2d');
                new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['Efficiency', 'Conversion', 'Technical', 'Speed', 'Strategy'],
                        datasets: [{
                            data: [
                                <?php echo (int)$perf['efficiency']; ?>,
                                <?php echo (int)$perf['conversion']; ?>,
                                <?php echo (int)$perf['technical']; ?>,
                                <?php echo (int)$perf['speed']; ?>,
                                <?php echo (int)$perf['strategy']; ?>
                            ],
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: '#4F46E5',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: 'rgba(0,0,0,0.05)' },
                                pointLabels: { font: { size: 10, weight: 'bold' } }
                            }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            });
            </script>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
