<?php

if ( !function_exists('decimus_setup') ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function decimus_setup()
    {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on Decimus, use a find and replace
         * to change 'decimus' to the name of your theme in all the template files.
         */
        load_theme_textdomain('decimus', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

    }
endif;
add_action('after_setup_theme', 'decimus_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function decimus_content_width(): void
{
    // This variable is intended to be overruled from themes.
    // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
    // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
    $GLOBALS['content_width'] = apply_filters('decimus_content_width', 640);
}

add_action('after_setup_theme', 'decimus_content_width', 0);


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
// Widgets
if ( !function_exists('decimus_widgets_init') ) :

    function decimus_widgets_init()
    {

        // Top Nav
        register_sidebar(array(
            'name' => esc_html__('Top Nav', 'decimus'),
            'id' => 'top-nav',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="ms-3">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-title d-none">',
            'after_title' => '</div>'
        ));
        // Top Nav End

        // Top Nav Search
        register_sidebar(array(
            'name' => esc_html__('Top Nav Search', 'decimus'),
            'id' => 'top-nav-search',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="top-nav-search">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-title d-none">',
            'after_title' => '</div>'
        ));
        // Top Nav Search End

        // Sidebar
        register_sidebar(array(
            'name' => esc_html__('Sidebar', 'decimus'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<section id="%1$s" class="widget %2$s card card-body mb-1 border-0">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title card-title border-bottom py-2">',
            'after_title' => '</h2>',
        ));
        // Sidebar End

        // Top Footer
        register_sidebar(array(
            'name' => esc_html__('Top Footer', 'decimus'),
            'id' => 'top-footer',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="footer_widget mb-5">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>'
        ));
        // Top Footer End

        // Footer 1
        register_sidebar(array(
            'name' => esc_html__('Footer 1', 'decimus'),
            'id' => 'footer-1',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="footer_widget mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title h4">',
            'after_title' => '</h2>'
        ));
        // Footer 1 End

        // Footer 2
        register_sidebar(array(
            'name' => esc_html__('Footer 2', 'decimus'),
            'id' => 'footer-2',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="footer_widget mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title h4">',
            'after_title' => '</h2>'
        ));
        // Footer 2 End

        // Footer 3
        register_sidebar(array(
            'name' => esc_html__('Footer 3', 'decimus'),
            'id' => 'footer-3',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="footer_widget mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title h4">',
            'after_title' => '</h2>'
        ));
        // Footer 3 End

        // Footer 4
        register_sidebar(array(
            'name' => esc_html__('Footer 4', 'decimus'),
            'id' => 'footer-4',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="footer_widget mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title h4">',
            'after_title' => '</h2>'
        ));
        // Footer 4 End

        // 404 Page
        register_sidebar(array(
            'name' => esc_html__('404 Page', 'decimus'),
            'id' => '404-page',
            'description' => esc_html__('Add widgets here.', 'decimus'),
            'before_widget' => '<div class="mb-4">',
            'after_widget' => '</div>',
            'before_title' => '<h1 class="widget-title">',
            'after_title' => '</h1>'
        ));
        // 404 Page End

    }

    add_action('widgets_init', 'decimus_widgets_init');


endif;
// Widgets END


// Shortcode in HTML-Widget
add_filter('widget_text', 'do_shortcode');
// Shortcode in HTML-Widget End

// Amount of posts/products in category
if ( !function_exists('decimus_wpsites_query') ) :

    function decimus_wpsites_query($query)
    {
        if ( $query->is_archive() && $query->is_main_query() && !is_admin() ) {
            $query->set('posts_per_page', 24);
        }
    }

    add_action('pre_get_posts', 'decimus_wpsites_query');

endif;
// Amount of posts/products in category END


// Pagination Categories
function decimus_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;
    global $paged;
    if ( $pages == '' ) {
        global $wp_query;
        $pages = $wp_query->max_num_pages;

        if ( !$pages )
            $pages = 1;
    }

    if ( 1 != $pages ) {
        echo '<nav aria-label="' . __('Page navigation', 'decimus') . '" role="navigation">';
        echo '<span class="sr-only">' . __('Page navigation', 'decimus') . '</span>';
        echo '<ul class="pagination justify-content-center ft-wpbs mb-4">';


        if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages )
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(1) . '" aria-label="First Page">&laquo;</a></li>';

        if ( $paged > 1 && $showitems < $pages )
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="Previous Page">&lsaquo;</a></li>';

        for ($i = 1; $i <= $pages; $i++) {
            if ( 1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems) )
                echo ($paged == $i) ? '<li class="page-item active"><span class="page-link"><span class="sr-only">Current Page </span>' . $i . '</span></li>' : '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '"><span class="sr-only">Page </span>' . $i . '</a></li>';
        }

        if ( $paged < $pages && $showitems < $pages )
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(($paged === 0 ? 1 : $paged) + 1) . '" aria-label="Next Page">&rsaquo;</a></li>';

        if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages )
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($pages) . '" aria-label="Last Page">&raquo;</a></li>';

        echo '</ul>';
        echo '</nav>';
        // Uncomment this if you want to show [Page 2 of 30]
        // echo '<div class="pagination-info mb-5 text-center">[ <span class="text-muted">Page</span> '.$paged.' <span class="text-muted">of</span> '.$pages.' ]</div>';
    }
}

//Pagination Categories END


// Pagination Buttons Single Posts
add_filter('next_post_link', 'post_link_attributes');
add_filter('previous_post_link', 'post_link_attributes');

function post_link_attributes($output)
{
    $code = 'class="page-link"';
    return str_replace('<a href=', '<a ' . $code . ' href=', $output);
}

// Pagination Buttons Single Posts END


// Excerpt to pages
add_post_type_support('page', 'excerpt');
// Excerpt to pages END

// Add <link rel=preload> to Fontawesome
add_filter('style_loader_tag', 'decimus_style_loader_tag');


function decimus_style_loader_tag($tag)
{

    $tag = preg_replace("/id='font-awesome-css'/", "id='font-awesome-css' online=\"if(media!='all')media='all'\"", $tag);

    return $tag;
}

// Add <link rel=preload> to Fontawesome END


// Breadcrumb
if ( !function_exists('the_breadcrumb') ) :
    function the_breadcrumb()
    {
        if ( !is_home() ) {
            echo '<nav class="breadcrumb mb-4 mt-2 bg-light py-2 px-3 small rounded">';
            echo '<a href="' . home_url('/') . '">' . ('<i class="fas fa-home"></i>') . '</a><span class="divider">&nbsp;/&nbsp;</span>';
            if ( is_category() || is_single() ) {
                the_category(' <span class="divider">&nbsp;/&nbsp;</span> ');
                if ( is_single() ) {
                    echo ' <span class="divider">&nbsp;/&nbsp;</span> ';
                    the_title();
                }
            } elseif ( is_page() ) {
                echo the_title();
            }
            echo '</nav>';
        }
    }

    add_filter('breadcrumbs', 'breadcrumbs');
endif;
// Breadcrumb END


// Comment Button
function decimus_comment_form($args)
{
    $args['class_submit'] = 'btn btn-outline-primary'; // since WP 4.1
    return $args;
}

add_filter('comment_form_defaults', 'decimus_comment_form');
// Comment Button END


// Password protected form
function decimus_pw_form()
{
    $output = '
		  <form action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post" class="form-inline">' . "\n"
        . '<input name="post_password" type="password" size="" class="form-control me-2 my-1" placeholder="' . __('Password', 'decimus') . '"/>' . "\n"
        . '<input type="submit" class="btn btn-outline-primary my-1" name="Submit" value="' . __('Submit', 'decimus') . '" />' . "\n"
        . '</p>' . "\n"
        . '</form>' . "\n";
    return $output;
}

add_filter("the_password_form", "decimus_pw_form");
// Password protected form END


// Allow HTML in term (category, tag) descriptions
foreach (array('pre_term_description') as $filter) {
    remove_filter($filter, 'wp_filter_kses');
    if ( !current_user_can('unfiltered_html') ) {
        add_filter($filter, 'wp_filter_post_kses');
    }
}

foreach (array('term_description') as $filter) {
    remove_filter($filter, 'wp_kses_data');
}
// Allow HTML in term (category, tag) descriptions END


// Allow HTML in author bio
remove_filter('pre_user_description', 'wp_filter_kses');
add_filter('pre_user_description', 'wp_filter_post_kses');
// Allow HTML in author bio END


// Hook after #primary
function bs_after_primary()
{
    do_action('bs_after_primary');
}

// Hook after #primary END


// Open links in comments in new tab
if ( !function_exists('bs_comment_links_in_new_tab') ) :
    function bs_comment_links_in_new_tab($text)
    {
        return str_replace('<a', '<a target="_blank" rel=”nofollow”', $text);
    }

    add_filter('comment_text', 'bs_comment_links_in_new_tab');
endif;
// Open links in comments in new tab END


// Disable Gutenberg blocks in widgets (WordPress 5.8)
// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter('gutenberg_use_widgets_block_editor', '__return_false');
// Disables the block editor from managing widgets.
add_filter('use_widgets_block_editor', '__return_false');
// Disable Gutenberg blocks in widgets (WordPress 5.8) END

