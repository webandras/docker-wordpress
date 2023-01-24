<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Decimus
 */

get_header();
?>
    <div id="content" class="site-content container-fluid side-padding narrow-content mt-5 py-5">
        <div id="primary" class="content-area">

            <main id="main" class="site-main">

                <section class="error-404 not-found">
                    <div class="page-404 align-center">

                        <h1 class="mb-3 h3 mt-2"><?php _e('The page you are looking for does not exist', 'decimus') ?></h1>
                        <!-- Remove this line and place some widgets -->
                        <p class="alert alert-info mb-4 py-1 mx-auto"><?php esc_html_e('Page not found.', 'decimus'); ?></p>
                        <!-- 404 Widget -->
                        <?php if ( is_active_sidebar('404-page') ) : ?>
                            <div><?php dynamic_sidebar('404-page'); ?></div>
                        <?php endif; ?>
                        <a class="btn btn-outline-primary" href="<?php echo esc_url(home_url()); ?>"
                           role="button"><?php esc_html_e('Back Home &raquo;', 'decimus'); ?></a>
                    </div>
                </section><!-- .error-404 -->

            </main><!-- #main -->

        </div><!-- #primary -->
    </div><!-- #content -->

<?php
get_footer();
