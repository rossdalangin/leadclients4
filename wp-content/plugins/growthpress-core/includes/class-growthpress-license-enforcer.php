<?php
/**
 * GrowthPress License Enforcer - Real-time Capability Validation
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_License_Enforcer {

    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Check if the current instance has a specific capability based on its license.
     */
    public function has_capability( $capability ) {
        // Dev bypass
        $key = get_option('growthpress_license_key');
        if ( $key && strpos($key, 'GP-DEV-') === 0 ) return true;

        if ( ! class_exists('GrowthPress_License') ) {
            // Fallback for when Hub is not active but we have an active status
            $status = get_option('growthpress_license_status');
            if ( $status !== 'active' ) return false;

            // Minimal baseline capabilities if Hub is missing but license was previously active
            $baseline = array('crm', 'booking');
            return in_array($capability, $baseline);
        }

        $hub = GrowthPress_License::get_instance();
        $data = $hub->get_license_data($key);

        if ( ! $data || $data['status'] !== 'active' ) return false;

        return in_array($capability, (array)$data['capabilities']);
    }

    /**
     * Render an "Upgrade Required" notice if capability is missing.
     */
    public function render_upgrade_notice( $feature_name, $required_tier = 'Elite' ) {
        ?>
        <div class="glass-card" style="text-align:center; padding:100px 50px; background:#FFF1F2; border:2px dashed #FDA4AF; border-radius:40px;">
            <div style="font-size:60px; margin-bottom:30px;">🔐</div>
            <h2 style="margin:0; color:#9F1239;"><?php echo esc_html($feature_name); ?>: Strategic Upgrade Required</h2>
            <p style="font-size:18px; color:#BE123C; max-width:600px; margin:30px auto; line-height:1.6;">
                The <?php echo esc_html($feature_name); ?> module requires a <strong><?php echo esc_html($required_tier); ?></strong> license node to be authenticated. Your current node does not have the provisioned capabilities for this high-stakes operational node.
            </p>
            <div style="display:flex; justify-content:center; gap:20px; margin-top:50px;">
                <a href="<?php echo admin_url('admin.php?page=growthpress-settings#tab-license'); ?>" class="gp-btn" style="background:#E11D48; color:white !important;">Activate Elite Node</a>
                <a href="#" class="gp-btn" style="background:transparent; border:1px solid #FDA4AF; color:#9F1239 !important;">Contact Authority Hub</a>
            </div>
        </div>
        <?php
    }
}

function gp_has_cap( $cap ) {
    return GrowthPress_License_Enforcer::get_instance()->has_capability( $cap );
}
