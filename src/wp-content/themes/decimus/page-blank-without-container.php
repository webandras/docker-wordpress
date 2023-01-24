<?php
/**
 * Template Name: Blank without container
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Decimus
 */

get_header();
?>
    <div id="content" class="site-content narrow-content">
        <div id="primary" class="content-area">

            <main id="main" class="site-main">

                <div class="entry-content">
                    <?php the_post(); ?>
                    <?php the_content(); ?>
                    <?php wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'decimus'),
                        'after' => '</div>',
                    ));
                    ?>
                </div>

            </main><!-- #main -->

        </div><!-- #primary -->
    </div><!-- #content -->
<?php
get_footer();
