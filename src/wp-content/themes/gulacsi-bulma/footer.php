<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Gulacsi_Bulma
 */

?>

<footer id="colophon" class="site-footer footer">
	<aside id="secondary" class="widget-area container is-centered">
		<div class="content">
			<?php dynamic_sidebar('footer-widgets-1'); ?>
		</div>
	</aside><!-- #secondary -->


	<div class="site-info content has-text-centered">
		<a href="<?php echo esc_url(__('https://wordpress.org/', 'gulacsi-bulma')); ?>">
			<?php
			/* translators: %s: CMS name, i.e. WordPress. */
			printf(esc_html__('Proudly powered by %s', 'gulacsi-bulma'), 'WordPress');
			?>
		</a>
		<span class="sep"> | </span>
		<?php
		/* translators: 1: Theme name, 2: Theme author. */
		printf(esc_html__('Theme: %1$s by %2$s.', 'gulacsi-bulma'), 'gulacsi-bulma', '<a href="http://underscores.me/">Andras Gulacsi</a>');
		?>
	</div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>