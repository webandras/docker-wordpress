<?php
/*
 * Grid template.
 *
 * This template can be overriden by copying this file to your-theme/bs5-post-page-grid-list/grid.php
 *
 * @author 		Bastian Kreiter
 * @package 	bS5 Post/Page Grid/List
 * @version     1.0.0

Post/Page Grid Shortcode 
Posts: [bs-post-grid type="post" category="documentation, category-default" order="ASC" orderby="title" posts="6"]
Pages: [bs-post-grid type="page" post_parent="413" order="ASC" orderby="title" posts="6"]
*/


// Post Grid Shortcode
add_shortcode('bs-post-grid', 'decimus_post_grid');
function decimus_post_grid($atts)
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


        <div class="row">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">
                    <div class="card h-100">
                        <!-- Featured Image-->
                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>

                        <div class="card-body d-flex flex-column">

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
                            <div class="card-text">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="mt-auto">
                                <a class="read-more"
                                   href="<?php the_permalink(); ?>"><?php _e('Read more »', 'decimus'); ?></a>
                            </div>
                            <!-- Tags -->
                            <?php decimus_tags(); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </div>

        <?php $myvariable = ob_get_clean();
        return $myvariable;
    }
}

// Post Grid Shortcode End
