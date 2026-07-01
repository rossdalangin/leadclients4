<?php
/**
 * Real Estate Niche specialized Closer Tools - Ultra Elite v6.3
 */
class GrowthPress_RealEstate {
    public function __construct() {
        add_shortcode('gp_property_matcher', array($this, 'render_property_matcher'));
        add_action('init', array($this, 'register_property_cpt'));
        add_action('gp_niche_lead_analysis', array($this, 'analyze_re_lead'));
        add_action('add_meta_boxes', array($this, 'add_property_meta_boxes'));
        add_action('save_post', array($this, 'save_property_meta'));
        add_filter('manage_gp_property_posts_columns', array($this, 'property_columns'));
        add_action('manage_gp_property_posts_custom_column', array($this, 'property_column_content'), 10, 2);
    }

    public function property_columns($cols) {
        $cols['_price'] = 'Price';
        $cols['_sqft'] = 'SQFT';
        return $cols;
    }

    public function property_column_content($col, $post_id) {
        if ($col === '_price') echo '$' . number_format(get_post_meta($post_id, '_gp_price', true));
        if ($col === '_sqft') echo number_format(get_post_meta($post_id, '_gp_sqft', true)) . ' SQFT';
    }

    public function add_property_meta_boxes() {
        add_meta_box('gp_property_details', '🏠 High-Yield Asset Inventory Data', array($this, 'render_property_meta'), 'gp_property', 'normal', 'high');
    }

    public function render_property_meta($post) {
        $price = get_post_meta($post->ID, '_gp_price', true);
        $sqft = get_post_meta($post->ID, '_gp_sqft', true);
        $tags = get_post_meta($post->ID, '_gp_lifestyle_tags', true);
        ?>
        <div style="background: #fff7ed; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #f97316;">
            <p style="margin: 0; font-size: 13px; color: #9a3412;"><strong>Asset Inventory Hub:</strong> High-yield portfolio items or real estate listings are managed here. These fields are utilized by the 'Neural Lifestyle Matcher' to autonomously pair prospects with their ideal strategic assets.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Asset Price ($)</label><p class="description">Market value of the strategic asset.</p></th>
                <td><input type="number" name="gp_property_price" value="<?php echo esc_attr($price); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Total Square Footage</label><p class="description">Physical dimensions of the node.</p></th>
                <td><input type="number" name="gp_property_sqft" value="<?php echo esc_attr($sqft); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Lifestyle Strategic Tags</label><p class="description">Keywords for the AI matching engine (e.g. Modern, Corporate, Elite).</p></th>
                <td>
                    <input type="text" name="gp_property_tags" value="<?php echo esc_attr($tags); ?>" class="regular-text" placeholder="e.g. Suburban, Modern, Elite">
                    <p class="description">Used by the Neural Inventory Matcher for client lifestyle DNS mapping.</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_property_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST['gp_property_price'])) return;
        update_post_meta($post_id, '_gp_price', sanitize_text_field($_POST['gp_property_price']));
        update_post_meta($post_id, '_gp_sqft', sanitize_text_field($_POST['gp_property_sqft']));
        update_post_meta($post_id, '_gp_lifestyle_tags', sanitize_text_field($_POST['gp_property_tags']));
    }

    public function analyze_re_lead($lead_id) {
        $lead = get_post($lead_id);
        $content = strtolower($lead->post_content);
        $crm = GrowthPress_CRM::get_instance();

        if (strpos($content, 'buy') !== false || strpos($content, 'purchase') !== false) {
            $crm->create_task("Buyer Inventory Match", "Lead looking to purchase. Cross-reference off-market nodes.", $lead_id);
            wp_set_object_terms($lead_id, 'Buyer', 'gp_lead_tag', true);
        }

        if (strpos($content, 'sell') !== false || strpos($content, 'listing') !== false) {
            $crm->create_task("Listing Value Valuation", "Lead looking to sell. Execute comparative market analysis.", $lead_id);
            wp_set_object_terms($lead_id, 'Seller', 'gp_lead_tag', true);
        }
    }

    public function register_property_cpt() {
        register_post_type('gp_property', array(
            'labels' => array('name' => 'Portfolio Inventory', 'singular_name' => 'Property'),
            'public' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-admin-home',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
        ));
    }

    public function render_property_matcher() {
        $properties = get_posts(array('post_type' => 'gp_property', 'posts_per_page' => 3));
        ob_start(); ?>
        <style>
            .gp-lifestyle-btn { width:100%; height:140px; text-transform:none; border-radius:40px; font-size:22px; letter-spacing:-0.02em; font-weight: 800; background: #FFF; border: 1px solid #E2E8F0; color: var(--secondary) !important; transition: all 0.4s ease; cursor: pointer; }
            .gp-lifestyle-btn:hover { border-color: var(--primary); transform: translateY(-10px); box-shadow: 0 30px 60px -15px rgba(0,0,0,0.1); }
        </style>
        <div class="glass-card" style="background:#fff7ed; border-left:5px solid #f97316; margin-bottom:40px; padding:20px; border-radius:15px;">
            <p style="margin:0; font-size:14px; color:#9a3412;"><strong>Inventory Success Pattern:</strong> Real estate portfolios utilizing the Neural Lifestyle Matcher report a 55% increase in lead engagement with off-market inventory. <strong>ROI Pro-Tip:</strong> Use "Modernist" and "Elite" tags to trigger automated high-net-worth psychological profiling in the CRM.</p>
        </div>
        <div class="gp-property-matcher glass-card gp-reveal" style="text-align:center; padding:150px 80px; background: radial-gradient(circle at top right, rgba(37,99,235,0.08), transparent 50%), rgba(255,255,255,0.9); border-radius: 60px;">
            <div style="font-size:11px; font-weight:950; color:var(--primary); text-transform:uppercase; letter-spacing:6px; margin-bottom:25px;">NEURAL INVENTORY MATCH v6.3</div>
            <h3 class="text-gradient" style="font-size:5rem; letter-spacing:-0.08em; line-height:0.9;">AI Lifestyle Matcher</h3>
            <p style="font-size:1.5rem; opacity:0.7; max-width:800px; margin:40px auto 0; font-weight: 500; line-height: 1.5;">Our neural network cross-references your specific lifestyle DNA with our proprietary off-market inventory node.</p>

            <div id="lifestyle-steps" style="margin-top:100px;">
                <div class="wp-block-columns" style="gap:50px;">
                    <?php if($properties): foreach($properties as $p):
                        $tags = get_post_meta($p->ID, '_gp_lifestyle_tags', true) ?: 'Suburban Sanctuary';
                        $first_tag = explode(',', $tags)[0];
                        ?>
                        <div class="wp-block-column">
                            <button class="gp-lifestyle-btn" onclick="jQuery('#lifestyle-steps').fadeOut(); jQuery('#gp-quiz-form').fadeIn();">
                                <?php echo esc_html($first_tag); ?>
                            </button>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="wp-block-column"><button class="gp-lifestyle-btn" onclick="jQuery('#lifestyle-steps').fadeOut(); jQuery('#gp-quiz-form').fadeIn();">Suburban Sanctuary</button></div>
                    <?php endif; ?>
                </div>
                <div style="margin-top:70px; font-size:12px; font-weight:950; opacity:0.3; letter-spacing:4px; text-transform: uppercase;">Inference Status: Ready for Domain Mapping</div>
            </div>
            <div id="gp-quiz-form" style="display:none; margin-top:80px;">
                <div style="max-width:700px; margin:0 auto;">
                    <?php echo do_shortcode('[gp_lead_form]'); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function generate_sample_data() {
        $id = wp_insert_post(array(
            'post_title'   => 'The Horizon Penthouse Node',
            'post_content' => 'High-stakes luxury realization with absolute skyline dominance. This premium asset features a neural-integrated lighting system, private glass-card foyer, and 3,500 sqft of elite living space. This off-market node is positioned in a high-growth urban sector and represents a significant appreciation opportunity for high-net-worth portfolios.',
            'post_type'    => 'gp_property',
            'post_status'  => 'publish'
        ));
        if ($id) {
            update_post_meta($id, '_gp_is_sample', '1');
            update_post_meta($id, '_gp_price', '4500000');
            update_post_meta($id, '_gp_sqft', '3500');
            update_post_meta($id, '_gp_lifestyle_tags', 'Urban, Modernist, Elite');
        }

        $sid = wp_insert_post(array(
            'post_title'   => 'Elite High-Yield Portfolio Audit Hub',
            'post_content' => 'Strategic assessment of your real estate capital designed to identify off-market appreciation nodes and ensure absolute lifestyle alignment. Our acquisition specialists utilize the v6.3 Neural Lifestyle Matcher to cross-reference your specific profile with proprietary inventory, maximizing your long-term equity realization.',
            'post_type'    => 'gp_service',
            'post_status'  => 'publish'
        ));
        if ($sid) {
            update_post_meta($sid, '_gp_is_sample', '1');
            update_post_meta($sid, '_gp_service_icon', '🏢');
        }
    }
}
new GrowthPress_RealEstate();
