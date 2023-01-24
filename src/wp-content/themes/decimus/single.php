<?php
/*
 * Template Post Type: post
 */

get_header(); ?>

<?php
$global_request = new WP_REST_Request('GET', '/decimus/v1/frontend/global');
$global_response = rest_do_request($global_request);
$global_data = rest_get_server()->response_to_data($global_response, true);

// check if we received the data from the endpoint
$have_global_data = isset($global_data) && isset($global_data['data']);
$global_options = $have_global_data ? $global_data['data']['option_value'] : [];

$enable_blog_sidebar = isset($global_options['enable_blog_sidebar']) ? intval($global_options['enable_blog_sidebar']) : 0;

?>

<div id="content" class="site-content container-fluid side-padding narrow-content py-5 mt-3">
    <div id="primary" class="content-area">

        <?php if (!$enable_blog_sidebar) { ?>
        <div class="row">
        <div class="col-md-10 offset-md-1 col-xxl-8 offset-xxl-2">
        <?php } ?>

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <?php the_breadcrumb(); ?>

        <?php if (!$enable_blog_sidebar) { ?>
        </div>
        </div>
        <?php } ?>

        <div class="row">
            <?php if ($enable_blog_sidebar) { ?>
            <div class="col-md-8 col-xxl-9">
            <?php } else { ?>
            <div class="col-md-10 offset-md-1 col-xxl-8 offset-xxl-2">
            <?php } ?>
                <main id="main" class="site-main">

                    <header class="entry-header">
                        <?php the_post(); ?>
                        <?php decimus_category_badge(); ?>
                        <?php the_title('<h1>', '</h1>'); ?>
                        <p class="entry-meta">
                            <small class="text-muted">
                                <?php
                                decimus_date();
                                _e(' by ', 'decimus');
                                the_author_posts_link();
                                decimus_comment_count();
                                ?>
                            </small>
                        </p>
                        <?php decimus_post_thumbnail(); ?>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <footer class="entry-footer clear-both">
                        <div class="mb-4">
                            <?php decimus_tags(); ?>
                        </div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <?php previous_post_link('%link'); ?>
                                </li>
                                <li class="page-item">
                                    <?php next_post_link('%link'); ?>
                                </li>
                            </ul>
                        </nav>
                    </footer>

                    <?php comments_template(); ?>

                </main> <!-- #main -->

            </div><!-- col -->
            <?php if ($enable_blog_sidebar) { ?>
                <?php get_sidebar(); ?>
            <?php } ?>
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->

<?php get_footer(); ?>
