<?php
/**
 * Template Name: Legal & Compliance
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div class="glass-card" style="background:#fefce8; border-left:5px solid #ca8a04; margin-bottom:40px; padding:20px; border-radius:15px; max-width:900px; margin-left:auto; margin-right:auto;">
            <p style="margin:0; font-size:14px; color:#854d0e;"><strong>Compliance Protocol:</strong> This node manages global ethics and legal constraints. <strong>Pro-Tip:</strong> Ensure your jurisdictional requirements are reflected here to maintain operational integrity in high-stakes sectors.</p>
        </div>
        <?php $alignment = get_theme_mod('gp_legal_alignment', 'left'); ?>
        <div style="max-width:900px; margin:0 auto; text-align: <?php echo esc_attr($alignment); ?>;" class="gp-reveal">
            <div class="glass-card" style="padding:100px 80px; border-top: 15px solid var(--secondary);">
                <div style="font-size:12px; font-weight:950; color:var(--secondary); text-transform:uppercase; letter-spacing:4px; margin-bottom:25px;"><?php echo esc_html(get_theme_mod("gp_legal_headline", "COMPLIANCE PROTOCOL")); ?></div>
                <h1 class="text-gradient" style="font-size:3.5rem; margin-bottom:50px;"><?php the_title(); ?></h1>

                <div class="entry-content" style="font-size:1.1rem; line-height:2; opacity:0.8; color:var(--text); text-align: <?php echo esc_attr($alignment); ?>;">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
                </div>

                <div style="margin-top:80px; padding-top:50px; border-top:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-size:11px; font-weight:800; opacity:0.4; letter-spacing:1px;">LAST REVISION: <?php the_modified_date(); ?></div>
                    <div style="display:flex; gap:20px; align-items:center;">
                        <span style="width:8px; height:8px; background:#10B981; border-radius:50%;"></span>
                        <span style="font-size:11px; font-weight:950; letter-spacing:1px;">ENCRYPTION ACTIVE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
