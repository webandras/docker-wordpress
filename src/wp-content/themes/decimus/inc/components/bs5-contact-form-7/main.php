<?php
/**
 * Plugin Name: bS5 Contact Form 7
 * Plugin URI: https://bootscore.me/plugins/bs-contact-form-7/
 * Description: Note: This is for Contact Form 7 version 5.4. If you use older version of CF7, use <a href="https://github.com/craftwerkberlin/bs5-contact-form-7/tree/2ec60be85f6bdc53420033b131fd821e3ff9fdc2">version 5.0.0.1</a>. Adds Bootstrap 5 alerts and checkboxes to Contact Form 7. It´s an additional plugin and needs <a href="https://wordpress.org/plugins/contact-form-7/">CF7</a> to work.
 * Author: Bastian Kreiter
 * Author URI: https://crftwrk.de
 * Version: 5.0.0.2
 */


//Adjust contact form 7 radios and checkboxes to match bootstrap 4 custom radio structure.
add_filter('wpcf7_form_elements', function ($content) {
    $content = preg_replace('/<label><input type="(checkbox|radio)" name="(.*?)" value="(.*?)" \/><span class="wpcf7-list-item-label">/i', '<label class="form-check form-check-inline form-check-\1"><input type="\1" name="\2" value="\3" class="form-check-input"><span class="wpcf7-list-item-label form-check-label">', $content);

    return $content;
});


// Disable Contact Form 7 Styles
add_action('wp_print_styles', 'wps_deregister_styles', 100);
function wps_deregister_styles()
{
    wp_deregister_style('contact-form-7');
}

add_action('wp_footer', 'decimus_load_session_data_for_checkout', 9999);

function decimus_load_session_data_for_checkout(): void
{
    global $wp;
    if ( is_checkout() && empty($wp->query_vars['order-pay']) && !isset($wp->query_vars['order-received']) ) {
        echo '<script>/* put data to inputs from session */
        jQuery(document).ready(function ($) {
            var userDataFromSession = sessionStorage.getItem("currentUserData");
            if (userDataFromSession) {
                userDataFromSession = JSON.parse(userDataFromSession);
                $("#billing_first_name").val(userDataFromSession.name);
                $("#billing_email").val(userDataFromSession.email);
            }
        });
      </script>';
    }
}
