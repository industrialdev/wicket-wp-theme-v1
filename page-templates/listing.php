<?php
/**
 * Template Name: Listing Page
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post(); ?>

		<?php
		if ( get_field( 'display_breadcrumb' ) ) {
			echo '<div class="container">';
			get_component( 'breadcrumbs', [] );
			echo '</div>';
		}
		?>
		<main>
			<?php the_content(); ?>
		</main>

	<?php endwhile; endif; ?>

<?php get_footer();