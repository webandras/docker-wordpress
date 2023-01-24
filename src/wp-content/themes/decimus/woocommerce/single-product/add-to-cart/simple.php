<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

global $product;

if ( !$product->is_purchasable() ) {
    return;
}

$woocommerce_request = new WP_REST_Request('GET', '/decimus/v1/frontend/woocommerce');
$woocommerce_response = rest_do_request($woocommerce_request);
$woocommerce_data = rest_get_server()->response_to_data($woocommerce_response, true);

// check if we received the data from the endpoint
$have_woocommerce_data = isset($woocommerce_data) && isset($woocommerce_data['data']);
$woocommerce_options = $have_woocommerce_data ? $woocommerce_data['data']['option_value'] : [];

$event_registration = isset($woocommerce_options['event_registration']) ? intval($woocommerce_options['event_registration']) : null;

echo wc_get_stock_html($product); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

    <?php do_action('woocommerce_before_add_to_cart_form'); ?>

    <form class="cart"
          action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
          method="post" enctype='multipart/form-data'>
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

        <?php if ( $event_registration ) { ?>
            <button type="submit" id="register-form-button" name="add-to-cart" data-bs-toggle="modal"
                    data-bs-target="#registerToEvent"
                    value="<?php echo esc_attr($product->get_id()); ?>"
                    class="single_add_to_cart_button alt btn btn-primary"><?php _e('Register to event', 'decimus') ?>
            </button>
        <?php } else { ?>
            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
                    class="single_add_to_cart_button alt btn btn-primary"><?php echo esc_html($product->single_add_to_cart_text()); ?>
                <i class="fa fa-shopping-cart"></i>
            </button>
        <?php } ?>

        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
    </form>

    <?php do_action('woocommerce_after_add_to_cart_form'); ?>

<?php endif; ?>
