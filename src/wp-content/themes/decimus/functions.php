<?php
/**
 * Decimus functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Decimus
 */

if ( !defined('ABSPATH') ) {
    exit;
}


// Register Bootstrap 5 Nav Walker
if ( !function_exists('decimus_register_navwalker') ) :
    function decimus_register_navwalker()
    {
        require_once('inc/class-bootstrap-5-navwalker.php');
        // Register Menus
        register_nav_menu('main-menu', 'Main menu');
        register_nav_menu('footer-menu', 'Footer menu');
    }
endif;
add_action('after_setup_theme', 'decimus_register_navwalker');
// Register Bootstrap 5 Nav Walker END


// Register Comment List
if ( !function_exists('decimus_register_comment_list') ) :
    function decimus_register_comment_list()
    {
        // Register Comment List
        require_once('inc/comment-list.php');
    }
endif;
add_action('after_setup_theme', 'decimus_register_comment_list');
// Register Comment List END


//Enqueue scripts and styles
function decimus_scripts(): void
{

    // Get modification time. Enqueue files with modification date to prevent browser from loading cached scripts and styles when file content changes. 
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/lib/bootstrap.min.css'));
    $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/style.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/css/lib/fontawesome.min.css'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/theme.js'));
    $modificated = date('YmdHi', filemtime(get_template_directory() . '/js/lib/bootstrap.bundle.min.js'));

    // Style CSS
    wp_enqueue_style('decimus-style', get_stylesheet_uri(), array(), $modificated);

    // Bootstrap
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/lib/bootstrap.min.css', array(), $modificated);

    // Fontawesome
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/css/lib/fontawesome.min.css', array(), $modificated);

    // Contact form styles
    wp_enqueue_style('bs5-contactform-style', get_template_directory_uri() . '/inc/components/bs5-contact-form-7/css/contactform-style.css', array(), $modificated);

    // Bootstrap JS
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/lib/bootstrap.bundle.min.js', array(), $modificated, true);

    // Contact form script
    wp_enqueue_script('bs5-contactform', get_template_directory_uri() . '/inc/components/bs5-contact-form-7/js/contactform-script.js', array(), $modificated, true);

    // Theme JS
    wp_enqueue_script('decimus-script', get_template_directory_uri() . '/js/theme.js', array(), $modificated, true);

    if ( is_singular() && comments_open() && get_option('thread_comments') ) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'decimus_scripts');
//Enqueue scripts and styles END


/**
 * Theme settings
 */
require_once get_template_directory() . '/inc/theme.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom components
 */
require_once get_template_directory() . '/inc/components.php';

/**
 * Custom widgets
 */
require_once get_template_directory() . '/inc/widgets/post-archives.php';
require_once get_template_directory() . '/inc/widgets/post-categories.php';
require_once get_template_directory() . '/inc/widgets/post-tags.php';
require_once get_template_directory() . '/inc/widgets/recent-posts.php';


/**
 * @param array $args
 * @return string
 *
 * args:
 * $lowercase === 0|1
 * $uppercase === 0|1
 * $numbers === 0|1
 * $symbols === 0|1
 * $pwd_length
 */
function generate_safe_password(array $args): string
{

    extract($args);

    if (
        $lowercase === 0 &&
        $uppercase === 0 &&
        $numbers === 0 &&
        $symbols === 0
    ) {
        return '';
    }

    // simple error handling
    if ( !is_numeric($pwd_length) && is_integer($pwd_length) ) {
        wp_die('Password length argument should be an integer!');
    }

    // $pwd_length = filter_var($pwd_length, FILTER_VALIDATE_INT);
    $pwd_length = filter_var($pwd_length, FILTER_SANITIZE_NUMBER_INT);

    // small letters
    $lowercaseChars = 'abcdefghijklmnopqrstuvwxyz';

    // CAPITAL LETTERS
    $uppercaseChars = strtoupper($lowercaseChars);

    // numerics
    $numberChars = '1234567890';

    // special characters
    $symbolChars = '`~!@#$%^&*()-_=+]}[{;:,<.>/?\'"\|';

    $charset = '';

    // Contains specific character groups
    if ( $lowercase ) {
        $charset .= $lowercaseChars;
    }
    if ( $uppercase ) {
        $charset .= $uppercaseChars;
    }
    if ( $numbers ) {
        $charset .= $numberChars;
    }
    if ( $symbols ) {
        $charset .= $symbolChars;
    }

    // store password
    $password = '';

    // Loop until the preferred length reached
    for ($i = 0; $i < $pwd_length; $i++) {
        // get randomized length with cryptographically secure integers
        $_rand = random_int(0, strlen($charset) - 1);

        // returns part of the string
        $password .= substr($charset, $_rand, 1);
    }

    return $password;
}

/**
 *
 */
function generate_unique_filename($filename_length): string
{
    return generate_safe_password([
            'pwd_length' => $filename_length,
            'lowercase' => 1,
            'uppercase' => 1,
            'numbers' => 1,
            'symbols' => 0,
        ]) . time();
}
