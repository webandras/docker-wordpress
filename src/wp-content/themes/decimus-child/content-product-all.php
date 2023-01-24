<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if ( empty($product) || !$product->is_visible() ) {
    return;
}
?>

<div class="col mb-5">


    <div class="card">
        <div <?php wc_product_class('', $product); ?>>
            <?php
            // Get product thumbnail
            $size = 'custom_event_width';
            $image_size = apply_filters('single_product_archive_thumbnail_size', $size);
            // Get url to single product
            $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);

            $html = '<div class="text-center">';
            $html .= '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
            $html .= $product ? $product->get_image('custom_event_width') : '';
            echo $html . '</a></div>';
            ?>
            <div class="bg-white card-body">
                <?php
                $html = '<h2 class="h3 bold-500 card-title ' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . '" style="margin-bottom: 10px;">';
                $html .= '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
                echo $html . get_the_title() . '</a></h2>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                // Get product attributes
                $datum_attribute = $product->get_attribute('datum');
                $idopont_attribute = $product->get_attribute('idopont');
                ?>
                <div class="mb1">
                    <?php

                    if ( $datum_attribute && $idopont_attribute ) {
                        echo '<div class="gray-body heading-font medium-size">' . $datum_attribute . ' ' . $idopont_attribute . '</div>';
                    }
                    ?>
                </div>

                <?php
                $short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

                if ( $short_description ) { ?>
                    <div class="woocommerce-product-details__short-description card-text">
                        <?php echo $short_description; // WPCS: XSS ok.
                        ?>
                    </div>
                    <?php
                }

                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
                remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
                do_action('woocommerce_after_shop_loop_item_title');

                echo '<a href="' . esc_url($link) . '" class="btn btn-small woocommerce-LoopProduct-link woocommerce-loop-product__link">Részletek<i class="material-icons" style="position: relative; top: 5px;">arrow_right_alt</i></a>';

                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * Removed! @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
                do_action('woocommerce_after_shop_loop_item');
                ?>
            </div>
        </div>
    </div>
</div>
