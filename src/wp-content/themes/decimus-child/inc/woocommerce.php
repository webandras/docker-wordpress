<?php

add_action('woocommerce_after_single_product_summary', 'decimus_child_add_share_to_products', 30);
function decimus_child_add_share_to_products(): void
{
    echo '<div class="ps-2">';
    echo '<p class="small-size"><b>' . __('Share:', 'decimus') . '</b></p>';
    echo do_shortcode('[bs-share-buttons]');
    echo '<div>';
}


// hooks to the product data tab
add_action('woocommerce_product_options_general_product_data', 'decimus_child_product_video_field');
// Adds a video field at the product data tab
function decimus_child_product_video_field(): void
{
    $args = array(
        'id' => 'product_video_field',
        'label' => sanitize_text_field('Product video'),
        'placeholder' => __('Paste your embed code from YouTube', 'decimus'),
        'desc_tip' => true,
        'style' => 'height:120px'

    );
    echo woocommerce_wp_textarea_input($args);
}


add_action('woocommerce_process_product_meta', 'decimus_child_product_video_field_save');
function decimus_child_product_video_field_save(int $post_id): void
{
    $product_video_field = $_POST['product_video_field'];
    update_post_meta($post_id, 'product_video_field', $product_video_field);
}


/**
 * Add a custom product data tab for featured video
 */
add_filter('woocommerce_product_tabs', 'decimus_child_new_product_tab');
function decimus_child_new_product_tab(array $tabs): array
{
    // Adds the new tab
    if ( !empty(get_the_content()) ) {
        $tabs['video_tab'] = array(
            'title' => __('Video', 'decimus'),
            'priority' => 100,
            'callback' => 'decimus_child_new_product_tab_content'
        );
    }
    return $tabs;
}


function decimus_child_new_product_tab_content(): void
{
    $product_video = get_post_meta(get_the_ID(), 'product_video_field', true);

    // The new tab content
    echo '<h2 class="h4">' . __('Video', 'decimus') . '</h2>';
    echo $product_video ?
        '<div id="product-video-container" class="ratio ratio-16x9">' . $product_video . '</div>'
        : '<p>' . __('No video available for this product.', 'decimus') . '</p>';

}


// Show notice if customer does not tick
add_action('woocommerce_checkout_process', 'decimus_child_not_approved_privacy');
function decimus_child_not_approved_privacy(): void
{
    if ( !(int)isset($_POST['privacy_policy']) ) {
        wc_add_notice(__('Kérjük az Adatkezelési Tájékoztató elolvasását és elfogadását a rendelés folytatásához.', 'decimus'), 'error');
    }
}


add_filter('woocommerce_add_to_cart_validation', 'decimus_child_remove_cart_item_before_add_to_cart', 20, 3);
function decimus_child_remove_cart_item_before_add_to_cart($passed, int $product_id, int $quantity)
{
    if ( !WC()->cart->is_empty() )
        WC()->cart->empty_cart();
    return $passed;
}


//remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);


add_filter('woocommerce_single_product_zoom_enabled', '__return_false');


/**
 * Change the default country on the checkout for non-existing users only
 */
add_filter('default_checkout_billing_state', 'decimus_child_change_default_checkout_state');

function decimus_child_change_default_checkout_state($state): string
{
    // If the user already exists, don't override country
    if ( WC()->customer->get_is_paying_customer() ) {
        return $state;
    }

    return 'CS'; // State code i18n/states
}
