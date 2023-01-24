<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

    <?php
    /**
     * Hook: woocommerce_before_single_product_summary.
     *
     * @hooked woocommerce_show_product_sale_flash - 10
     * @hooked woocommerce_show_product_images - 20
     */
    do_action('woocommerce_before_single_product_summary');
    ?>

    <div class="summary entry-summary">
        <?php
        /**
         * Hook: woocommerce_single_product_summary.
         *
         * @hooked woocommerce_template_single_title - 5
         * @hooked woocommerce_template_single_rating - 10
         * @hooked woocommerce_template_single_price - 10
         * @hooked woocommerce_template_single_excerpt - 20
         * @hooked woocommerce_template_single_add_to_cart - 30
         * @hooked woocommerce_template_single_meta - 40
         * @hooked woocommerce_template_single_sharing - 50
         * @hooked WC_Structured_Data::generate_product_data() - 60
         */
        do_action('woocommerce_single_product_summary');
        ?>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');
    ?>

    <?php
    $contact_request = new WP_REST_Request('GET', '/decimus/v1/frontend/contact');
    $contact_response = rest_do_request($contact_request);
    $contact_data = rest_get_server()->response_to_data($contact_response, true);

    // check if we received the data from the endpoint
    $have_contact_data = isset($contact_data) && isset($contact_data['data']);
    $contact_options = $have_contact_data ? $contact_data['data']['option_value'] : [];

    $phone = isset($contact_options['phone_number']) ? esc_html($contact_options['phone_number']) : '';
    $email = isset($contact_options['email_address']) ? sanitize_email($contact_options['email_address']) : '';
    $messenger = isset($contact_options['messenger']) ? sanitize_url($contact_options['messenger']) : '';

    ?>

    <div class="modal fade" tabindex="-1" id="registerToEvent" aria-labelledby="registerToEventLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php _e('Register to the event', 'decimus') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <p class="pt-0"><?php _e('Please fill in this form to register to the event, or contact me:', 'decimus') ?></p>

                            <ul class="no-bullets mt-0">
                                <li>
                                    <a href="tel:<?php echo $phone ?>"><?php echo $phone ?></a>
                                </li>
                                <li>
                                    <a href="mailto:<?php echo $email ?>"><?php echo $email ?></a>
                                </li>
                                <li>
                                    <a href="<?php echo $messenger ?>">Messenger</a>
                                </li>
                            </ul>

                            <hr>
                        </div>
                    </div>
                    <?php
                    echo do_shortcode(
                        '[contact-form-7 id="180" title="Event Registration" event-name="' . get_the_title() . '" event-details=""]'
                    );
                    ?>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal"><?php _e('Close window', 'decimus') ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php do_action('woocommerce_after_single_product'); ?>
