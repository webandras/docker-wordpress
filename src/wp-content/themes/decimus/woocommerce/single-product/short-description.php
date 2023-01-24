<?php

/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( !defined('ABSPATH') ) {
    exit; // Exit if accessed directly.
}

global $post;
?>

<div class="woocommerce-product-details__short-description">
    <?php
    $product_tabs = apply_filters('woocommerce_product_tabs', array());

    if ( !empty($product_tabs) ) {

        foreach ($product_tabs as $key => $product_tab) {
            if ( $key === 'description' ) {
                if ( isset($product_tab['callback']) ) {
                    call_user_func($product_tab['callback'], $key, $product_tab);
                }
            }
        }
    }
    ?>
</div>
