<?php
/**
 * GrowthPress Reviews Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Reviews_Manager {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_reviews_menu' ) );
        add_action( 'wp_ajax_gp_generate_review_reply', array( $this, 'handle_reply_generation' ) );
    }

    public function add_reviews_menu() {
        add_submenu_page(
            'growthpress-dashboard',
            'Reputation & Reviews',
            'Reviews',
            'manage_options',
            'growthpress-reviews',
            array( $this, 'render_reviews_page' )
        );
    }

    public function handle_reply_generation() {
        check_ajax_referer( 'gp_admin_nonce', 'gp_nonce' );
        $review_id = intval($_POST['review_id']);
        $reputation = new GrowthPress_Reputation();
        $reply = $reputation->suggest_review_reply($review_id);
        wp_send_json_success($reply);
    }

    public function render_reviews_page() {
        $reviews = get_posts(array('post_type' => 'gp_review', 'posts_per_page' => 10));
        ?>
        <div class="wrap growthpress-reviews gp-reveal">
            <div class="glass-card" style="background:#f0fdf4; border-left:5px solid #10b981; margin-bottom:30px;">
                <h4 style="margin:0 0 10px 0; color:#166534;">⭐️ Strategic Context: Reputation Authority</h4>
                <p style="margin:0; font-size:14px; color:#166534; line-height:1.5;">Public reviews are a primary "Market Authority" signal. The AI suggests replies that reinforce your sector expertise and professional authority. <strong>Success Pattern:</strong> Responding to all reviews, positive or negative, within 24 hours is correlated with a significant boost in local SEO dominance and trust-building for new inquiries.</p>
            </div>

            <h1>Reputation Management</h1>
            <p class="description">Monitor your business reviews and use AI to generate professional replies. <strong>Pro-Tip:</strong> Responding to all reviews, positive or negative, within 24 hours boosts local SEO dominance.</p>

            <div class="reviews-list" style="margin-top:20px; display:grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap:25px;">
                <?php foreach($reviews as $review): ?>
                    <div class="glass-card" style="margin-bottom:15px;">
                        <h3><?php echo esc_html($review->post_title); ?></h3>
                        <p><?php echo esc_html($review->post_content); ?></p>
                        <button class="button" onclick="generateReply(<?php echo $review->ID; ?>)">Generate AI Reply</button>
                        <div id="reply-<?php echo $review->ID; ?>" style="margin-top:10px; font-style:italic;"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script>
        function generateReply(id) {
            var $ = jQuery;
            $('#reply-' + id).text('AI is writing...');
            $.post(ajaxurl, { action: 'gp_generate_review_reply', review_id: id, gp_nonce: gp_admin.nonce }, function(res) {
                if(res.success) $('#reply-' + id).html('<strong>Suggested Reply:</strong><br>' + res.data);
            });
        }
        </script>
        <?php
    }
}

new GrowthPress_Reviews_Manager();
