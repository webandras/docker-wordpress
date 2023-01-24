<?php

// Remove emoji support (for optimization purposes)
add_action('init', 'decimus_child_remove_emoji');
function decimus_child_remove_emoji(): void
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove from TinyMCE
    add_filter('tiny_mce_plugins', 'decimus_child_remove_tinymce_emoji');
}


// Filter out the tinymce emoji plugin.
function decimus_child_remove_tinymce_emoji(array $plugins): array
{

    if ( !is_array($plugins) ) {
        return array();
    }

    return array_diff($plugins, array('wpemoji'));
}


// Not sure if it is useful to set some headers here
// On shared hosting, there headers cannot be modified in webserver configuration
//add_filter('wp_headers', 'decimus_child_additional_headers');
function decimus_child_additional_headers(array $headers): array
{
    if ( !is_admin() ) {

        $headers['Referrer-Policy'] = 'no-referrer-when-downgrade';
        $headers['X-Content-Type-Options'] = 'nosniff';
        $headers['X-XSS-Protection'] = '1; mode=block';
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
    }

    return $headers;
}


add_filter('excerpt_length', 'decimus_child_excerpt_length', 999);
/**
 * Filter the except length to 30 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function decimus_child_excerpt_length($length)
{
    return 30;
}


// Custom breadcrumb
if ( !function_exists('the_breadcrumb') ) {
    function the_breadcrumb()
    {
        if ( !is_home() ) {
            echo '<nav class="breadcrumb mt-3 mb-0 p-2 px-0 small rounded">';
            echo '<a href="' . home_url('/') . '">' . (
                '<i class="fas fa-home"></i>') .
                '</a><span class="divider">&nbsp;/&nbsp;</span>';
            if ( is_category() || is_single() ) {

                $category = get_the_category(' <span class="divider">&nbsp;/&nbsp;</span> ');

                if ( count($category) > 0 ) {
                    echo $category;
                }

                if ( is_single() ) {

                    if ( count($category) > 0 ) {
                        echo ' <span class="divider">&nbsp;/&nbsp;</span> ';
                    }
                    the_title();
                }
            } elseif ( is_page() ) {
                echo the_title();
            }
            echo '</nav>';
        }
    }

    add_filter('breadcrumbs', 'breadcrumbs');
}
// Breadcrumb END


// Add custom image size (with hard crop) 960*600px
add_image_size('boritokep', 960, 600, true);

