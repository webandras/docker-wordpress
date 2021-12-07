<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gulacsi_Bulma
 */

if (!is_active_sidebar('sidebar-1')) {
	return;
}
?>

<aside id="secondary" class="widget-area <?php echo (is_home() && !is_front_page()) ? "content" : "container is-fluid"; ?>">
	<div class="content">
		<?php dynamic_sidebar('sidebar-1'); ?>
	</div>
</aside><!-- #secondary -->