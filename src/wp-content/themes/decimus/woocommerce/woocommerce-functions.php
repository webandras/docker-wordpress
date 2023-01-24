<?php
/**
 * Woocommerce functions and definitions
 *
 * @package Decimus
 */


// Woocommerce Templates
function decimus_add_woocommerce_support(): void
{
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'decimus_add_woocommerce_support');
// Woocommerce Templates END


// Woocommerce Lightbox
add_action('after_setup_theme', 'decimus');

function decimus(): void
{
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

// Woocommerce Lightbox End


// Register Ajax Cart
function decimus_register_ajax_cart(): void
{
    require_once('ajax-cart/ajax-add-to-cart.php');
}

add_action('after_setup_theme', 'decimus_register_ajax_cart');
// Register Ajax Cart End


// Scripts and Styles
function decimus_wc_scripts(): void
{
    // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. 
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/css/woocommerce-style.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/woocommerce/js/woocommerce.js'));

    // WooCommerce CSS
    wp_enqueue_style('woocommerce', get_template_directory_uri() . '/woocommerce/css/woocommerce-style.css', array(), $modificated);

    // WooCommerce JS
    wp_enqueue_script('woocommerce-script', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array(), $modificated, true);

    if ( is_singular() && comments_open() && get_option('thread_comments') ) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'decimus_wc_scripts');
//Scripts and styles End


// Mini cart header
if ( !function_exists('decimus_mini_cart') ) :

    function decimus_mini_cart($fragments)
    {
        ob_start();
        $count = WC()->cart->cart_contents_count;
        ?><span class="cart-content"><?php
        if ( $count > 0 ) {
            ?>
            <span class="cart-content-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light"><?php echo esc_html($count); ?></span>
            <span class="cart-total ms-1 d-none d-md-inline"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
            <?php
        }
        ?></span><?php

        $fragments['span.cart-content'] = ob_get_clean();

        return $fragments;
    }

    add_filter('woocommerce_add_to_cart_fragments', 'decimus_mini_cart');

endif;
// Mini cart header End


// Forms

/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null $value Not in use.
 *
 * @return mixed
 */
if ( !function_exists('decimus_wc_form_field_args') ) {
    function decimus_wc_form_field_args(array $args, $key, $value = null): array
    {
        // Start field type switch case.
        switch ($args['type']) {
            /* Targets all select input type elements, except the country and state select input types */
            case 'select':
                // Add a class to the field's html element wrapper - woocommerce
                // input types (fields) are often wrapped within a <p></p> tag.
                $args['class'][] = 'form-group';
                // Add a class to the form input itself.
                $args['input_class'] = array('form-control', 'input-lg');
                $args['label_class'] = array('control-label');
                $args['custom_attributes'] = array(
                    'data-plugin' => 'select2',
                    'data-allow-clear' => 'true',
                    'aria-hidden' => 'true',
                    // Add custom data attributes to the form input itself.
                );
                break;
            // By default WooCommerce will populate a select with the country names - $args
            // defined for this specific input type targets only the country select element.
            case 'country':
                $args['class'][] = 'form-group single-country';
                $args['label_class'] = array('control-label');
                break;
            // By default WooCommerce will populate a select with state names - $args defined
            // for this specific input type targets only the country select element.
            case 'state':
                // Add class to the field's html element wrapper.
                $args['class'][] = 'form-group';
                // add class to the form input itself.
                $args['input_class'] = array('', 'input-lg');
                $args['label_class'] = array('control-label');
                $args['custom_attributes'] = array(
                    'data-plugin' => 'select2',
                    'data-allow-clear' => 'true',
                    'aria-hidden' => 'true',
                );
                break;
            case 'password':
            case 'text':
            case 'email':
            case 'tel':
            case 'number':
                $args['class'][] = 'form-group';
                $args['input_class'] = array('form-control', 'input-lg');
                $args['label_class'] = array('control-label');
                break;
            case 'textarea':
                $args['input_class'] = array('form-control', 'input-lg');
                $args['label_class'] = array('control-label');
                break;
            case 'checkbox':
                $args['label_class'] = array('custom-control custom-checkbox');
                $args['input_class'] = array('custom-control-input', 'input-lg');
                break;
            case 'radio':
                $args['label_class'] = array('custom-control custom-radio');
                $args['input_class'] = array('custom-control-input', 'input-lg');
                break;
            default:
                $args['class'][] = 'form-group';
                $args['input_class'] = array('form-control', 'input-lg');
                $args['label_class'] = array('control-label');
                break;
        } // end switch ($args).
        return $args;
    }
}

if ( !is_admin() && !function_exists('decimus_wc_review_ratings_enabled') ) {
    /**
     * Check if reviews are enabled.
     *
     * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
     *
     * @return bool
     */
    function decimus_wc_reviews_enabled(): bool
    {
        return 'yes' === get_option('woocommerce_enable_reviews');
    }

    /**
     * Check if reviews ratings are enabled.
     *
     * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
     *
     * @return bool
     */
    function decimus_wc_review_ratings_enabled(): bool
    {
        return decimus_wc_reviews_enabled() && 'yes' === get_option('woocommerce_enable_review_rating');
    }
}

// Forms end


// WooCommerce Breadcrumb
if ( !function_exists('decimus_woocommerce_breadcrumbs') ) :
    add_filter('woocommerce_breadcrumb_defaults', 'decimus_woocommerce_breadcrumbs');
    function decimus_woocommerce_breadcrumbs(): array
    {
        return array(
            'delimiter' => ' &nbsp;&#47;&nbsp; ',
            'wrap_before' => '<nav class="breadcrumb small-size mb-0 mt-0 pt-3 ps-3 pb-2 small rounded" itemprop="breadcrumb">',
            'wrap_after' => '</nav>',
            'before' => '',
            'after' => '',
            'home' => _x('Home', 'breadcrumb', 'woocommerce'),
        );
    }
endif;
// WooCommerce Breadcrumb End


// Optional Telephone
if ( !function_exists('decimus_phone_optional') ) :

    function decimus_phone_optional(array $address_fields): array
    {
        $address_fields['billing_phone']['required'] = false;
        return $address_fields;
    }

    add_filter('woocommerce_billing_fields', 'decimus_phone_optional', 10, 1);
endif;
// Optional Telephone End


// Bootstrap Billing forms
function decimus_wc_bootstrap_form_field_args(array $args, $key, $value): array
{

    $args['input_class'][] = 'form-control';
    return $args;
}

add_filter('woocommerce_form_field_args', 'decimus_wc_bootstrap_form_field_args', 10, 3);
// Bootstrap Billing forms End


// Ship to a different address closed by default
add_filter('woocommerce_ship_to_different_address_checked', '__return_false');
// Ship to a different address closed by default End


// Remove cross-sells at cart
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
// Remove cross-sells at cart End


// Remove CSS and/or JS for Select2 used by WooCommerce, see https://gist.github.com/Willem-Siebe/c6d798ccba249d5bf080.
add_action('wp_enqueue_scripts', 'decimus_dequeue_styles_and_scripts_select2', 100);

function decimus_dequeue_styles_and_scripts_select2(): void
{
    if ( class_exists('woocommerce') ) {
        wp_dequeue_style('selectWoo');
        wp_deregister_style('selectWoo');

        wp_dequeue_script('selectWoo');
        wp_deregister_script('selectWoo');
    }
}

// Remove CSS and/or JS for Select2 END


// Mini cart widget buttons
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10);
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

function decimus_woocommerce_widget_shopping_cart_button_view_cart(): void
{
    echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn btn-warning d-block mb-2">' . esc_html__('View cart', 'woocommerce') . '</a>';
}

function decimus_woocommerce_widget_shopping_cart_proceed_to_checkout(): void
{
    echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary d-block">' . esc_html__('Checkout', 'woocommerce') . '</a>';
}

add_action('woocommerce_widget_shopping_cart_buttons', 'decimus_woocommerce_widget_shopping_cart_button_view_cart', 10);
add_action('woocommerce_widget_shopping_cart_buttons', 'decimus_woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
// Mini cart widget buttons End


// Cart empty message alert
remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
add_action('woocommerce_cart_is_empty', 'decimus_empty_cart_message', 10);

function decimus_empty_cart_message(): void
{
    $html = '<div class="cart-empty alert alert-info">';
    $html .= wp_kses_post(apply_filters('wc_empty_cart_message', __('Your cart is currently empty.', 'woocommerce')));
    echo $html . '</div>';
}

// Cart empty message alert End


if ( !function_exists('decimus_woocommerce_content') ) {

    /**
     * Output WooCommerce content.
     *
     * This function is only used in the optional 'woocommerce.php' template.
     * which people can add to their themes to add basic woocommerce support.
     * without hooks or modifying core templates.
     */
    function decimus_woocommerce_content(): void
    {
        if ( is_singular('product') ) {

            while (have_posts()) :
                the_post();
                wc_get_template_part('content', 'single-product');
            endwhile;

        } else {
            ?>
            <div class="px-2 px-md-3">

            <?php if ( apply_filters('woocommerce_show_page_title', true) ) : ?>
                <h1 class="page-title h2 mt-0"><?php woocommerce_page_title(); ?></h1>

            <?php endif; ?>

            <?php do_action('woocommerce_archive_description'); ?>

            <?php if ( woocommerce_product_loop() ) : ?>

                <?php do_action('woocommerce_before_shop_loop'); ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php if ( wc_get_loop_prop('total') ) : ?>
                    <?php while (have_posts()) : ?>
                        <?php the_post(); ?>
                        <?php wc_get_template_part('content', 'product'); ?>
                    <?php endwhile; ?>
                <?php endif; ?>

                <?php woocommerce_product_loop_end(); ?>

                <?php do_action('woocommerce_after_shop_loop'); ?>

                </div>

            <?php
            else :
                do_action('woocommerce_no_products_found');
            endif;
        }
    }
}

/**
 * Reorder product data tabs
 */
// add_filter( 'woocommerce_product_tabs', 'decimus_woocommerce_reorder_tabs', 98 );
function decimus_woocommerce_reorder_tabs($tabs): array
{

    // Additional information first
    $tabs['additional_information']['priority'] = 5;
    // Description second
    $tabs['description']['priority'] = 10;
    // Reviews third
    $tabs['video_tab']['priority'] = 15;

    return $tabs;
}

/*
 * See https://stackoverflow.com/a/35689563
 * woocommerce_payment_complete_order_status  is only triggered when an online payment is required.
 * change the paid order status (that is set by the payment gateway for paid orders) to "completed"
 * basically disabling the "processing" paid order status
 *
 * General information
 * https://woocommerce.com/document/woocommerce-order-status-control/
 * */
add_action('woocommerce_payment_complete_order_status', 'decimus_auto_complete_virtual_paid_order', 10, 3);
/*function wc_auto_complete_order($status, $order_id, $order)
{
    return 'completed';
}*/

/*
 * See https://quadlayers.com/autocomplete-woocommerce-orders/
 * Only for virtual products
 *
 * A different solution based on shipping method: https://stackoverflow.com/questions/48303688/woocommerce-autocomplete-paid-orders-based-on-shipping-method/48306674#48306674
 * */
function decimus_auto_complete_virtual_paid_order($payment_status, $order_id, $order)
{
    $current_status = $order->get_status();
    // We only want to update the status to 'completed' if it's coming from one of the following statuses:
    $allowed_current_statuses = array('on-hold', 'pending', 'failed');

    if ( 'processing' === $payment_status && in_array($current_status, $allowed_current_statuses) ) {

        $order_items = $order->get_items();

        // Create an array of products in the order
        $order_products = array_filter(array_map(function ($item) {
            // Get associated product for each line item
            return $item->get_product();
        }, $order_items), function ($product) {
            // Remove non-products
            return !!$product;
        });

        if ( count($order_products > 0) ) {
            // Check if each product is 'virtual'
            $is_virtual_order = array_reduce($order_products, function ($virtual_order_so_far, $product) {
                return $virtual_order_so_far && $product->is_virtual();
            }, true);

            if ( $is_virtual_order ) {
                $payment_status = 'completed';
            }
        }
    }
    return $payment_status;
}



