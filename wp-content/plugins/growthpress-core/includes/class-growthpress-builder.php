<?php
/**
 * GrowthPress Page Builder Integration Class - Bricks Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Builder {

    /**
     * Strategic Builder Node
     *
     * Orchestrates the integration of high-stakes UI components into visual
     * page builders (Elementor, Bricks, and Gutenberg).
     * Pro-Tip: Use 'Elite Hero' block patterns to anchor your value proposition
     * above the fold for maximum conversion trajectory.
     */
    public function __construct() {
        add_action( 'elementor/elements/categories_registered', array( $this, 'register_elementor_category' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_elementor_widgets' ) );
        add_action( 'init', array( $this, 'register_block_patterns' ) );
        add_filter( 'bricks/builder/i18n', array( $this, 'register_bricks_category' ) );
    }

    public function register_elementor_category( $elements_manager ) {
        $elements_manager->add_category( 'growthpress', array( 'title' => 'GrowthPress OS', 'icon' => 'fa fa-chart-line' ) );
    }

    public function register_elementor_widgets( $widgets_manager ) {
        if ( ! class_exists( '\Elementor\Widget_Base' ) ) return;
        require_once __DIR__ . '/widgets/elementor-lead-form.php';
        $widgets_manager->register( new \GrowthPress_Lead_Form_Widget() );
    }

    public function register_bricks_category( $i18n ) {
        $i18n['growthpress'] = 'GrowthPress OS Elements';
        return $i18n;
    }

    public function register_block_patterns() {
        if ( ! function_exists( 'register_block_pattern' ) ) return;
        register_block_pattern_category( 'growthpress', array( 'label' => 'GrowthPress' ) );

        // Elite Hero Pattern
        register_block_pattern( 'growthpress/elite-hero', array(
            'title' => 'Elite Hero with Glass Form',
            'categories' => array( 'growthpress' ),
            'content' => '
<!-- wp:group {"tagName":"section","className":"gp-hero grainy-bg","layout":{"type":"constrained"}} -->
<section class="wp-block-group gp-hero grainy-bg">
    <!-- wp:columns {"verticalAlignment":"center"} -->
    <div class="wp-block-columns are-vertically-aligned-center">
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column are-vertically-aligned-center">
            <!-- wp:heading {"level":1} --><h1 class="text-gradient">Scale Your Business with AI Intelligence</h1><!-- /wp:heading -->
            <!-- wp:paragraph --><p>Stop losing leads to outdated systems. GrowthPress is the unified operating system for high-ticket service firms.</p><!-- /wp:paragraph -->
            <!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"className":"gp-btn"} --><a class="wp-block-button__link gp-btn">Book Discovery Session</a><!-- /wp:button --></div><!-- /wp:buttons -->
        </div>
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column are-vertically-aligned-center">
            <!-- wp:group {"className":"glass-card"} --><div class="wp-block-group glass-card">
                <!-- wp:heading {"level":3} --><h3>Priority Access</h3><!-- /wp:heading -->
                <!-- wp:shortcode -->[gp_lead_form]<!-- /wp:shortcode -->
            </div><!-- /wp:group -->
        </div>
    </div>
    <!-- /wp:columns -->
</section>
<!-- /wp:group -->'
        ) );

        // Trust Bar Pattern
        register_block_pattern( 'growthpress/trust-bar', array(
            'title' => 'Social Proof Trust Bar',
            'categories' => array( 'growthpress' ),
            'content' => '
<!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:40px;padding-bottom:40px">
    <!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase","letterSpacing":"0.1em","fontSize":"12px"}}} -->
    <p class="has-text-align-center" style="font-size:12px;letter-spacing:0.1em;text-transform:uppercase">Trusted by 500+ Industry Leaders Globally</p>
    <!-- /wp:paragraph -->
    <!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"20px"}}}} -->
    <div class="wp-block-columns alignwide" style="margin-top:20px">
        <!-- wp:column --><div class="wp-block-column" style="opacity:0.4;text-align:center"><strong>FORBES</strong></div><!-- /wp:column -->
        <!-- wp:column --><div class="wp-block-column" style="opacity:0.4;text-align:center"><strong>TECHCRUNCH</strong></div><!-- /wp:column -->
        <!-- wp:column --><div class="wp-block-column" style="opacity:0.4;text-align:center"><strong>ENTREPRENEUR</strong></div><!-- /wp:column -->
        <!-- wp:column --><div class="wp-block-column" style="opacity:0.4;text-align:center"><strong>INC. 5000</strong></div><!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->'
        ) );

        // Feature Grid Pattern
        register_block_pattern( 'growthpress/feature-grid', array(
            'title' => 'Growth Ecosystem Feature Grid',
            'categories' => array( 'growthpress' ),
            'content' => '
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
    <!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">Unified Business Intelligence</h2><!-- /wp:heading -->
    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"50px"}}}} -->
    <div class="wp-block-columns" style="margin-top:50px">
        <!-- wp:column --><div class="wp-block-column glass-card"><h4>AI Lead Triage</h4><p>Instantly qualify and route leads based on sentiment and intent.</p></div><!-- /wp:column -->
        <!-- wp:column --><div class="wp-block-column glass-card"><h4>Omnichannel CRM</h4><p>Centralize communication across SMS, Email, and WhatsApp.</p></div><!-- /wp:column -->
        <!-- wp:column --><div class="wp-block-column glass-card"><h4>Booking Engine</h4><p>Eliminate back-and-forth with staff-aware appointment scheduling.</p></div><!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->'
        ) );
    }
}

new GrowthPress_Builder();
