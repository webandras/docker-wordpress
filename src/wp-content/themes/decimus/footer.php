<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Decimus
 */

?>

<?php

$global_request = new WP_REST_Request('GET', '/decimus/v1/frontend/global');
$global_response = rest_do_request($global_request);
$global_data = rest_get_server()->response_to_data($global_response, true);

// check if we received the data from the endpoint
$have_global_data = isset($global_data) && isset($global_data['data']);
$global_options = $have_global_data ? $global_data['data']['option_value'] : [];

$scroll_to_top_arrow = $have_global_data ? esc_attr($global_options['enable_scroll_to_top_arrow']) : true;

?>

<footer>

    <div class="decimus-footer">
        <div class="container">

            <!-- Top Footer Widget -->
            <?php if ( is_active_sidebar('top-footer') ) : ?>
                <div>
                    <?php dynamic_sidebar('top footer'); ?>
                </div>
            <?php endif; ?>

            <div class="row">

                <!-- Footer 1 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar('footer-1') ) : ?>
                        <div>
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer 2 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar('footer-2') ) : ?>
                        <div>
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer 3 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar('footer-3') ) : ?>
                        <div>
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer 4 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar('footer-4') ) : ?>
                        <div>
                            <?php dynamic_sidebar('footer-4'); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Footer Widgets End -->

            </div>

            <!-- Bootstrap 5 Nav Walker Footer Menu -->
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer-menu',
                'container' => false,
                'menu_class' => '',
                'fallback_cb' => '__return_false',
                'items_wrap' => '<ul id="footer-menu" class="nav %2$s">%3$s</ul>',
                'depth' => 1,
                'walker' => new bootstrap_5_wp_nav_menu_walker()
            ));
            ?>
            <!-- Bootstrap 5 Nav Walker Footer Menu End -->

        </div>
    </div>

    <div class="decimus-info text-muted border-top py-2 text-center">
        <div class="container">
            <small>&copy;&nbsp;<?php echo Date('Y'); ?> - <?php bloginfo('name'); ?></small>
        </div>
    </div>

</footer>

<?php if ( $scroll_to_top_arrow ) { ?>
    <div class="top-button position-fixed zi-1020">
        <a href="#to-top" class="btn btn-primary shadow"><i class="fas fa-chevron-up"></i></a>
    </div>
<?php } ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
