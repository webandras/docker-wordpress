<?php
/**
 * Template Name: No Sidebar with Vue.js app container
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

    <div id="content" class="site-content container-fluid side-padding narrow-content py-5 mt-4">
        <div id="primary" class="content-area">

            <!-- Hook to add something nice -->
            <?php bs_after_primary(); ?>

            <div class="row">
                <div class="col-12">

                    <main id="main" class="site-main">

                        <header class="entry-header">
                            <?php the_post(); ?>
                            <?php the_breadcrumb(); ?>
                            <!-- Title -->
                            <?php the_title('<h1 class="mt-0">', '</h1>'); ?>
                            <!-- Featured Image-->
                            <?php decimus_post_thumbnail(); ?>
                            <!-- .entry-header -->
                        </header>

                        <div class="entry-content">
                            <!-- Content -->
                            <?php the_content(); ?>
                            <!-- VUE.js SPA will be rendered here -->
                            <?php echo do_shortcode('[vuecommerce_filter_products]'); ?>
                            <!-- .entry-content -->
                            <?php wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'decimus'),
                                'after' => '</div>',
                            ));
                            ?>
                        </div>

                        <footer class="entry-footer px-1 px-sm-2 px-md-3">

                        </footer>
                        <!-- Comments -->
                        <?php comments_template(); ?>

                    </main><!-- #main -->

                </div><!-- col -->
                <?php // get_sidebar(); ?>
            </div><!-- row -->

        </div><!-- #primary -->
    </div><!-- #content -->

<?php
get_footer();
