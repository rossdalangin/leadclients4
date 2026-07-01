<?php
/**
 * GrowthPress WooCommerce Integration
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class GrowthPress_WooCommerce {

    /**
     * Strategic Commerce Node
     *
     * Synchronizes high-ticket transactions from WooCommerce to the Business OS ledger.
     * Methodology: Automate the reconciliation of financial equity nodes.
     */
    public function __construct() {
        add_action( 'woocommerce_order_status_completed', array( $this, 'handle_order_payment' ) );
    }

    public function handle_order_payment( $order_id ) {
        $order = wc_get_order( $order_id );
        $related_id = $order->get_meta('_gp_related_id');

        if ( $related_id ) {
            // Update related transaction or appointment
            update_post_meta( $related_id, '_gp_payment_status', 'Paid' );
            GrowthPress_Activity::log( "WooCommerce Payment received for related ID #$related_id" );
        }
    }
}

new GrowthPress_WooCommerce();
