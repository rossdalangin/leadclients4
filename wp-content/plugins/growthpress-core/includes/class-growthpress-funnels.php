<?php
/**
 * GrowthPress Funnel Management Class - A/B Testing Enhanced
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Funnels {

    public function __construct() {
        add_action( 'init', array( $this, 'register_funnel_cpt' ) );
        add_shortcode( 'gp_funnel_step', array( $this, 'render_funnel_step' ) );
        add_shortcode( 'gp_split_test', array( $this, 'render_split_test' ) );
        add_action( 'wp_ajax_gp_track_funnel', array( $this, 'handle_tracking' ) );
        add_action( 'wp_ajax_nopriv_gp_track_funnel', array( $this, 'handle_tracking' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_funnel_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_funnel_meta' ) );
        add_filter( 'manage_gp_funnel_posts_columns', array( $this, 'funnel_columns' ) );
        add_action( 'manage_gp_funnel_posts_custom_column', array( $this, 'funnel_column_content' ), 10, 2 );
    }

    public function funnel_columns( $cols ) {
        $cols['_hits'] = 'Total Traffic';
        $cols['_conv'] = 'Conv. Rate';
        $cols['_roi'] = 'Funnel ROI';
        return $cols;
    }

    public function funnel_column_content( $col, $post_id ) {
        $a = (int)get_post_meta($post_id, '_hits_A', true);
        $b = (int)get_post_meta($post_id, '_hits_B', true);
        $ca = (int)get_post_meta($post_id, '_conv_A', true);
        $cb = (int)get_post_meta($post_id, '_conv_B', true);

        if ( $col === '_hits' ) echo ($a + $b) . ' Hits';
        if ( $col === '_conv' ) {
            $total_hits = ($a + $b) ?: 1;
            $total_conv = ($ca + $cb);
            echo round(($total_conv / $total_hits) * 100, 2) . '%';
        }
        if ( $col === '_roi' ) {
            $spend = (float)get_post_meta($post_id, '_funnel_ad_spend', true);
            $val_est = (float)get_post_meta($post_id, '_lead_value_est', true);
            $total_conv = ($ca + $cb);
            $pipeline_gen = $total_conv * $val_est;
            $roi = $spend > 0 ? (($pipeline_gen - $spend) / $spend) * 100 : 0;
            echo '<span style="color:'.($roi > 0 ? '#10b981' : '#ef4444').'; font-weight:bold;">'.round($roi).'%</span>';
        }
    }

    public function add_funnel_meta_boxes() {
        add_meta_box( 'gp_funnel_details', '🎯 Neural Funnel A/B Performance Metrics', array( $this, 'render_funnel_meta' ), 'gp_funnel', 'normal', 'high' );
    }

    public function render_funnel_meta( $post ) {
        $hitsA = get_post_meta( $post->ID, '_hits_A', true ) ?: 0;
        $hitsB = get_post_meta( $post->ID, '_hits_B', true ) ?: 0;
        $convA = get_post_meta( $post->ID, '_conv_A', true ) ?: 0;
        $convB = get_post_meta( $post->ID, '_conv_B', true ) ?: 0;
        $urlA = get_post_meta( $post->ID, '_url_A', true );
        $urlB = get_post_meta( $post->ID, '_url_B', true );
        $goal = get_post_meta( $post->ID, '_conversion_goal', true ) ?: 'Lead Capture';
        $strategy = get_post_meta( $post->ID, '_winning_strategy_note', true );
        $cost = get_post_meta( $post->ID, '_funnel_ad_spend', true ) ?: 0;
        $val = get_post_meta( $post->ID, '_lead_value_est', true ) ?: 500;

        $rateA = $hitsA > 0 ? round(($convA / $hitsA) * 100, 2) : 0;
        $rateB = $hitsB > 0 ? round(($convB / $hitsB) * 100, 2) : 0;
        $winner = ($rateA > $rateB) ? 'Variation A' : (($rateB > $rateA) ? 'Variation B' : 'Inconclusive');
        ?>
        <div style="background: #fdf2f8; padding: 20px; border-radius: 12px; margin-bottom: 25px; border-left: 4px solid #db2777;">
            <h4 style="margin: 0 0 10px 0; color: #9d174d;">🎯 A/B Intelligence Summary</h4>
            <p style="margin: 0; font-size: 14px; color: #9d174d;">Current Winning Node: <strong><?php echo $winner; ?></strong></p>
            <p style="margin: 10px 0 0 0; font-size: 12px; opacity: 0.8;">The system is tracking traffic and lead conversions for two variations. Variation A (Control) vs Variation B (Challenger). Direct the winning URL to your primary ad campaigns.</p>
        </div>
        <table class="form-table">
            <tr>
                <th colspan="2" style="background:#f0f0f0; padding:15px; border-radius:8px 8px 0 0;">Variation A (The Control)</th>
            </tr>
            <tr>
                <th><label>Target URL A</label><p class="description">The landing page for the control variation.</p></th>
                <td><input type="url" name="gp_url_a" value="<?php echo esc_url($urlA); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Traffic Hits A</label><p class="description">Total sessions recorded for Variation A.</p></th>
                <td><input type="number" name="gp_hits_a" value="<?php echo esc_attr($hitsA); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Conversions A</label><p class="description">Total leads captured through Variation A.</p></th>
                <td><input type="number" name="gp_conv_a" value="<?php echo esc_attr($convA); ?>" class="regular-text"> <span style="margin-left:10px; font-weight:700; color:#10b981;"><?php echo $rateA; ?>% CV</span></td>
            </tr>
            <tr>
                <th colspan="2" style="background:#f0f0f0; padding:15px;">Variation B (The Challenger)</th>
            </tr>
            <tr>
                <th><label>Target URL B</label><p class="description">The landing page for the test variation.</p></th>
                <td><input type="url" name="gp_url_b" value="<?php echo esc_url($urlB); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Traffic Hits B</label><p class="description">Total sessions recorded for Variation B.</p></th>
                <td><input type="number" name="gp_hits_b" value="<?php echo esc_attr($hitsB); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Conversions B</label><p class="description">Total leads captured through Variation B.</p></th>
                <td><input type="number" name="gp_conv_b" value="<?php echo esc_attr($convB); ?>" class="regular-text"> <span style="margin-left:10px; font-weight:700; color:#10b981;"><?php echo $rateB; ?>% CV</span></td>
            </tr>
            <tr class="section-header"><th colspan="2"><h3>Funnel Calibration</h3></th></tr>
            <tr>
                <th><label>Primary Conversion Goal</label><p class="description">The specific user action being tracked (e.g. Booking, Purchase, Quiz Completion).</p></th>
                <td><input type="text" name="gp_conv_goal" value="<?php echo esc_attr($goal); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Ad Spend / Acquisition Cost ($)</label><p class="description">Total investment in traffic for this funnel lifecycle.</p></th>
                <td><input type="number" name="gp_funnel_cost" value="<?php echo esc_attr($cost); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Projected Lead Value ($)</label><p class="description">Average strategic value of a single lead captured here.</p></th>
                <td><input type="number" name="gp_lead_val" value="<?php echo esc_attr($val); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Winning Strategy Note</label><p class="description">Document why the winning variation outperformed the control for future funnel optimizations.</p></th>
                <td><textarea name="gp_winning_strategy" style="width:100%; height:100px;"><?php echo esc_textarea($strategy); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    public function save_funnel_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_hits_a'] ) ) return;
        update_post_meta( $post_id, '_hits_A', intval( $_POST['gp_hits_a'] ) );
        update_post_meta( $post_id, '_hits_B', intval( $_POST['gp_hits_b'] ) );
        update_post_meta( $post_id, '_conv_A', intval( $_POST['gp_conv_a'] ) );
        update_post_meta( $post_id, '_conv_B', intval( $_POST['gp_conv_b'] ) );
        update_post_meta( $post_id, '_url_A', esc_url_raw( $_POST['gp_url_a'] ) );
        update_post_meta( $post_id, '_url_B', esc_url_raw( $_POST['gp_url_b'] ) );
        update_post_meta( $post_id, '_funnel_ad_spend', floatval( $_POST['gp_funnel_cost'] ) );
        update_post_meta( $post_id, '_lead_value_est', floatval( $_POST['gp_lead_val'] ) );
        update_post_meta( $post_id, '_conversion_goal', sanitize_text_field( $_POST['gp_conv_goal'] ) );
        update_post_meta( $post_id, '_winning_strategy_note', sanitize_textarea_field( $_POST['gp_winning_strategy'] ) );
    }

    public function register_funnel_cpt() {
        register_post_type( 'gp_funnel', array(
            'labels'      => array( 'name' => 'Funnels', 'singular_name' => 'Funnel' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-filter',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function render_split_test( $atts ) {
        $a = shortcode_atts( array( 'id' => 0 ), $atts );
        if ( ! $a['id'] ) return '';

        $hitsA = (int)get_post_meta($a['id'], '_hits_A', true);
        $hitsB = (int)get_post_meta($a['id'], '_hits_B', true);
        $convA = (int)get_post_meta($a['id'], '_conv_A', true);
        $convB = (int)get_post_meta($a['id'], '_conv_B', true);

        $rateA = $hitsA > 0 ? ($convA / $hitsA) : 0;
        $rateB = $hitsB > 0 ? ($convB / $hitsB) : 0;

        // Autonomous Winner Override Logic
        $variation = ( $rateB > $rateA && $hitsB > 20 ) ? 'B' : 'A';

        // Log the hit autonomously
        $this->track_variation_hit($a['id'], $variation);

        $admin_bar = '';
        if ( current_user_can('manage_options') ) {
            $admin_bar = '<div style="background:var(--secondary); color:white; padding:10px 20px; font-size:10px; font-weight:950; text-transform:uppercase; letter-spacing:2px; display:flex; justify-content:space-between; align-items:center;">
                <span>🎯 A/B Node: Variation '.$variation.' Active</span>
                <span>CVR: '.round(($variation === 'A' ? $rateA : $rateB)*100, 2).'%</span>
            </div>';
        }

        $content = get_post_meta($a['id'], "_content_$variation", true);
        return $admin_bar . do_shortcode($content ?: "<!-- Funnel node $variation active -->");
    }

    public function render_funnel_step( $atts ) {
        $a = shortcode_atts( array( 'id' => 0, 'variation' => 'A' ), $atts );
        if ( ! $a['id'] ) return '';

        ob_start(); ?>
        <script>
        jQuery(document).ready(function($) {
            $.post(gp_ajax.ajaxurl, {
                action: 'gp_track_funnel',
                funnel_id: <?php echo intval($a['id']); ?>,
                variation: '<?php echo esc_js($a['variation']); ?>'
            });
        });
        </script>
        <?php
        return ob_get_clean();
    }

    public function handle_tracking() {
        $id = intval($_POST['funnel_id']);
        $var = sanitize_text_field($_POST['variation']);
        $this->track_variation_hit($id, $var);
        wp_send_json_success();
    }

    public function track_variation_hit( $funnel_id, $variation = 'A' ) {
        $hits = get_post_meta( $funnel_id, "_hits_$variation", true ) ?: 0;
        update_post_meta( $funnel_id, "_hits_$variation", ++$hits );
    }

    public function get_performance( $funnel_id ) {
        return array(
            'A' => get_post_meta( $funnel_id, '_hits_A', true ) ?: 0,
            'B' => get_post_meta( $funnel_id, '_hits_B', true ) ?: 0,
        );
    }
}

new GrowthPress_Funnels();
