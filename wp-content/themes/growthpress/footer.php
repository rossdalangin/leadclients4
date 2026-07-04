<?php $footer_style = get_theme_mod('gp_footer_style', 'luxe'); ?>
<footer id="colophon" class="site-footer footer-style-<?php echo $footer_style; ?>">
    <?php if($footer_style !== 'minimal'): ?>
        <div class="footer-glow"></div>
    <?php endif; ?>
	<div class="container footer-content">
        <div class="wp-block-columns footer-grid">
            <div class="wp-block-column brand-column">
                <h2 class="footer-brand"><?php bloginfo('name'); ?></h2>
                <p class="footer-desc">
                    <?php echo esc_html(get_theme_mod('gp_footer_desc', 'The premier AI-integrated operating system for high-stakes service firms. Engineering market dominance through relentless automation and strategic intelligence.')); ?>
                </p>
                <div class="footer-social-wrap">
                    <div class="footer-eyebrow">UPLINK CHANNELS</div>
                    <div class="footer-socials">
                        <?php
                        $socials = array('linkedin', 'twitter', 'instagram', 'facebook');
                        foreach($socials as $s):
                            $url = get_theme_mod("gp_social_$s");
                            if($url): ?>
                                <a href="<?php echo esc_url($url); ?>" target="_blank" class="social-link">
                                    <span class="dashicons dashicons-<?php echo $s === 'twitter' ? 'twitter' : $s; ?>"></span>
                                </a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="wp-block-column nav-column">
                <h4 class="footer-heading">Ecosystem</h4>
                <ul class="footer-nav">
                    <li><a href="<?php echo home_url('/services'); ?>">Strategic Services</a></li>
                    <li><a href="<?php echo home_url('/pricing'); ?>">Investment Plans</a></li>
                    <li><a href="<?php echo home_url('/case-studies'); ?>">Success Stories</a></li>
                    <li><a href="<?php echo home_url('/faq'); ?>">Intelligence Base</a></li>
                </ul>
            </div>
            <div class="wp-block-column nav-column">
                <h4 class="footer-heading">Protocol</h4>
                <ul class="footer-nav">
                    <li><a href="<?php echo home_url('/our-mission'); ?>">Mission Vision</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>">Contact Command</a></li>
                    <li><a href="#">Ethics & Compliance</a></li>
                    <li><a href="#">Portal Access</a></li>
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
