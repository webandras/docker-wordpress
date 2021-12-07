<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gulacsi_Bulma
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'gulacsi-bulma'); ?></a>

		<header id="masthead" class="site-header">
			<!-- #site-navigation -->
			<nav id="site-navigation" class="navbar" role="navigation" aria-label="main navigation">
				<div class="navbar-brand">
					<a class="navbar-item" href="<?php echo esc_url(home_url('/')); ?>">
						<?php the_custom_logo(); ?>
					</a>

					<a role="button" class="navbar-burger" aria-label="<?php esc_html_e('Primary Menu', 'gulacsi-bulma'); ?>" aria-expanded="false" data-target="primary-navbar" aria-controls="primary-menu" aria-expanded="false">
						<span aria-hidden="true"></span>
						<span aria-hidden="true"></span>
						<span aria-hidden="true"></span>
					</a>
				</div>

				<?php
				/* get menu items for primary navbar */
				$menuName = 'menu-1';
				if (($locations = get_nav_menu_locations()) && isset($locations[$menuName])) {
					$menu = wp_get_nav_menu_object($locations[$menuName]);
					$menuItems = wp_get_nav_menu_items($menu->term_id);
				}
				// Get the queried object and sanitize it
				$currentPage = sanitize_post($GLOBALS['wp_the_query']->get_queried_object());
				// Get the page slug
				$currentPageSlug = $currentPage ? $currentPage->post_name : null;
				$currentPageUrl = esc_url(get_bloginfo('url') . '/' . ($currentPageSlug ? $currentPageSlug : '') . '/');
				?>
				<div id="primary-navbar" class="navbar-menu">
					<div class="navbar-start">
						<?php
						if ($menuItems) {
							foreach ($menuItems as $item) {
								$url = $item->url;
								$title = $item->title;
						?>
								<?php if (strtolower($item->post_name) === 'home' && (is_home() || is_front_page())) { ?>
									<a href="<?php echo esc_url($url); ?>" class="navbar-item is-active">
										<?php esc_html_e($title) ?>
									</a>
								<?php } else { ?>
									<a href="<?php echo esc_url($url); ?>" class="navbar-item<?php echo ($currentPageUrl === $url) ? " is-active" : ""; ?>">
										<?php esc_html_e($title) ?>
									</a>
								<?php } ?>
							<?php	} ?>
						<?php	} ?>
						<div class="navbar-item has-dropdown is-hoverable">
							<a class="navbar-link">
								More
							</a>

							<div class="navbar-dropdown">
								<a class="navbar-item">
									About
								</a>
								<a class="navbar-item">
									Jobs
								</a>
								<a class="navbar-item">
									Contact
								</a>
								<hr class="navbar-divider">
								<a class="navbar-item">
									Report an issue
								</a>
							</div>
						</div>
					</div>

					<div class="navbar-end">
						<div class="navbar-item">
							<div class="buttons">
								<a class="button is-primary">
									<strong>Sign up</strong>
								</a>
								<a href="<?php esc_html_e(wp_login_url()); ?>" class="button is-light">
									Log in
								</a>
							</div>
						</div>
					</div>
				</div>
			</nav><!-- #site-navigation -->
		</header><!-- #masthead -->