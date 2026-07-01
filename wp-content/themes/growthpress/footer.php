<?php $footer_style = get_theme_mod('gp_footer_style', 'luxe'); ?>
<footer id="colophon" class="site-footer footer-style-<?php echo $footer_style; ?>" style="background: var(--secondary); color: white; padding: <?php echo $footer_style === 'minimal' ? '60px 0' : '120px 0 60px'; ?>; margin-top: 150px; position: relative; overflow: hidden;">
    <?php if($footer_style !== 'minimal'): ?>
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05; background: radial-gradient(circle at top right, var(--primary), transparent);"></div>
    <?php endif; ?>
	<div class="container" style="position: relative; z-index: 2;">
        <div class="wp-block-columns" style="margin-bottom: 100px; gap: 60px;">
            <div class="wp-block-column" style="flex-basis: 45%;">
                <h2 style="color: white; margin-bottom: 25px; font-family: var(--font-heading); font-size: 32px; letter-spacing: -0.04em;"><?php bloginfo('name'); ?></h2>
                <p style="color: rgba(255,255,255,0.5); font-size: 17px; line-height: 1.8; max-width: 400px;">
                    <?php echo esc_html(get_theme_mod('gp_footer_desc', 'The premier AI-integrated operating system for high-stakes service firms. Engineering market dominance through relentless automation and strategic intelligence.')); ?>
                </p>
                <div style="margin-top: 40px; display: flex; gap: 30px; align-items: center;">
                    <div style="font-size: 10px; font-weight: 950; letter-spacing: 2px; opacity: 0.4;">UPLINK CHANNELS</div>
                    <div style="display: flex; gap: 15px;">
                        <?php
                        $socials = array('linkedin', 'twitter', 'instagram', 'facebook');
                        foreach($socials as $s):
                            $url = get_theme_mod("gp_social_$s");
                            if($url): ?>
                                <a href="<?php echo esc_url($url); ?>" target="_blank" style="width: 36px; height: 36px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; display:flex; align-items:center; justify-content:center; text-decoration:none; color:white; transition:0.3s;">
                                    <span class="dashicons dashicons-<?php echo $s === 'twitter' ? 'twitter' : $s; ?>" style="font-size:16px;"></span>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="wp-block-column">
                <h4 style="color: white; font-weight: 950; text-transform: uppercase; letter-spacing: 2px; font-size: 11px; margin-bottom: 35px; opacity: 0.4;">Ecosystem</h4>
                <ul style="list-style: none; padding: 0; font-size: 15px; line-height: 2.8; font-weight: 600;">
                    <li><a href="<?php echo home_url('/services'); ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Strategic Services</a></li>
                    <li><a href="<?php echo home_url('/pricing'); ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Investment Plans</a></li>
                    <li><a href="<?php echo home_url('/case-studies'); ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Success Stories</a></li>
                    <li><a href="<?php echo home_url('/faq'); ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Intelligence Base</a></li>
                </ul>
            </div>
            <div class="wp-block-column">
                <h4 style="color: white; font-weight: 950; text-transform: uppercase; letter-spacing: 2px; font-size: 11px; margin-bottom: 35px; opacity: 0.4;">Protocol</h4>
                <ul style="list-style: none; padding: 0; font-size: 15px; line-height: 2.8; font-weight: 600;">
                    <li><a href="<?php echo home_url('/our-mission'); ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Mission Vision</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Contact Command</a></li>
                    <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Ethics & Compliance</a></li>
                    <li><a href="#" style="color: rgba(255,255,255,0.7); text-decoration: none; transition: 0.3s;">Portal Access</a></li>
                </ul>
            </div>
        </div>
		<div class="site-info" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 50px; display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: rgba(255,255,255,0.3); font-weight: 600;">
			<div>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Elite Business OS v6.3 Definitive.</div>
            <div style="display: flex; gap: 30px; align-items: center;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#10B981; box-shadow: 0 0 10px #10B981;"></span>
                    <?php echo esc_html(get_theme_mod('gp_footer_status_label', 'SYSTEM ONLINE')); ?>
                </div>
                <div style="padding: 6px 15px; border-radius: 100px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); color: var(--accent);"><?php echo esc_html(get_theme_mod('gp_footer_neural_label', 'NEURAL LINK ACTIVE')); ?></div>
            </div>
		</div>
	</div>
</footer>

<?php if(get_theme_mod('gp_enable_sticky_cta', true)): ?>
<div class="gp-mobile-cta-bar gp-reveal" style="animation-delay: 1s;">
    <div class="price-info">
        <span class="price-val">FREE</span>
        STRATEGY BRIEF
    </div>
    <a href="<?php echo home_url('/book-now'); ?>" class="gp-btn" style="padding: 12px 25px; font-size: 12px; border-radius: 12px;">SECURE NOW</a>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
