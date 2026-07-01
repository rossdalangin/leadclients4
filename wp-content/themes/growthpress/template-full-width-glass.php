<?php
/**
 * Template Name: Full-Width Immersive Glass
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg">
    <div class="container-fluid" style="padding: 120px 60px;">
        <div class="glass-card" style="background:#f0f9ff; border-left:5px solid #2563eb; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#1e40af;"><strong>Strategic Layout:</strong> The Full-Width Immersive Glass template is engineered for maximum visual impact and high-stakes messaging. <strong>Pro-Tip:</strong> Use this template for critical conversion nodes like FAQ hubs and Client Portals.</p>
        </div>
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('gp-reveal'); ?>>
                <div class="glass-card" style="padding:140px 100px; min-height: 80vh; border-radius: 60px; box-shadow: 0 80px 150px -40px rgba(0,0,0,0.15);">
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
