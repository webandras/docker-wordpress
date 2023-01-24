=== bS5 Post / Page Grid / List ===

Contributors: craftwerk

Requires at least: 4.5
Tested up to: 5.6.2
Requires PHP: 5.6
Stable tag: 5.0.0.1
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Post / Page Grid / List for bootScore WordPress Theme, Copyright 2020 Bastian Kreiter.

== Installation ==

1. In your admin panel, go to Plugins > and click the Add New button.
2. Click Upload Plugin and Choose File, then select the Plugin's .zip file. Click Install Now.
3. Click Activate to use your new Plugin right away.

== Usage ==

= Posts =

Use a shortcode like this to display posts in a page:

[bs-post-grid type="post" category="sample-category" order="ASC" orderby="title" posts="12"]

[bs-post-list type="post" category="sample-category, test-category" order="DESC" orderby="date"]

Options:

category: category slug - Multiple categories separated by commas
order: ASC or DESC
orderby: date or title
posts: number of posts to display

= Pages =

Use a shortcode like this to display child pages in a page:

[bs-post-grid type="page" post_parent="413" order="ASC" orderby="title" posts="6"]

[bs-post-grid type="page" post_parent="45" order="DESC" orderby="date"]

Options:

post_parent: ID of your parent page
order: ASC or DESC
orderby: date or title
posts: number of pages to display


== Changelog ==

    = 5.0.0.1 - February 16 2021 =
    
        * [NEW] Override templates in child-theme 

    = 5.0.0.0 - February 02 2021 =
    
        * Initial release