<?php
/**
 * GrowthPress Theme Functions - Advanced Customizer v6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

function growthpress_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
    register_nav_menus( array( 'primary' => 'Primary Menu' ) );
}
add_action( 'after_setup_theme', 'growthpress_setup' );

/**
 * Register Customizer Settings
 */
function growthpress_customize_register( $wp_customize ) {
    // Panels for Organization
    $wp_customize->add_panel( 'gp_design_architecture', array( 'title' => '1. Design Architecture', 'priority' => 30 ) );
    $wp_customize->add_panel( 'gp_content_hubs', array( 'title' => '2. Content Hub Strategy', 'priority' => 31 ) );
    $wp_customize->add_panel( 'gp_marketing_nodes', array( 'title' => '3. Marketing & ROI', 'priority' => 32 ) );
    $wp_customize->add_panel( 'gp_homepage_sections', array( 'title' => '4. Homepage Command Center', 'priority' => 33 ) );

    // 1. Elite Branding & Geometry
    $wp_customize->add_section( 'growthpress_branding', array(
        'title' => 'Global Identity & Geometry',
        'panel' => 'gp_design_architecture',
        'priority' => 10,
    ) );

    $wp_customize->add_setting( 'gp_design_style', array( 'default' => 'unisex', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_design_style', array(
        'label' => 'Strategic Design Set', 'section' => 'growthpress_branding', 'type' => 'select',
        'choices' => array( 'unisex' => 'Minimalist Modern (Unisex)', 'male' => 'Bold Executive (Male Focus)', 'female' => 'Elegant Professional (Female Focus)' ),
    ) );

    $wp_customize->add_setting( 'gp_global_radius', array( 'default' => '32px', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_global_radius', array( 'label' => 'Global Corner Geometry', 'section' => 'growthpress_branding', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_blur_intensity', array( 'default' => '40px', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_blur_intensity', array( 'label' => 'Glass Blur Intensity', 'section' => 'growthpress_branding', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_animation_speed', array( 'default' => '0.8s', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_animation_speed', array( 'label' => 'Interaction Animation Speed', 'section' => 'growthpress_branding', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_glass_opacity', array( 'default' => '0.88', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_glass_opacity', array( 'label' => 'Glass Card Opacity', 'section' => 'growthpress_branding', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_dark_accent_color', array( 'default' => '#818CF8', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gp_dark_accent_color', array( 'label' => 'Dark Mode Accent', 'section' => 'growthpress_branding', 'description' => 'Vibrant cyber-accents for high-stakes UI. Clinical and precise.' ) ) );

    $wp_customize->add_setting( 'gp_dark_bg_color', array( 'default' => '#020617', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gp_dark_bg_color', array( 'label' => 'Dark Mode Background', 'section' => 'growthpress_branding', 'description' => 'Deep slate background for maximum readability in high-concentration environments.' ) ) );

    $wp_customize->add_setting( 'gp_dark_surface_color', array( 'default' => '#0F172A', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'gp_dark_surface_color', array( 'label' => 'Dark Mode Surface', 'section' => 'growthpress_branding', 'description' => 'Elevation surface for glass cards. Anchors high-authority content.' ) ) );

    $wp_customize->add_setting( 'growthpress_primary_color', array( 'default' => '#4F46E5', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'growthpress_primary_color', array( 'label' => 'Primary Brand Node', 'section' => 'growthpress_branding' ) ) );

    $wp_customize->add_setting( 'gp_global_share_image', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'gp_global_share_image', array(
        'label' => 'Global Social Share Image', 'section' => 'growthpress_branding'
    ) ) );

    $wp_customize->add_setting( 'gp_elite_gradient', array( 'default' => 'linear-gradient(135deg, #1E1B4B 0%, #4F46E5 100%)', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_elite_gradient', array(
        'label' => 'Aesthetic Trajectory Gradient', 'section' => 'growthpress_branding', 'type' => 'select',
        'choices' => array(
            'linear-gradient(135deg, #1E1B4B 0%, #4F46E5 100%)' => 'Midnight Indigo (Unisex)',
            'linear-gradient(135deg, #020617 0%, #2563EB 100%)' => 'Deep Blue Executive (Male)',
            'linear-gradient(135deg, #4C0519 0%, #BE185D 100%)' => 'Royal Rose Luxe (Female)',
            'linear-gradient(135deg, #064E3B 0%, #10B981 100%)' => 'Emerald Growth (Green)'
        ),
    ) );

    $wp_customize->add_setting( 'gp_header_layout', array( 'default' => 'space-between', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_header_layout', array(
        'label' => 'Dynamic Header Architecture', 'section' => 'growthpress_branding', 'type' => 'select',
        'choices' => array(
            'space-between' => 'Logo Left / Nav Center / CTA Right',
            'center'        => 'Logo & Nav Centered (Symmetric)',
            'flex-start'    => 'Logo Left / Nav Left / CTA Right'
        ),
    ) );

    // 2. Homepage Content Engine
    $wp_customize->add_section( 'growthpress_homepage', array( 'title' => 'Homepage Content Strategy', 'panel' => 'gp_content_hubs', 'priority' => 10 ) );

    // Hero
    $wp_customize->add_setting( 'gp_hero_headline', array( 'default' => 'Transform Your Business with AI Intelligence', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_headline', array( 'label' => 'Hero Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_hero_subheadline', array( 'default' => 'The unified operating system for high-ticket service firms. Scale faster, automate smarter.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_subheadline', array( 'label' => 'Hero Subheadline', 'section' => 'growthpress_homepage', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_hero_quiz_headline', array( 'default' => 'Get Your Custom Roadmap', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_quiz_headline', array( 'label' => 'Hero Quiz Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_hero_quiz_shortcode', array( 'default' => '[gp_quiz_lead_form]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_quiz_shortcode', array( 'label' => 'Hero Quiz Shortcode', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_hero_status_label', array( 'default' => 'CORE ACTIVE', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_status_label', array( 'label' => 'Hero Status Label', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_hero_eyebrow', array( 'default' => 'ELITE BUSINESS OS v6.3', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_eyebrow', array( 'label' => 'Hero Eyebrow', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_hero_btn_text', array( 'default' => 'Initiate Strategic Setup', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_hero_btn_text', array( 'label' => 'Hero Button Text', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_booking_shortcode', array( 'default' => '[gp_booking_form]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_booking_shortcode', array( 'label' => 'Global Booking Shortcode', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    // Competitive Delta
    $wp_customize->add_setting( 'gp_delta_eyebrow', array( 'default' => 'THE COMPETITIVE DELTA', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_delta_eyebrow', array( 'label' => 'Competitive Delta Eyebrow', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_delta_headline', array( 'default' => 'Engineered for Market Dominance', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_delta_headline', array( 'label' => 'Competitive Delta Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_delta_subheadline', array( 'default' => 'Our 14-node relational architecture eliminates operational latency and maximizes conversion velocity.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_delta_subheadline', array( 'label' => 'Competitive Delta Subheadline', 'section' => 'growthpress_homepage', 'type' => 'textarea' ) );

    // Advantage Grid
    for($i=1; $i<=3; $i++) {
        $wp_customize->add_setting( "gp_adv_icon_$i", array( 'default' => ($i==1?'🧠':($i==2?'📊':'⚡')), 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_adv_icon_$i", array( 'label' => "Advantage $i Icon", 'section' => 'growthpress_homepage', 'type' => 'text' ) );
        $wp_customize->add_setting( "gp_adv_title_$i", array( 'default' => ($i==1?'Neural Triage':($i==2?'ROI Forecasting':'Velocity Protocol')), 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_adv_title_$i", array( 'label' => "Advantage $i Title", 'section' => 'growthpress_homepage', 'type' => 'text' ) );
        $wp_customize->add_setting( "gp_adv_desc_$i", array( 'default' => 'Description for advantage item ' . $i, 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_adv_desc_$i", array( 'label' => "Advantage $i Desc", 'section' => 'growthpress_homepage', 'type' => 'textarea' ) );
    }

    // System Topology
    $wp_customize->add_setting( 'gp_topology_eyebrow', array( 'default' => 'SYSTEM TOPOLOGY', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_topology_eyebrow', array( 'label' => 'Topology Eyebrow', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_topology_headline', array( 'default' => 'A Unified Neural Ecosystem', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_topology_headline', array( 'label' => 'Topology Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_topology_subheadline', array( 'default' => 'GrowthPress OS isn\'t just a theme—it\'s a high-stakes infrastructure that connects your CRM, Booking, Proposals, and KB articles into one intelligent node.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_topology_subheadline', array( 'label' => 'Topology Subheadline', 'section' => 'growthpress_homepage', 'type' => 'textarea' ) );

    for($i=1; $i<=4; $i++) {
        $wp_customize->add_setting( "gp_topo_list_$i", array( 'default' => 'Feature Node ' . $i, 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_topo_list_$i", array( 'label' => "Topology List Item $i", 'section' => 'growthpress_homepage', 'type' => 'text' ) );
    }

    // Verified Trajectories
    $wp_customize->add_setting( 'gp_trajectories_eyebrow', array( 'default' => 'VERIFIED TRAJECTORIES', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_trajectories_eyebrow', array( 'label' => 'Trajectories Eyebrow', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_trajectories_headline', array( 'default' => 'Proven ROI Across All Sectors', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_trajectories_headline', array( 'label' => 'Trajectories Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_homepage_stats_shortcode', array( 'default' => '[gp_stats_bar]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_homepage_stats_shortcode', array( 'label' => 'Stats Bar Shortcode', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_homepage_radar_shortcode', array( 'default' => '[gp_ecosystem_radar]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_homepage_radar_shortcode', array( 'label' => 'Ecosystem Radar Shortcode', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    // Methodology Section Toggle
    $wp_customize->add_setting( 'gp_enable_homepage_methodology', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_homepage_methodology', array( 'label' => 'Enable Methodology Section', 'section' => 'growthpress_homepage', 'type' => 'checkbox' ) );

    // Niche Node
    $wp_customize->add_setting( 'gp_niche_node_headline', array( 'default' => 'Calculate Your Growth Potential', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_niche_node_headline', array( 'label' => 'Niche Node Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );

    // Final CTA
    $wp_customize->add_setting( 'gp_final_cta_headline', array( 'default' => 'Ready for Dominance?', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_final_cta_headline', array( 'label' => 'Final CTA Headline', 'section' => 'growthpress_homepage', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_final_cta_subheadline', array( 'default' => 'Initialize your GrowthPress OS node today and join the elite top 1% of firms.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_final_cta_subheadline', array( 'label' => 'Final CTA Subheadline', 'section' => 'growthpress_homepage', 'type' => 'textarea' ) );

    // Homepage Section: Strategic Process
    $wp_customize->add_section( 'gp_homepage_process', array( 'title' => 'Strategic Process', 'panel' => 'gp_homepage_sections', 'priority' => 10 ) );
    $wp_customize->add_setting( 'gp_enable_process', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_process', array( 'label' => 'Enable Process Section', 'section' => 'gp_homepage_process', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'gp_process_headline', array( 'default' => 'The Journey to Dominance', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_process_headline', array( 'label' => 'Process Headline', 'section' => 'gp_homepage_process', 'type' => 'text' ) );

    for($i=1; $i<=3; $i++) {
        $wp_customize->add_setting( "gp_process_step_title_$i", array( 'default' => "Phase 0$i", 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_process_step_title_$i", array( 'label' => "Step $i Title", 'section' => 'gp_homepage_process', 'type' => 'text' ) );
        $wp_customize->add_setting( "gp_process_step_desc_$i", array( 'default' => "Description for phase $i.", 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_process_step_desc_$i", array( 'label' => "Step $i Desc", 'section' => 'gp_homepage_process', 'type' => 'textarea' ) );
    }

    // Homepage Section: Specialist Preview
    $wp_customize->add_section( 'gp_homepage_team', array( 'title' => 'Specialist Preview', 'panel' => 'gp_homepage_sections', 'priority' => 20 ) );
    $wp_customize->add_setting( 'gp_enable_homepage_team', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_homepage_team', array( 'label' => 'Enable Specialist Preview', 'section' => 'gp_homepage_team', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'gp_homepage_team_headline', array( 'default' => 'Human Capital Authority', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_homepage_team_headline', array( 'label' => 'Team Section Headline', 'section' => 'gp_homepage_team', 'type' => 'text' ) );

    // Homepage Section: Social Proof
    $wp_customize->add_section( 'gp_homepage_proof', array( 'title' => 'Social Proof Hub', 'panel' => 'gp_homepage_sections', 'priority' => 30 ) );
    $wp_customize->add_setting( 'gp_enable_homepage_proof', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_homepage_proof', array( 'label' => 'Enable Proof Section', 'section' => 'gp_homepage_proof', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'gp_homepage_proof_headline', array( 'default' => 'Verified Market Authority', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_homepage_proof_headline', array( 'label' => 'Proof Headline', 'section' => 'gp_homepage_proof', 'type' => 'text' ) );

    // 3. Strategic Services Admin
    $wp_customize->add_section( 'growthpress_services_admin', array( 'title' => 'Service Page Strategy', 'panel' => 'gp_content_hubs', 'priority' => 20 ) );

    $wp_customize->add_setting( 'gp_single_service_roi_label', array( 'default' => '+315%', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_single_service_roi_label', array( 'label' => 'Single Service ROI Label', 'section' => 'growthpress_services_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_services_headline', array( 'default' => 'Elite Service Infrastructure', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_services_headline', array( 'label' => 'Services Headline', 'section' => 'growthpress_services_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_services_subheadline', array( 'default' => 'Proprietary methodologies engineered for market dominance and high-ticket returns.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_services_subheadline', array( 'label' => 'Services Subheadline', 'section' => 'growthpress_services_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_services_shortcode', array( 'default' => '[gp_service_grid]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_services_shortcode', array( 'label' => 'Services Grid Shortcode', 'section' => 'growthpress_services_admin', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_services_pattern', array( 'default' => 'Service firms that integrate the Full Ecosystem Radar into their discovery process report a 44% increase in perceived authority during initial briefings.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_services_pattern', array( 'label' => 'Services Success Pattern Text', 'section' => 'growthpress_services_admin', 'type' => 'textarea' ) );

    // 4. Case Studies & ROI Admin
    $wp_customize->add_section( 'growthpress_results_admin', array( 'title' => 'Case Study Layouts', 'panel' => 'gp_content_hubs', 'priority' => 30 ) );

    $wp_customize->add_setting( 'gp_single_project_validation', array( 'default' => 'This project profile demonstrates the \'Autonomous Realization\' pattern, where operational latency was reduced by 40% through v6.3 multi-node intelligence.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_single_project_validation', array( 'label' => 'Single Project Validation Pattern', 'section' => 'growthpress_results_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_results_headline', array( 'default' => 'Verified Results & ROI Profiles', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_results_headline', array( 'label' => 'Results Headline', 'section' => 'growthpress_results_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_results_subheadline', array( 'default' => 'Visual confirmation of our precision engineering and client success trajectories.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_results_subheadline', array( 'label' => 'Results Subheadline', 'section' => 'growthpress_results_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_results_shortcode', array( 'default' => '[gp_case_study_grid]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_results_shortcode', array( 'label' => 'Results Grid Shortcode', 'section' => 'growthpress_results_admin', 'type' => 'text' ) );

    // 5. Pricing & Investment Strategy
    $wp_customize->add_section( 'growthpress_pricing_admin', array( 'title' => 'Pricing Infrastructure', 'panel' => 'gp_marketing_nodes', 'priority' => 10 ) );
    $wp_customize->add_setting( 'gp_pricing_headline', array( 'default' => 'Strategic Investment Tiers', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_pricing_headline', array( 'label' => 'Pricing Headline', 'section' => 'growthpress_pricing_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_pricing_subheadline', array( 'default' => 'Select the operational tier that aligns with your enterprise growth goals.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_pricing_subheadline', array( 'label' => 'Pricing Subheadline', 'section' => 'growthpress_pricing_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_pricing_shortcode', array( 'default' => '[gp_trust_badges]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_pricing_shortcode', array( 'label' => 'Pricing Trust Shortcode', 'section' => 'growthpress_pricing_admin', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_pricing_pattern', array( 'default' => 'Selecting the \'Elite Operating System\' tier activates high-stakes autonomous triage and priority specialist routing, reducing operational latency by an average of 14 hours per week.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_pricing_pattern', array( 'label' => 'Pricing Success Pattern Text', 'section' => 'growthpress_pricing_admin', 'type' => 'textarea' ) );

    // 6. About & Mission Strategy
    $wp_customize->add_section( 'growthpress_about_admin', array( 'title' => 'Mission Vision Strategy', 'panel' => 'gp_content_hubs', 'priority' => 40 ) );

    // 7. Team & Specialist Strategy
    $wp_customize->add_section( 'growthpress_team_admin', array( 'title' => 'Team Node Strategy', 'panel' => 'gp_content_hubs', 'priority' => 50 ) );
    $wp_customize->add_setting( 'gp_team_headline', array( 'default' => 'Specialized Operational Team', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_team_headline', array( 'label' => 'Team Headline', 'section' => 'growthpress_team_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_team_subheadline', array( 'default' => 'Elite human capital nodes trained in high-stakes operational execution.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_team_subheadline', array( 'label' => 'Team Subheadline', 'section' => 'growthpress_team_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_team_shortcode', array( 'default' => '[gp_staff_grid]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_team_shortcode', array( 'label' => 'Team Grid Shortcode', 'section' => 'growthpress_team_admin', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_team_pattern', array( 'default' => 'Firms with at least 3 authenticated Specialist Nodes report a 28% higher client retention rate due to specialized operational depth.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_team_pattern', array( 'label' => 'Team Success Pattern Text', 'section' => 'growthpress_team_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_single_staff_intel', array( 'default' => 'This dossier profiles a high-performance operational node. Pro-Tip: Schedule a \'Strategic Briefing\' directly from the dossier to reduce project kickoff latency.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_single_staff_intel', array( 'label' => 'Single Staff Intel Pattern', 'section' => 'growthpress_team_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_about_headline', array( 'default' => 'Engineering Market Dominance', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_about_headline', array( 'label' => 'About Headline', 'section' => 'growthpress_about_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_about_text', array( 'default' => 'We are dedicated to building the worlds most advanced business growth operating systems, empowering high-ticket firms with autonomous intelligence.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'gp_about_text', array( 'label' => 'Mission Statement', 'section' => 'growthpress_about_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_about_vision_node', array( 'default' => 'Our mission is to move firms from \'Manual Latency\' to \'Autonomous Realization\'. v6.3 Elite architecture is the standard for high-performance service delivery.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_about_vision_node', array( 'label' => 'About Vision Node Text', 'section' => 'growthpress_about_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_about_shortcode', array( 'default' => '[gp_stats_bar]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_about_shortcode', array( 'label' => 'About Stats Shortcode', 'section' => 'growthpress_about_admin', 'type' => 'text' ) );

    for($i=1; $i<=3; $i++) {
        $wp_customize->add_setting( "gp_about_benefit_icon_$i", array( 'default' => ($i==1?'🤖':($i==2?'📊':'⚡')), 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_about_benefit_icon_$i", array( 'label' => "Benefit $i Icon", 'section' => 'growthpress_about_admin', 'type' => 'text' ) );
        $wp_customize->add_setting( "gp_about_benefit_title_$i", array( 'default' => ($i==1?'Neural Triage':($i==2?'ROI Analytics':'Velocity Protocol')), 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_about_benefit_title_$i", array( 'label' => "Benefit $i Title", 'section' => 'growthpress_about_admin', 'type' => 'text' ) );
        $wp_customize->add_setting( "gp_about_benefit_desc_$i", array( 'default' => 'Description for benefit item ' . $i, 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "gp_about_benefit_desc_$i", array( 'label' => "Benefit $i Desc", 'section' => 'growthpress_about_admin', 'type' => 'textarea' ) );
    }

    // KB Hub Strategy
    $wp_customize->add_section( 'growthpress_kb_admin', array( 'title' => 'KB Hub Strategy', 'panel' => 'gp_content_hubs', 'priority' => 60 ) );

    $wp_customize->add_setting( 'gp_single_kb_intel', array( 'default' => 'This article is indexed within the \'Neural Hub\' and serves as context for autonomous AI discovery sequences.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_single_kb_intel', array( 'label' => 'Single KB Intel Pattern', 'section' => 'growthpress_kb_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_kb_pattern', array( 'default' => 'A robust technical repository reduces \'Support Node\' latency by 65%. Pro-Tip: High-value leads frequently search for "ROI" and "Implementation" keywords before booking.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_kb_pattern', array( 'label' => 'KB Success Pattern Text', 'section' => 'growthpress_kb_admin', 'type' => 'textarea' ) );

    // Inventory Hub Strategy
    $wp_customize->add_section( 'growthpress_inventory_admin', array( 'title' => 'Inventory Hub Strategy', 'panel' => 'gp_content_hubs', 'priority' => 70 ) );

    $wp_customize->add_setting( 'gp_single_property_intel', array( 'default' => 'This asset is synced with the \'Neural Lifestyle Matcher\'. Success Pattern: Elite nodes with verified lifestyle tags report a 55% higher conversion probability.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_single_property_intel', array( 'label' => 'Single Property Intel Pattern', 'section' => 'growthpress_inventory_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_inventory_pattern', array( 'default' => 'Portfolios utilizing \'Neural Lifestyle Matching\' report a 55% increase in high-net-worth lead engagement with off-market assets.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_inventory_pattern', array( 'label' => 'Inventory Success Pattern Text', 'section' => 'growthpress_inventory_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_inventory_shortcode', array( 'default' => '[gp_inventory_grid]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_inventory_shortcode', array( 'label' => 'Inventory Grid Shortcode', 'section' => 'growthpress_inventory_admin', 'type' => 'text' ) );

    // 7. Market Authority & Social Proof
    $wp_customize->add_section( 'growthpress_authority_admin', array( 'title' => 'Market Authority Feed', 'panel' => 'gp_marketing_nodes', 'priority' => 20 ) );
    $wp_customize->add_setting( 'gp_enable_authority_feed', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_authority_feed', array( 'label' => 'Enable Real-time Feed', 'section' => 'growthpress_authority_admin', 'type' => 'checkbox' ) );

    $wp_customize->add_setting( 'gp_authority_interval', array( 'default' => '30000', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_authority_interval', array(
        'label' => 'Feed Pulse Frequency (ms)', 'section' => 'growthpress_authority_admin', 'type' => 'select',
        'choices' => array( '15000' => 'Fast (15s)', '30000' => 'Standard (30s)', '60000' => 'Conservative (1m)' ),
    ) );

    // 8. Contact & Support Admin
    $wp_customize->add_section( 'growthpress_contact_admin', array( 'title' => 'Contact Configuration', 'panel' => 'gp_marketing_nodes', 'priority' => 30 ) );
    $wp_customize->add_setting( 'gp_contact_headline', array( 'default' => 'Initiate Strategic Sequence', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_contact_headline', array( 'label' => 'Contact Headline', 'section' => 'growthpress_contact_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_contact_subheadline', array( 'default' => 'Uplink with our specialist team to calibrate your growth operating system.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_contact_subheadline', array( 'label' => 'Contact Subheadline', 'section' => 'growthpress_contact_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_contact_shortcode', array( 'default' => '[gp_lead_form]', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_contact_shortcode', array( 'label' => 'Contact Form Shortcode', 'section' => 'growthpress_contact_admin', 'type' => 'text' ) );

    // 8. Conversion UI & Global CTAs
    $wp_customize->add_section( 'growthpress_conversion_admin', array( 'title' => 'Conversion UI Controls', 'panel' => 'gp_design_architecture', 'priority' => 20 ) );
    $wp_customize->add_setting( 'gp_enable_sticky_cta', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_sticky_cta', array( 'label' => 'Enable Global Sticky CTA', 'section' => 'growthpress_conversion_admin', 'type' => 'checkbox' ) );

    $wp_customize->add_setting( 'gp_footer_style', array( 'default' => 'luxe', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_footer_style', array(
        'label' => 'Footer Architecture', 'section' => 'growthpress_conversion_admin', 'type' => 'select',
        'choices' => array( 'standard' => 'Standard Corporate', 'luxe' => 'High-Luxe Immersive', 'minimal' => 'Minimalist Technical' ),
    ) );

    $wp_customize->add_setting( 'gp_footer_desc', array( 'default' => 'The premier AI-integrated operating system for high-stakes service firms. Engineering market dominance through relentless automation and strategic intelligence.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_footer_desc', array( 'label' => 'Footer Description', 'section' => 'growthpress_conversion_admin', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gp_footer_status_label', array( 'default' => 'SYSTEM ONLINE', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_footer_status_label', array( 'label' => 'Footer Status Label', 'section' => 'growthpress_conversion_admin', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_footer_neural_label', array( 'default' => 'NEURAL LINK ACTIVE', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_footer_neural_label', array( 'label' => 'Footer Neural Label', 'section' => 'growthpress_conversion_admin', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_header_cta_text', array( 'default' => 'Secure My Slot', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_header_cta_text', array( 'label' => 'Header CTA Text', 'section' => 'growthpress_conversion_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_header_cta_link', array( 'default' => '/book-now', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_header_cta_link', array( 'label' => 'Header CTA Link', 'section' => 'growthpress_conversion_admin', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gp_enable_announcement', array( 'default' => false, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_announcement', array( 'label' => 'Enable Announcement Bar', 'section' => 'growthpress_conversion_admin', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'gp_announcement_text', array( 'default' => 'New High-Ticket ROI Profiles just added to the Results Gallery.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_announcement_text', array( 'label' => 'Announcement Text', 'section' => 'growthpress_conversion_admin', 'type' => 'text' ) );

    // Social Authority Profiles
    $wp_customize->add_section( 'growthpress_social_admin', array( 'title' => 'Social Uplink Profiles', 'panel' => 'gp_marketing_nodes', 'priority' => 25 ) );
    $socials = array('linkedin' => 'LinkedIn', 'twitter' => 'Twitter/X', 'instagram' => 'Instagram', 'facebook' => 'Facebook');
    foreach($socials as $id => $label) {
        $wp_customize->add_setting( "gp_social_$id", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( "gp_social_$id", array( 'label' => $label . ' URL', 'section' => 'growthpress_social_admin', 'type' => 'text' ) );
    }

    // 9. Automated Page Generation Nodes
    $wp_customize->add_section( 'growthpress_generation_admin', array( 'title' => 'Page Generation Nodes', 'priority' => 90 ) );

    $wp_customize->add_setting( 'gp_index_note', array( 'default' => 'High-stakes pages should use specialized templates (e.g. Strategic Services Hub) for optimized conversion trajectory.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_index_note', array( 'label' => 'Index Template Note', 'section' => 'growthpress_generation_admin', 'type' => 'textarea' ) );
    $gen_pages = array('home'=>'Home', 'services'=>'Services', 'pricing'=>'Pricing', 'case_studies'=>'Case Studies', 'faq'=>'FAQ', 'about'=>'Our Mission', 'booking'=>'Book Now', 'portal'=>'Client Portal', 'contact'=>'Contact');
    foreach($gen_pages as $id => $label) {
        $wp_customize->add_setting( "gp_gen_$id", array( 'default' => true, 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( "gp_gen_$id", array( 'label' => 'Generate ' . $label, 'section' => 'growthpress_generation_admin', 'type' => 'checkbox' ) );
    }

    // Strategy & Roadmap Admin
    $wp_customize->add_section( 'growthpress_strategy_admin', array(
        'title' => 'Strategy Template Admin',
        'panel' => 'gp_content_hubs',
        'priority' => 80,
        'description' => 'Configure the ROI Roadmap templates. These settings guide the client through their strategic implementation journey.'
    ) );
    $wp_customize->add_setting( 'gp_strategy_headline', array( 'default' => 'Strategic ROI Roadmap', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_strategy_headline', array( 'label' => 'Strategy Headline', 'section' => 'growthpress_strategy_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_strategy_subheadline', array( 'default' => 'Your 12-month trajectory for market dominance and autonomous realization.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_strategy_subheadline', array( 'label' => 'Strategy Subheadline', 'section' => 'growthpress_strategy_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_strategy_instruction', array( 'default' => 'This roadmap is generated using your active niche benchmarks. It identifies the high-stakes nodes required to transition from manual operations to an autonomous growth engine. Execute the phases sequentially to maintain system integrity.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_strategy_instruction', array( 'label' => 'Strategy Instruction Pattern', 'section' => 'growthpress_strategy_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_strategy_pattern', array( 'default' => 'This roadmap uses the \'Market Dominance Sequence\' to identify adjacent market sectors based on your sector implementation delta.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_strategy_pattern', array( 'label' => 'Strategy Success Pattern', 'section' => 'growthpress_strategy_admin', 'type' => 'textarea' ) );

    // FAQ Hub Strategy
    $wp_customize->add_section( 'growthpress_faq_admin', array(
        'title' => 'FAQ Hub Strategy',
        'panel' => 'gp_content_hubs',
        'priority' => 85,
        'description' => 'Calibrate the Intelligence Base and FAQ node.'
    ) );
    $wp_customize->add_setting( 'gp_faq_headline', array( 'default' => 'Intelligence Base', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_faq_headline', array( 'label' => 'FAQ Headline', 'section' => 'growthpress_faq_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_faq_subheadline', array( 'default' => 'Search our neural-indexed knowledge base for technical and strategic insights.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_faq_subheadline', array( 'label' => 'FAQ Subheadline', 'section' => 'growthpress_faq_admin', 'type' => 'textarea' ) );

    // Legal Template Strategy
    $wp_customize->add_section( 'growthpress_legal_admin', array(
        'title' => 'Legal & Compliance Layout',
        'panel' => 'gp_design_architecture',
        'priority' => 80,
        'description' => 'Configure the layout of Privacy, Terms, and Compliance pages.'
    ) );
    $wp_customize->add_setting( 'gp_legal_alignment', array( 'default' => 'left', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_legal_alignment', array(
        'label' => 'Legal Text Alignment', 'section' => 'growthpress_legal_admin', 'type' => 'select',
        'choices' => array( 'left' => 'Standard Left', 'center' => 'Centered Executive' ),
    ) );

    // Portal Login Strategy
    $wp_customize->add_section( 'growthpress_portal_admin', array(
        'title' => 'Portal Login Layout',
        'panel' => 'gp_design_architecture',
        'priority' => 85,
        'description' => 'Configure the authentication command node interface.'
    ) );
    $wp_customize->add_setting( 'gp_portal_headline', array( 'default' => 'Ecosystem Authentication', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_portal_headline', array( 'label' => 'Portal Headline', 'section' => 'growthpress_portal_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_portal_subheadline', array( 'default' => 'Synchronize with your project velocity and financial ledgers.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_portal_subheadline', array( 'label' => 'Portal Subheadline', 'section' => 'growthpress_portal_admin', 'type' => 'textarea' ) );

    // Single Staff Overrides
    $wp_customize->add_setting( 'gp_staff_bio_label', array( 'default' => 'Strategic Biography', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_staff_bio_label', array( 'label' => 'Staff Bio Heading', 'section' => 'growthpress_team_admin', 'type' => 'text' ) );
    $wp_customize->add_setting( 'gp_staff_contact_label', array( 'default' => 'Node Authentication', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_staff_contact_label', array( 'label' => 'Staff Contact Heading', 'section' => 'growthpress_team_admin', 'type' => 'text' ) );

    // Single Treatment Strategy
    $wp_customize->add_section( 'growthpress_treatment_admin', array(
        'title' => 'Treatment Template Admin',
        'panel' => 'gp_content_hubs',
        'priority' => 75,
        'description' => 'Configure the specialized clinical treatment protocol templates.'
    ) );
    $wp_customize->add_setting( 'gp_single_treatment_intel', array( 'default' => 'This protocol is registered in the v6.3 Clinical Intelligence Hub. Success Pattern: Documenting expected outcomes reduces patient anxiety and increases realization probability.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_single_treatment_intel', array( 'label' => 'Single Treatment Intel Pattern', 'section' => 'growthpress_treatment_admin', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'gp_treatment_pattern', array( 'default' => 'Firms that utilize the full Clinical Hub report a 34% reduction in patient administrative latency.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'gp_treatment_pattern', array( 'label' => 'Treatment Success Pattern Text', 'section' => 'growthpress_treatment_admin', 'type' => 'textarea' ) );

    // Global Visual Effects
    $wp_customize->add_section( 'gp_effects_admin', array(
        'title' => 'Global Visual Effects',
        'panel' => 'gp_design_architecture',
        'priority' => 50,
        'description' => 'Enhance the cinematic atmosphere of your Business OS. High-fidelity overlays increase the perceived technical maturity of the platform.'
    ) );
    $wp_customize->add_setting( 'gp_enable_grain', array( 'default' => true, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_grain', array( 'label' => 'Enable Grainy Atmosphere', 'section' => 'gp_effects_admin', 'type' => 'checkbox', 'description' => 'Adds a subtle analog film-grain for premium textured depth.' ) );
    $wp_customize->add_setting( 'gp_enable_magnetic_grid', array( 'default' => false, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_enable_magnetic_grid', array( 'label' => 'Enable Magnetic Grid Overlay', 'section' => 'gp_effects_admin', 'type' => 'checkbox', 'description' => 'Displays a technical alignment grid, conveying engineering precision.' ) );

    $wp_customize->add_setting( 'gp_neon_mode', array( 'default' => false, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'gp_neon_mode', array( 'label' => 'Enable Neon Mode (Dark Theme Only)', 'section' => 'gp_effects_admin', 'type' => 'checkbox', 'description' => 'Overwrites accent colors with vibrant neon-teal and pink glows for a high-tech "Cyber-Authority" look.' ) );

    // 10. Ecosystem Maintenance
    $wp_customize->add_section( 'growthpress_maintenance', array( 'title' => 'OS Maintenance & Sync', 'priority' => 100 ) );
    $wp_customize->add_setting( 'gp_regenerate_trigger', array( 'default' => '' ) );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'gp_regenerate_trigger', array(
        'label' => 'Sync Ecosystem', 'section' => 'growthpress_maintenance', 'type' => 'button',
        'input_attrs' => array( 'value' => 'Apply Updates & Sync All Pages', 'class' => 'button button-primary', 'onclick' => 'if(confirm("Regenerate and template all core pages now?")){ jQuery.post(ajaxurl, {action:"gp_regenerate_pages", gp_nonce:"'.wp_create_nonce("gp_admin_nonce").'"}); }' ),
    ) ) );
}
add_action( 'customize_register', 'growthpress_customize_register' );

function growthpress_scripts() {
	wp_enqueue_style( 'growthpress-inter-font', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Lexend:wght@400;700;800;900&display=swap' );
	wp_enqueue_style( 'growthpress-style', get_stylesheet_uri() );
    wp_enqueue_style( 'growthpress-accents', get_template_directory_uri() . '/niche-accents.css' );
    wp_enqueue_style( 'growthpress-mobile-cta', get_template_directory_uri() . '/mobile-cta.css' );

    $primary = get_theme_mod( 'growthpress_primary_color', '#4F46E5' );
    $gradient = get_theme_mod( 'gp_elite_gradient', 'linear-gradient(135deg, #1E1B4B 0%, #4F46E5 100%)' );
    $radius = get_theme_mod( 'gp_global_radius', '32px' );
    $blur = get_theme_mod( 'gp_blur_intensity', '40px' );
    $speed = get_theme_mod( 'gp_animation_speed', '0.8s' );
    $glass_opacity = get_theme_mod( 'gp_glass_opacity', '0.88' );
    $dark_accent = get_theme_mod( 'gp_dark_accent_color', '#818CF8' );
    $dark_bg = get_theme_mod( 'gp_dark_bg_color', '#020617' );
    $dark_surface = get_theme_mod( 'gp_dark_surface_color', '#0F172A' );
    $grain_opacity = get_theme_mod( 'gp_enable_grain', true ) ? '0.04' : '0';
    $grid_display = get_theme_mod( 'gp_enable_magnetic_grid', false ) ? 'block' : 'none';
    $neon_mode = get_theme_mod( 'gp_neon_mode', false );
    $neon_css = $neon_mode ? "body.dark-theme { --primary: #00FFCC; --primary-alt: #00CCAA; --primary-glow: rgba(0, 255, 204, 0.4); --accent: #FF00FF; }" : "";
    wp_add_inline_style( 'growthpress-style', ":root { --primary: $primary; --elite-gradient: $gradient; --radius: $radius; --blur: $blur; --speed: $speed; --grain-opacity: $grain_opacity; --grid-display: $grid_display; --glass-opacity: $glass_opacity; } body.dark-theme { --accent: $dark_accent; --bg: $dark_bg; --surface: $dark_surface; } $neon_css" );

    if ( defined( 'GROWTHPRESS_CORE_URL' ) ) {
	    wp_enqueue_script( 'growthpress-frontend-js', GROWTHPRESS_CORE_URL . 'assets/js/frontend.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '4.4.1', true );
	    wp_localize_script( 'growthpress-frontend-js', 'gp_ajax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'authority_enabled' => get_theme_mod('gp_enable_authority_feed', true),
            'authority_interval' => get_theme_mod('gp_authority_interval', '30000')
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'growthpress_scripts' );

function growthpress_body_classes( $classes ) {
    $niche = get_option( 'growthpress_niche', 'business' );
    $design = get_theme_mod( 'gp_design_style', 'unisex' );
    $classes[] = 'gp-niche-' . $niche;
    $classes[] = 'gp-design-' . $design;
    return $classes;
}
add_filter( 'body_class', 'growthpress_body_classes' );

function growthpress_footer_popup() {
    if ( is_admin() || ! defined( 'GROWTHPRESS_CORE_URL' ) ) return;
    ?>
    <div id="gp-exit-popup" class="glass-card" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000; width:460px; text-align:center; padding: 50px;">
        <h2 class="text-gradient">Wait! Get Your Free AI Roadmap</h2>
        <p>Enter your details below to receive a custom 12-month business growth strategy powered by GPT-4.</p>
        <?php echo do_shortcode('[gp_lead_form]'); ?>
        <button onclick="jQuery('#gp-exit-popup').fadeOut()" style="margin-top:20px; border:none; background:none; cursor:pointer; color:#64748B; font-weight:700;">No thanks, I'll pass.</button>
    </div>
    <?php
}
add_action( 'wp_footer', 'growthpress_footer_popup' );
