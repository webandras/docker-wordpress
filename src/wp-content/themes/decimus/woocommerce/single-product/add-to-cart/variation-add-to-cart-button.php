<?php
/**
 * Single variation cart button
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">
    <?php do_action('woocommerce_before_add_to_cart_button'); ?>

    <?php
    do_action('woocommerce_before_add_to_cart_quantity');

    woocommerce_quantity_input(array(
        'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
        'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
    ));

    do_action('woocommerce_after_add_to_cart_quantity');
    ?>

    <?php
    $woocommerce_request = new WP_REST_Request('GET', '/decimus/v1/frontend/woocommerce');
    $woocommerce_response = rest_do_request($woocommerce_request);
    $woocommerce_data = rest_get_server()->response_to_data($woocommerce_response, true);

    // check if we received the data from the endpoint
    $have_woocommerce_data = isset($woocommerce_data) && isset($woocommerce_data['data']);
    $woocommerce_options = $have_woocommerce_data ? $woocommerce_data['data']['option_value'] : [];

    $event_registration = isset($woocommerce_options['event_registration']) ? intval($woocommerce_options['event_registration']) : null;
    ?>

    <?php if ( $event_registration ) { ?>
        <button type="submit"
                class="single_add_to_cart_button btn btn-primary"><?php echo esc_html($product->single_add_to_cart_text()); ?></button>
    <?php } else { ?>
        <button type="submit" data-bs-toggle="modal" data-bs-target="#registerToEvent"
                class="single_add_to_cart_button btn btn-primary"><?php echo esc_html('Register'); ?></button>
    <?php } ?>

    <?php do_action('woocommerce_after_add_to_cart_button'); ?>

    <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>"/>
    <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>"/>
    <input type="hidden" name="variation_id" class="variation_id" value="0"/>
</div>
