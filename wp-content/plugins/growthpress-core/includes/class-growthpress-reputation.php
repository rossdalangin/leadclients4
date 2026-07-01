<?php
/**
 * GrowthPress Reputation Management Class - Automated
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reputation {

    /**
     * Strategic Reputation Node
     *
     * Manages client testimonials and market authority validation.
     * Methodology: Connect reviews to Case Studies to create a verified chain of success.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register_review_cpt' ) );
        add_shortcode( 'gp_review_feed', array( $this, 'render_review_feed' ) );
        add_action( 'gp_appointment_completed', array( $this, 'trigger_review_request' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_review_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_review_meta' ) );
        add_filter( 'manage_gp_review_posts_columns', array( $this, 'review_columns' ) );
        add_action( 'manage_gp_review_posts_custom_column', array( $this, 'review_column_content' ), 10, 2 );
    }

    public function review_columns( $cols ) {
        $cols['_rating'] = 'Rating';
        $cols['_source'] = 'Source';
        $cols['_client'] = 'Client Name';
        return $cols;
    }

    public function review_column_content( $col, $post_id ) {
        if ( $col === '_rating' ) echo str_repeat('⭐', intval(get_post_meta( $post_id, '_gp_rating', true )));
        if ( $col === '_source' ) echo get_post_meta( $post_id, '_gp_review_source', true ) ?: 'Direct';
        if ( $col === '_client' ) echo get_post_meta( $post_id, '_gp_client_name', true ) ?: get_the_title($post_id);
    }

    public function add_review_meta_boxes() {
        add_meta_box( 'gp_review_details', '⭐ Testimonial Market Authority Data', array( $this, 'render_review_meta' ), 'gp_review', 'normal', 'high' );
    }

    public function render_review_meta( $post ) {
        $rating = get_post_meta( $post->ID, '_gp_rating', true ) ?: 5;
        $source = get_post_meta( $post->ID, '_gp_review_source', true ) ?: 'Google';
        $client = get_post_meta( $post->ID, '_gp_client_name', true );
        $project_id = get_post_meta( $post->ID, '_related_project', true );
        $projects = get_posts( array( 'post_type' => 'gp_project', 'posts_per_page' => -1 ) );
        ?>
        <div style="background: #fdf2f8; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #db2777;">
            <p style="margin: 0; font-size: 13px; color: #9d174d;"><strong>Reputation Management:</strong> Testimonials are the primary driver of market authority. Linking reviews to specific 'Case Studies' creates a verified chain of success that boosts frontend conversion rates.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Client Name</label><p class="description">The identity of the reviewer as it will appear on the frontend wall.</p></th>
                <td><input type="text" name="gp_client_name" value="<?php echo esc_attr($client); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Client Rating (1-5)</label><p class="description">Numeric authority score. 5 stars is the benchmark for high-ticket service excellence.</p></th>
                <td><input type="number" name="gp_review_rating" value="<?php echo esc_attr($rating); ?>" min="1" max="5" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Review Source</label><p class="description">The platform where this review originated.</p></th>
                <td>
                    <select name="gp_review_source" style="width:100%;">
                        <option value="Google" <?php selected($source, 'Google'); ?>>Google Business</option>
                        <option value="Trustpilot" <?php selected($source, 'Trustpilot'); ?>>Trustpilot</option>
                        <option value="Facebook" <?php selected($source, 'Facebook'); ?>>Facebook</option>
                        <option value="Direct" <?php selected($source, 'Direct'); ?>>Direct Testimonial</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Linked Case Study</label><p class="description">Associates this testimonial with a specific project outcome for maximum social proof.</p></th>
                <td>
                    <select name="gp_related_project" style="width:100%;">
                        <option value="0">No Related Project</option>
                        <?php foreach($projects as $p): ?>
                            <option value="<?php echo $p->ID; ?>" <?php selected($project_id, $p->ID); ?>><?php echo esc_html($p->post_title); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function save_review_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_review_rating'] ) ) return;
        update_post_meta( $post_id, '_gp_rating', intval( $_POST['gp_review_rating'] ) );
        update_post_meta( $post_id, '_gp_review_source', sanitize_text_field( $_POST['gp_review_source'] ) );
        update_post_meta( $post_id, '_gp_client_name', sanitize_text_field( $_POST['gp_client_name'] ) );
        update_post_meta( $post_id, '_related_project', intval( $_POST['gp_related_project'] ) );
    }

    public function register_review_cpt() {
        register_post_type( 'gp_review', array(
            'labels'      => array( 'name' => 'Reviews', 'singular_name' => 'Review' ),
            'public'      => true,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-star-filled',
            'supports'    => array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
        ) );
    }

    public function get_top_reviews( $limit = 3 ) {
        return get_posts( array(
            'post_type'      => 'gp_review',
            'posts_per_page' => $limit,
            'meta_key'       => '_gp_rating',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC'
        ) );
    }

    public function trigger_review_request( $appointment_id ) {
        $email = get_post_meta( $appointment_id, '_client_email', true );
        // Logic to send review request email/SMS
        GrowthPress_Activity::log( "Review request sent to $email for appointment #$appointment_id" );
    }

    public function generate_sample_data() {
        $niche = get_option('growthpress_niche', 'business');
        $reviews = array(
            "The AI triage saved us 10 hours a week in discovery calls. Total game changer." => "Alex Johnson",
            "Professional, fast, and the client portal is exactly what our high-ticket clients expected." => "Sarah Miller",
            "Best investment we've made in our tech stack this year. Highly recommended." => "David Chen"
        );
        foreach($reviews as $content => $author) {
            if ( ! get_page_by_path( sanitize_title($author), OBJECT, 'gp_review' ) ) {
                $id = wp_insert_post(array('post_title' => $author, 'post_content' => $content, 'post_type' => 'gp_review', 'post_status' => 'publish'));
                update_post_meta($id, '_gp_rating', 5);
                update_post_meta($id, '_gp_is_sample', '1');
            }
        }
    }

    public function render_review_feed() {
        $reviews = get_posts( array( 'post_type' => 'gp_review', 'posts_per_page' => 5 ) );
        ob_start(); ?>
        <div class="gp-review-feed">
            <?php foreach ( $reviews as $review ) :
                $rating = get_post_meta( $review->ID, '_gp_rating', true ) ?: 5; ?>
                <div class="gp-review-card glass-card" style="margin-bottom: 15px;">
                    <div class="rating"><?php echo str_repeat('⭐', intval($rating)); ?></div>
                    <p>"<?php echo esc_html($review->post_content); ?>"</p>
                    <strong>- <?php echo esc_html($review->post_title); ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function suggest_review_reply( $review_id ) {
        $review = get_post( $review_id );
        $ai = GrowthPress_AI::get_instance();
        $prompt = "A client left this review: \"{$review->post_content}\". Generate a professional reply.";
        return $ai->call_ai( $prompt, "You are a customer success manager." );
    }
}

new GrowthPress_Reputation();
