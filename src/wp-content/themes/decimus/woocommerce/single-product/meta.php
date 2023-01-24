<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( !defined('ABSPATH') ) {
    exit;
}

global $product;
?>

<?php
$woocommerce_request = new WP_REST_Request('GET', '/decimus/v1/frontend/woocommerce');
$woocommerce_response = rest_do_request($woocommerce_request);
$woocommerce_data = rest_get_server()->response_to_data($woocommerce_response, true);

// check if we received the data from the endpoint
$have_woocommerce_data = isset($woocommerce_data) && isset($woocommerce_data['data']);
$woocommerce_options = $have_woocommerce_data ? $woocommerce_data['data']['option_value'] : [];

$show_single_product_meta = isset($woocommerce_options['show_single_product_meta']) ? intval($woocommerce_options['show_single_product_meta']) : null;


?>

<?php if ( $show_single_product_meta === 1 ) { ?>
    <div class="product_meta">

        <?php do_action('woocommerce_product_meta_start'); ?>

        <?php if ( wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable')) ) : ?>

            <span class="sku_wrapper"><?php esc_html_e('SKU:', 'woocommerce'); ?> <span
                        class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span>

        <?php endif; ?>

        <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'woocommerce') . ' ', '</span>'); ?>

        <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span>'); ?>

        <?php do_action('woocommerce_product_meta_end'); ?>

        <?php // echo do_shortcode('[addtoany]'); ?>
    </div>
<?php } ?>
