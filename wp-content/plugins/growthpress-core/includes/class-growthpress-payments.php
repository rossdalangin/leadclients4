<?php
/**
 * GrowthPress Payments Engine
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_Payments {

    public function __construct() {
        add_action( 'init', array( $this, 'register_payment_cpt' ) );
        add_action( 'wp_ajax_gp_process_deposit', array( $this, 'handle_deposit' ) );
        add_action( 'wp_ajax_gp_mark_transaction_paid', array( $this, 'handle_manual_paid' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_payment_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_payment_meta' ) );
        add_filter( 'manage_gp_transaction_posts_columns', array( $this, 'transaction_columns' ) );
        add_action( 'manage_gp_transaction_posts_custom_column', array( $this, 'transaction_column_content' ), 10, 2 );
    }

    public function transaction_columns( $cols ) {
        $cols['_amt'] = 'Amount';
        $cols['_type'] = 'Type';
        $cols['_method'] = 'Method';
        $cols['_status'] = 'Status';
        return $cols;
    }

    public function transaction_column_content( $col, $post_id ) {
        if ( $col === '_status' ) {
            $s = get_post_meta( $post_id, '_status', true ) ?: 'Pending';
            $color = ($s === 'Paid') ? '#10b981' : (($s === 'Refunded') ? '#ef4444' : '#f59e0b');
            echo "<span style='color:$color; font-weight:bold;'>$s</span>";
            if($s !== 'Paid') {
                echo '<br><button class="button button-small" style="margin-top:5px;" onclick="gpMarkPaid('.$post_id.')">MARK PAID</button>';
                echo '<script>function gpMarkPaid(id){ jQuery.post(ajaxurl, {action:"gp_mark_transaction_paid", transaction_id:id, gp_nonce:"'.wp_create_nonce("gp_admin_nonce").'"}, function(){ location.reload(); }); }</script>';
            }
            return;
        }
        if ( $col === '_amt' ) echo '$' . number_format(get_post_meta( $post_id, '_amount', true ));
        if ( $col === '_type' ) {
            $t = get_post_meta($post_id, '_transaction_type', true) ?: 'Revenue';
            echo '<span style="color:'.($t === 'Revenue' ? '#10b981' : '#ef4444').'; font-weight:bold;">'.$t.'</span>';
        }
        if ( $col === '_method' ) echo get_post_meta( $post_id, '_payment_method', true ) ?: 'Stripe';
        if ( $col === '_status' ) {
            $s = get_post_meta( $post_id, '_status', true ) ?: 'Pending';
            $color = ($s === 'Paid') ? '#10b981' : (($s === 'Refunded') ? '#ef4444' : '#f59e0b');
            echo "<span style='color:$color; font-weight:bold;'>$s</span>";
        }
    }

    public function add_payment_meta_boxes() {
        add_meta_box( 'gp_payment_details', '💰 Strategic Transaction Data Ledger', array( $this, 'render_payment_meta' ), 'gp_transaction', 'normal', 'high' );
    }

    public function render_payment_meta( $post ) {
        $amount = get_post_meta( $post->ID, '_amount', true );
        $status = get_post_meta( $post->ID, '_status', true ) ?: 'Pending';
        $method = get_post_meta( $post->ID, '_payment_method', true ) ?: 'Stripe';
        $type = get_post_meta( $post->ID, '_transaction_type', true ) ?: 'Revenue';
        $category = get_post_meta( $post->ID, '_transaction_category', true ) ?: 'Operations';
        $tax = get_post_meta( $post->ID, '_is_tax_deductible', true );
        $audit = get_post_meta( $post->ID, '_audit_notes', true );
        $related = get_post_meta( $post->ID, '_related_id', true );
        $ref = get_post_meta( $post->ID, '_payment_reference', true );
        $verified = get_post_meta( $post->ID, '_is_verified', true );
        ?>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #64748b;">
            <p style="margin: 0; font-size: 13px; color: #334155;"><strong>Ledger Instructions:</strong> This record serves as the financial source of truth for the system. Transactions linked to appointments or proposals will contribute to the real-time ROI reports on the dashboard.</p>
        </div>
        <table class="form-table">
            <tr>
                <th><label>Transaction Status</label><p class="description">Current payment state. 'Paid' records are included in earned equity reports.</p></th>
                <td>
                    <select name="gp_payment_status" style="width:100%;">
                        <option value="Pending" <?php selected($status, 'Pending'); ?>>Pending / Unpaid</option>
                        <option value="Paid" <?php selected($status, 'Paid'); ?>>Paid / Synchronized</option>
                        <option value="Refunded" <?php selected($status, 'Refunded'); ?>>Refunded</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Ledger Amount ($)</label><p class="description">The specific monetary value of this entry.</p></th>
                <td><input type="number" name="gp_payment_amount" value="<?php echo esc_attr($amount); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Entry Classification</label><p class="description">Distinguish between income and outgoing strategic costs.</p></th>
                <td>
                    <select name="gp_transaction_type" style="width:100%;">
                        <option value="Revenue" <?php selected($type, 'Revenue'); ?>>Strategic Revenue (Income)</option>
                        <option value="Expense" <?php selected($type, 'Expense'); ?>>Operating Expense (Outgoing)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Strategic Category</label><p class="description">Used for departmental ROI and expense tracking.</p></th>
                <td>
                    <select name="gp_transaction_category" style="width:100%;">
                        <option value="Marketing" <?php selected($category, 'Marketing'); ?>>Marketing & Lead Gen</option>
                        <option value="Operations" <?php selected($category, 'Operations'); ?>>Operations & Delivery</option>
                        <option value="Software" <?php selected($category, 'Software'); ?>>Software & AI Infrastructure</option>
                        <option value="Payroll" <?php selected($category, 'Payroll'); ?>>Payroll & Specialists</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Tax Deductible</label><p class="description">Flag for end-of-year accounting audits.</p></th>
                <td><input type="checkbox" name="gp_is_tax" value="1" <?php checked($tax, '1'); ?>> Mark as Deductible</td>
            </tr>
            <tr>
                <th><label>Payment Method</label><p class="description">The gateway or channel used for the fund transfer.</p></th>
                <td>
                    <select name="gp_payment_method" style="width:100%;">
                        <option value="Stripe" <?php selected($method, 'Stripe'); ?>>Stripe Card</option>
                        <option value="PayPal" <?php selected($method, 'PayPal'); ?>>PayPal Enterprise</option>
                        <option value="Bank" <?php selected($method, 'Bank'); ?>>Bank Transfer</option>
                        <option value="Cash" <?php selected($method, 'Cash'); ?>>Cash / Manual</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label>Payment Reference</label><p class="description">External ID from Stripe, PayPal, or Bank Statement.</p></th>
                <td><input type="text" name="gp_payment_ref" value="<?php echo esc_attr($ref); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label>Receipt Verification</label><p class="description">Manual override to confirm funds have cleared the strategic node.</p></th>
                <td><input type="checkbox" name="gp_payment_verified" value="1" <?php checked($verified, '1'); ?>> Marked as Verified</td>
            </tr>
            <tr>
                <th><label>Related System Node ID</label><p class="description">ID of the linked Lead, Appointment, or Proposal.</p></th>
                <td><input type="number" name="gp_related_id" value="<?php echo esc_attr($related); ?>" class="regular-text" placeholder="e.g. 422"></td>
            </tr>
            <tr>
                <th><label>Financial Audit Notes</label><p class="description">Internal notes for accounting verification and reconciliation.</p></th>
                <td><textarea name="gp_audit_notes" style="width:100%; height:100px;"><?php echo esc_textarea($audit); ?></textarea></td>
            </tr>
        </table>
        <?php
    }

    public function save_payment_meta( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['gp_payment_status'] ) ) return;
        update_post_meta( $post_id, '_status', sanitize_text_field( $_POST['gp_payment_status'] ) );
        update_post_meta( $post_id, '_amount', floatval( $_POST['gp_payment_amount'] ) );
        update_post_meta( $post_id, '_payment_method', sanitize_text_field( $_POST['gp_payment_method'] ) );
        update_post_meta( $post_id, '_payment_reference', sanitize_text_field( $_POST['gp_payment_ref'] ) );
        update_post_meta( $post_id, '_is_verified', isset($_POST['gp_payment_verified']) ? '1' : '0' );
        update_post_meta( $post_id, '_transaction_type', sanitize_text_field( $_POST['gp_transaction_type'] ) );
        update_post_meta( $post_id, '_transaction_category', sanitize_text_field( $_POST['gp_transaction_category'] ) );
        update_post_meta( $post_id, '_is_tax_deductible', isset($_POST['gp_is_tax']) ? '1' : '0' );
        update_post_meta( $post_id, '_audit_notes', sanitize_textarea_field( $_POST['gp_audit_notes'] ) );
        update_post_meta( $post_id, '_related_id', intval( $_POST['gp_related_id'] ) );
    }

    public function register_payment_cpt() {
        register_post_type( 'gp_transaction', array(
            'labels'      => array( 'name' => 'Transactions', 'singular_name' => 'Transaction' ),
            'public'      => false,
            'show_ui'     => true,
            'menu_icon'   => 'dashicons-money-alt',
            'supports'    => array( 'title', 'custom-fields' ),
        ) );
    }

    public function create_invoice( $amount, $related_id, $type = 'booking' ) {
        $transaction_id = wp_insert_post( array(
            'post_title'  => sprintf( 'Invoice for %s #%d', ucfirst($type), $related_id ),
            'post_type'   => 'gp_transaction',
            'post_status' => 'publish',
        ) );
        update_post_meta( $transaction_id, '_amount', $amount );
        update_post_meta( $transaction_id, '_status', 'Pending' );
        update_post_meta( $transaction_id, '_related_id', $related_id );
        return $transaction_id;
    }

    public function handle_manual_paid() {
        check_ajax_referer('gp_admin_nonce', 'gp_nonce');
        if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error();

        $transaction_id = intval($_POST['transaction_id']);
        update_post_meta($transaction_id, '_status', 'Paid');
        update_post_meta($transaction_id, '_verified_at', current_time('mysql'));

        GrowthPress_Activity::log("Ledger Sync: Transaction #$transaction_id manually marked as PAID. ROI reports synchronized.");
        wp_send_json_success("Transaction successfully synchronized to financial ledger.");
    }

    public function handle_deposit() {
        check_ajax_referer( 'gp_portal_nonce', 'gp_nonce' );
        // Mock Stripe/PayPal integration logic
        $transaction_id = intval($_POST['transaction_id']);
        update_post_meta( $transaction_id, '_status', 'Paid' );
        GrowthPress_Activity::log( "Payment received for Transaction #$transaction_id" );
        wp_send_json_success('Payment confirmed.');
    }
}

new GrowthPress_Payments();
