<?php

/**
 * The template for displaying all pages
 * Template Name: blog
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Gulacsi_Bulma
 */

get_header();
?>

<div class="container is-fluid">

  <div class="columns">

    <div class="column is-half">

      <main id="primary" class="site-main">

        <?php
        while (have_posts()) :
          the_post();

          get_template_part('template-parts/content', 'page');

          // If comments are open or we have at least one comment, load up the comment template.
          if (comments_open() || get_comments_number()) :
            comments_template();
          endif;

        endwhile; // End of the loop.
        ?>

      </main><!-- #main -->
    </div>

    <div class="column is-one-quarter">
      <?php
      get_sidebar();
      ?>
    </div>
  </div>
</div>
<?php
get_footer();
