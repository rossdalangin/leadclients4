<?php
/**
 * Main template file
 */
get_header();
?>
<main id="primary" class="site-main grainy-bg" style="padding: 100px 0;">
    <div class="container">
        <div class="glass-card" style="background:#f0f7ff; border-left:5px solid #2563eb; margin-bottom:60px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#1e40af;"><strong>System Index:</strong> This is the fallback template for the GrowthPress ecosystem. <strong>Strategic Note:</strong> <?php echo esc_html(get_theme_mod('gp_index_note', 'High-stakes pages should use specialized templates (e.g. Strategic Services Hub) for optimized conversion trajectory.')); ?></p>
        </div>
        <?php if ( is_home() && ! is_front_page() ) : ?>
            <header style="margin-bottom: 50px;">
                <h1 class="page-title"><?php single_post_title(); ?></h1>
            </header>
        <?php endif; ?>

        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                if ( is_singular() ) : ?>
                    <article id="post-<?php the_ID(); ?>" <?php body_class(); ?>>
                        <?php if ( ! is_front_page() ) : ?>
                            <header class="entry-header" style="margin-bottom:30px;">
                                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="post-thumbnail" style="margin-top:20px; border-radius:20px; overflow:hidden;">
                                        <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto;' ) ); ?>
                                    </div>
                                <?php endif; ?>
                            </header>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php else : ?>
                    <div class="glass-card" style="margin-bottom:30px;">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="button button-small">Read More</a>
                    </div>
                <?php endif;
            endwhile;
        endif;
        ?>
    </div>
</main>
<?php
get_footer();
