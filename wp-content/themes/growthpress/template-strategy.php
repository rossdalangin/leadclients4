<?php
/**
 * Template Name: Strategic ROI Roadmap
 */

get_header();

$niche = get_option('growthpress_niche', 'business');
$ai = GrowthPress_AI::get_instance();
$roadmap = '';

if ( is_user_logged_in() ) {
    $user = wp_get_current_user();
    $leads = get_posts(array('post_type' => 'gp_lead', 'meta_key' => '_lead_email', 'meta_value' => $user->user_email, 'posts_per_page' => 1));
    if ( ! empty($leads) ) {
        $lead_id = $leads[0]->ID;
        $roadmap = get_post_meta($lead_id, '_gp_growth_roadmap', true);
        if ( ! $roadmap ) {
            $roadmap = $ai->generate_growth_roadmap($niche);
            update_post_meta($lead_id, '_gp_growth_roadmap', $roadmap);
        }
    }
}

if ( ! $roadmap ) {
    $roadmap = $ai->generate_growth_roadmap($niche);
}

?>

<main id="primary" class="site-main container" style="padding: 120px 0;">
    <div class="glass-card" style="background:#f1f5f9; border-left:5px solid #64748b; margin-bottom:60px; padding:25px; text-align:center;">
        <p style="margin:0; font-size:15px; color:#1e293b;"><strong>Actionable Intelligence:</strong> <?php echo esc_html(get_theme_mod('gp_strategy_pattern', "This roadmap uses the 'Market Dominance Sequence' to identify adjacent market sectors based on your sector implementation delta.")); ?></p>
    </div>
    <div style="text-align:center; margin-bottom:80px;">
        <div style="font-size:12px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:4px; margin-bottom:20px;">OPERATIONAL ARCHITECTURE v6.3</div>
        <h1 class="text-gradient" style="font-size:4.5rem; letter-spacing:-0.06em; line-height:1;"><?php echo esc_html(get_theme_mod('gp_strategy_headline', 'Strategic ROI Roadmap')); ?></h1>
        <p style="font-size:1.3rem; opacity:0.7; max-width:800px; margin:25px auto 0;"><?php echo esc_html(get_theme_mod('gp_strategy_subheadline', 'Your 12-month trajectory for market dominance, engineered by the GrowthPress AI neural engine.')); ?></p>
    </div>

    <div class="glass-card gp-reveal" style="padding:80px; border-radius:50px; line-height:1.8; font-size:1.1rem;">
        <div style="background:rgba(37,99,235,0.05); border-left:5px solid var(--primary); padding:25px; border-radius:15px; margin-bottom:50px;">
            <p style="margin:0; font-size:14px; color:var(--primary); font-weight:600;"><strong>Strategic Instruction:</strong> <?php echo esc_html(get_theme_mod('gp_strategy_instruction', "This roadmap is generated using your active niche benchmarks. It identifies the high-stakes nodes required to transition from manual operations to an autonomous growth engine. Execute the phases sequentially to maintain system integrity.")); ?></p>
        </div>
        <div class="entry-content">
            <?php echo apply_filters('the_content', $roadmap); ?>
        </div>

        <div style="margin-top:80px; padding-top:60px; border-top:1px solid rgba(0,0,0,0.05); display:grid; grid-template-columns: 1fr 1.5fr; gap:60px; align-items:center;">
            <div>
                <h3 style="font-size:24px; margin-bottom:20px;">Execute Strategic Next Step</h3>
                <p style="opacity:0.6; margin-bottom:30px;">Initialize the first node of your growth sequence by scheduling a mandatory operational audit.</p>
                <a href="<?php echo home_url(get_theme_mod('gp_header_cta_link', '/book-now')); ?>" class="gp-btn">INITIATE AUDIT</a>
            </div>
            <div style="background:var(--primary-glow); padding:40px; border-radius:30px; border:1px solid rgba(37,99,235,0.1);">
                <div style="font-size:10px; font-weight:950; color:var(--primary); letter-spacing:2px; margin-bottom:15px;">NEURAL PROBABILITY OF DOMINANCE</div>
                <div style="font-size:4rem; font-weight:950; color:var(--primary); line-height:1;">94.2%</div>
                <p style="font-size:12px; opacity:0.5; margin-top:15px;">Based on current sector saturation and automation implementation delta.</p>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
