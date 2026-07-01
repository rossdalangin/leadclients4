<?php
/**
 * Template Name: Strategic Investment Tiers
 */
get_header(); ?>

<main id="primary" class="site-main grainy-bg" style="padding-top:120px; padding-bottom:120px;">
    <div class="container">
        <div style="text-align:center; margin-bottom:100px;" class="gp-reveal">
            <span class="eyebrow">CAPITAL ALLOCATION</span>
            <h1 class="text-gradient headline-xl" style="margin-bottom:35px;">
                <?php echo esc_html( get_theme_mod('gp_pricing_headline', 'Strategic Investment Tiers') ); ?>
            </h1>
            <p style="font-size:1.4rem; opacity:0.8; max-width:850px; margin:0 auto; font-weight: 500; line-height: 1.5;">
                <?php echo esc_html( get_theme_mod('gp_pricing_subheadline', 'Select the operational tier that aligns with your enterprise growth goals.') ); ?>
            </p>
            <div style="margin-top:50px; display:inline-flex; align-items:center; gap:15px; background:var(--primary-glow); color:var(--primary); padding:15px 35px; border-radius:100px; font-size:12px; font-weight:950; letter-spacing:1px; border:1px solid rgba(79, 70, 229, 0.1);">
                <span style="font-size:20px;">🚀</span> 32% AVG INCREASE IN LEAD VELOCITY DETECTED ACROSS ELITE NODES
            </div>
        </div>

        <div class="glass-card" style="background:#fefce8; border-left:5px solid #ca8a04; margin-bottom:60px; padding:25px; border-radius:15px; text-align:center;">
            <p style="margin:0; font-size:15px; color:#854d0e;"><strong>Actionable Intelligence:</strong> <?php echo esc_html(get_theme_mod('gp_pricing_pattern', 'Selecting the \'Elite Operating System\' tier activates high-stakes autonomous triage and priority specialist routing, reducing operational latency by an average of 14 hours per week.')); ?></p>
        </div>

        <div class="wp-block-columns" style="gap:40px; margin-top:80px;" class="gp-reveal gp-reveal-stagger-1">
            <div class="wp-block-column glass-card" style="padding:50px; text-align:center;">
                <h3 style="margin-bottom:15px;">Foundational Node</h3>
                <div style="font-size:3rem; font-weight:950; color:var(--primary); margin-bottom:30px;">$2,500<span style="font-size:14px; opacity:0.3; letter-spacing:0;">/MO</span></div>
                <ul style="list-style:none; padding:0; font-size:15px; line-height:2.5; margin-bottom:40px; opacity:0.7;">
                    <li>Standard CRM Integration</li>
                    <li>Basic AI Triage Engine</li>
                    <li>Regional SEO Optimization</li>
                    <li>Weekly Performance Briefs</li>
                </ul>
                <a href="/book-now" class="gp-btn" style="width:100%;">Initialize Setup</a>
            </div>
            <div class="wp-block-column glass-card" style="padding:60px; text-align:center; border:2px solid var(--primary); transform: scale(1.05); z-index:2;">
                <div style="background:var(--primary); color:white; font-size:10px; font-weight:950; padding:6px 20px; border-radius:100px; display:inline-block; margin-bottom:20px; letter-spacing:1px;">MOST POPULAR</div>
                <h3 style="margin-bottom:15px;">Elite Operating System</h3>
                <div style="font-size:4rem; font-weight:950; color:var(--primary); margin-bottom:30px;">$7,500<span style="font-size:14px; opacity:0.3; letter-spacing:0;">/MO</span></div>
                <ul style="list-style:none; padding:0; font-size:16px; line-height:2.5; margin-bottom:40px; font-weight:700;">
                    <li>Full Multi-Node AI Ecosystem</li>
                    <li>Automated Proposal Generation</li>
                    <li>Advanced ROI Analytics Hub</li>
                    <li>Priority Specialist Uplink</li>
                </ul>
                <a href="/book-now" class="gp-btn" style="width:100%; height:80px; font-size:18px;">Deploy Enterprise Node</a>
            </div>
            <div class="wp-block-column glass-card" style="padding:50px; text-align:center;">
                <h3 style="margin-bottom:15px;">Global Dominance</h3>
                <div style="font-size:3rem; font-weight:950; color:var(--primary); margin-bottom:30px;">Custom</div>
                <ul style="list-style:none; padding:0; font-size:15px; line-height:2.5; margin-bottom:40px; opacity:0.7;">
                    <li>Full White-Label Architecture</li>
                    <li>Custom Neural Node Training</li>
                    <li>Multi-Location Hub Control</li>
                    <li>Unlimited Strategic Support</li>
                </ul>
                <a href="/contact" class="gp-btn" style="width:100%; background:var(--secondary);">Contact Command</a>
            </div>
        </div>

        <div style="margin-top:120px;" class="gp-reveal">
            <?php echo do_shortcode(get_theme_mod('gp_pricing_shortcode', '[gp_trust_badges]')); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
