=== bS Share Buttons ===

Contributors: craftwerk

Requires at least: 4.5
Tested up to: 5.8.1
Requires PHP: 5.6
Stable tag: 5.0.0
License: MIT License
License URI: https://github.com/bootscore/bs5-share-buttons/blob/main/LICENSE

Displays share buttons in bootScore WordPress Theme, Copyright 2020 Bastian Kreiter.

== Installation ==

1. In your admin panel, go to Plugins > and click the Add New button.
2. Click Upload Plugin and Choose File, then select the Plugin's .zip file. Click Install Now.
3. Click Activate to use your new Plugin right away.

== Usage ==

Use shortcode to display share buttons in your post, page or widget:

[bs-share-buttons]

Use shortcode to display share buttons in your .php files:

<?php echo do_shortcode("[bs-share-buttons]"); ?>

Remove buttons you do not want to display directly in main.php line 77 to 89 by deleting the respective line or override them by display: none. Use following classes:

.btn-twitter
.btn-facebook
.btn-whatsapp
.btn-pinterest
.btn-linkedin
.btn-reddit
.btn-tumblr
.btn-buffer
.btn-mix
.btn-vk
.btn-mail
.btn-print


== Changelog ==

    = 1.0.0 - January 02 2021 =
    
        * Initial release
