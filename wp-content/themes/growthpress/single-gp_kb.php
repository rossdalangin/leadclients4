<?php
/**
 * Single Knowledge Base Article Template - GrowthPress Elite
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:100px; padding-bottom:120px;">
    <div class="container">
        <div style="margin-bottom:60px;" class="gp-reveal">
            <a href="/faq" style="font-size:11px; font-weight:950; letter-spacing:3px; text-decoration:none; opacity:0.5; color:var(--text); text-transform:uppercase;">&larr; Return to Intelligence Hub</a>
        </div>

        <div class="glass-card" style="background:#f0f7ff; border-left:5px solid #2563eb; margin-bottom:60px; padding:25px; border-radius:15px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#9d174d;"><strong>Neural Context:</strong> <?php echo esc_html(get_theme_mod('gp_single_kb_intel', "This article is indexed within the 'Neural Hub' and serves as context for autonomous AI discovery sequences.")); ?></p>
        </div>

        <?php while ( have_posts() ) : the_post(); ?>
            <div class="wp-block-columns" style="gap:60px;">
                <div class="wp-block-column" style="flex-basis:70%;">
                    <article id="post-<?php the_ID(); ?>" <?php post_class('glass-card gp-reveal'); ?> style="padding:80px; border-radius:48px; box-shadow: 0 40px 80px rgba(0,0,0,0.05);">
                        <header class="entry-header">
                            <span class="eyebrow">TECHNICAL INTELLIGENCE NODE</span>
                            <h1 class="text-gradient headline-lg" style="margin-bottom:45px;"><?php the_title(); ?></h1>
                        </header>

                        <div class="entry-content" style="font-size:1.25rem; line-height:1.8; color:var(--text); opacity:0.8; font-weight: 500;">
                            <?php the_content(); ?>
                        </div>

                        <?php if(has_tag()): ?>
                            <div style="margin-top:50px; padding-top:30px; border-top:1px solid #F1F5F9; display:flex; gap:10px;">
                                <?php the_tags('', '', ''); ?>
                            </div>
                        <?php endif; ?>
                    </article>

                    <!-- AI Contextual Assistant -->
                    <div class="glass-card" style="margin-top:40px; background:var(--secondary); color:white; border:none; padding:50px; border-radius:40px;">
                        <h3 style="color:white; margin-bottom:20px;">Have more questions?</h3>
                        <p style="opacity:0.7; margin-bottom:30px;">Our AI assistant has indexed this technical article and is ready to help you implement these strategies.</p>
                        <button onclick="jQuery('#gp-chat-launcher').click()" class="gp-btn" style="background:var(--primary); color:white !important; border:none; height:60px; padding:0 40px; border-radius:15px;">ASK AI ASSISTANT</button>
                    </div>
                </div>

                <div class="wp-block-column">
                    <div class="glass-card" style="padding:40px; position:sticky; top:40px;">
                        <h4 style="margin-top:0; font-size:11px; font-weight:950; opacity:0.3; letter-spacing:2px; text-transform:uppercase;">Hub Navigation</h4>
                        <div style="margin-top:30px;">
                            <?php echo do_shortcode('[gp_kb_search]'); ?>
                        </div>
                        <div style="margin-top:40px;">
                            <h5 style="margin-bottom:15px;">Related Intelligence</h5>
                            <ul style="list-style:none; padding:0; font-size:13px; font-weight:700;">
                                <?php
                                $related = get_posts(array('post_type'=>'gp_kb', 'posts_per_page'=>3, 'post__not_in'=>array(get_the_ID())));
                                foreach($related as $r): ?>
                                    <li style="margin-bottom:15px;"><a href="<?php echo get_permalink($r->ID); ?>" style="text-decoration:none;"><?php echo esc_html($r->post_title); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
