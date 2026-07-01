<?php
/**
 * GrowthPress Builder Extensions
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Builder_Ext {

    /**
     * Strategic Builder Extensions
     *
     * Injects high-stakes UI components into the visual builder ecosystem.
     * Strategic Note: Urgency banners are calibrated to specific niche seasonalities.
     */
    public function __construct() {
        add_shortcode( 'gp_urgency_banner', array( $this, 'render_urgency_banner' ) );
    }

    public function render_urgency_banner() {
        $niche = get_option('growthpress_niche', 'business');
        $messages = array(
            'dental'    => '🚨 Emergency Dental Appointments Available Today!',
            'roofing'   => '⛈️ Storm Damage? Immediate Inspections Available.',
            'solar'     => '⏳ Federal Tax Credit Ending Soon. Lock in Your Savings.',
            'law'       => '⚖️ Critical Case? Talk to an Attorney in 15 Minutes.',
            'medical'   => '🩺 Urgent Care Triage: Skip the Waiting Room.'
        );
        $msg = $messages[$niche] ?? '⚡ Limited Slots Available for This Month. Book Now.';

        return '<div class="gp-urgency-banner" style="background:#2563EB; color:white; padding:10px; text-align:center; font-weight:bold; width:100%;">' . esc_html($msg) . '</div>';
    }
}

new GrowthPress_Builder_Ext();
