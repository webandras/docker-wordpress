<?php

/**
 * The template for displaying all WooCommerce pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Decimus
 */

get_header();
?>

    <div id="content" class="site-content container-fluid narrow-content side-padding py-5 mt-4">
        <div id="primary" class="content-area">

            <!-- Hook to add something nice -->
            <?php bs_after_primary(); ?>

            <main id="main" class="site-main mt-2">
                <!-- Breadcrumb -->
                <?php woocommerce_breadcrumb(); ?>
                <div class="row">
                    <div class="col">
                        <?php decimus_woocommerce_content(); ?>
                    </div>
                    <!-- sidebar -->
                    <?php //get_sidebar();
                    ?>
                    <!-- row -->
                </div>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- #content -->

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
<?php
get_footer();
