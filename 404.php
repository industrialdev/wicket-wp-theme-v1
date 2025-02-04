<?php get_header(); ?>

<main id="main-content">
	<section id="page-content">
		<div class="container">
			<h1>404</h1>
			<h2><?php _e('Page not found', 'wicket'); ?></h2>
			<p><?php _e("The page you're looking for cannot be found", 'wicket'); ?></p>
			<?php get_component( 'link', [
				'variant' => 'primary',
				'text'    => __( 'Go back to the homepage', 'wicket' ),
				'url'    => home_url(),
			] ) ?>
		</div>
	</div>
</main>

<?php get_footer();
