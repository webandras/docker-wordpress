<?php

// style and scripts
add_action('wp_enqueue_scripts', 'decimus_child_enqueue_styles');
function decimus_child_enqueue_styles(): void
{
    // style.css from the parent theme
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // custom.js
    wp_enqueue_script('slick-slider', get_stylesheet_directory_uri() . '/js/slick.min.js', false, '', true);
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', false, '', true);

    // only compile to css in dev env
    if ( WP_ENV === 'development' ) {
        require_once get_stylesheet_directory() . '/_develop/scss-compiler.php';
        decimus_compile_scss();
    }

}


// Dequeue parent bootstrap.min.css and enqueue child
add_action('wp_enqueue_scripts', 'decimus_child_remove_scripts', 20);
function decimus_child_remove_scripts(): void
{
    $global_request = new WP_REST_Request('GET', '/decimus/v1/frontend/global');
    $global_response = rest_do_request($global_request);
    $global_data = rest_get_server()->response_to_data($global_response, true);

    // check if we received the data from the endpoint
    $have_global_data = isset($global_data) && isset($global_data['data']);
    $global_options = $have_global_data ? $global_data['data']['option_value'] : [];

    $skin = $have_global_data ? esc_attr($global_options['skin']) : 'lux';

    // Dequeue parent bootstrap.min.css
    wp_dequeue_style('bootstrap');
    wp_deregister_style('bootstrap');

    // Register your child bootstrap.min.css (from the bootswatch theme's pre-compiled bundle)
    wp_enqueue_style('child-theme-bootstrap', get_stylesheet_directory_uri() . '/css/lib/' . $skin . '.css', array('parent-style'));
}


// Theme and WooCommerce hooks and functions
require_once get_template_directory() . '/woocommerce/woocommerce-functions.php';
require_once get_stylesheet_directory() . '/inc/theme.php';
require_once get_stylesheet_directory() . '/inc/woocommerce.php';


//add_action('wp_footer', 'decimus_child_add_google_analytics');
function decimus_child_add_google_analytics()
{
    $html = '<!-- Global site tag (gtag.js) - Google Analytics -->';
    $html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-170495324-1"></script>';
    $html .= '<script>';
    $html .= 'window.dataLayer = window.dataLayer || [];';
    $html .= 'function gtag() {';
    $html .= 'dataLayer.push(arguments);';
    $html .= '}';
    $html .= 'gtag("js", new Date());';
    $html .= 'gtag("config", "UA-170495324-1");';
    $html .= '</script>';

    echo $html;
}




