<?php
/*
 * List template.
 *
 * This template can be overriden by copying this file to your-theme/bs5-post-page-grid-list/list.php
 *
 * @author 		Bastian Kreiter
 * @package 	bS5 Post/Page Grid/List
 * @version     1.0.0

Post/Page List Shortcode 
Posts: [bs-post-list type="post" category="documentation, category-default" order="DESC" orderby="date" posts="6"]
Pages: [bs-post-list type="page" post_parent="413" order="DESC" orderby="date"]
*/


// Post List Shortcode
add_shortcode('bs-post-list', 'decimus_post_list');
function decimus_post_list($atts)
{
    ob_start();
    extract(shortcode_atts(array(
        'type' => 'post',
        'order' => 'date',
        'orderby' => 'date',
        'posts' => -1,
        'category' => '',
        'post_parent' => '',
    ), $atts));
    $options = array(
        'post_type' => $type,
        'order' => $order,
        'orderby' => $orderby,
        'posts_per_page' => $posts,
        'category_name' => $category,
        'post_parent' => $post_parent,
    );
    $query = new WP_Query($options);
    if ( $query->have_posts() ) { ?>


        <?php while ($query->have_posts()) : $query->the_post(); ?>


            <div class="card horizontal mb-4 bg-white">
                <div class="row">
                    <!-- Featured Image-->
                    <?php if ( has_post_thumbnail() )
                        echo '<div class="card-img-left-md col-lg-5">' . get_the_post_thumbnail(null, 'boritokep') . '</div>';
                    ?>
                    <div class="col">
                        <div class="card-body text-gray">

                            <?php decimus_category_badge(); ?>

                            <!-- Title -->
                            <h2 class="blog-post-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <!-- Meta -->
                            <?php if ( 'post' === get_post_type() ) : ?>
                                <small class="text-muted mb-2">
                                    <?php
                                    decimus_date();
                                    decimus_author();
                                    decimus_comments();
                                    decimus_edit();
                                    ?>
                                </small>
                            <?php endif; ?>
                            <!-- Excerpt & Read more -->
                            <div class="card-text mt-auto">
                                <?php the_excerpt(); ?> <a class="read-more"
                                                           href="<?php the_permalink(); ?>"><?php _e('Read more »', 'decimus'); ?></a>
                            </div>
                            <!-- Tags -->
                            <?php decimus_tags(); ?>
                        </div><!-- .card-body -->
                    </div> <!-- .col -->
                </div> <!-- .row -->
            </div> <!-- .card -->


        <?php endwhile;
        wp_reset_postdata(); ?>


        <?php $myvariable = ob_get_clean();
        return $myvariable;
    }
}

// Post List Shortcode End
