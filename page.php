<?php get_header(); ?>

<?php
$wrapper_classes     = [];
$dev_wrapper_classes = get_field( 'page_wrapper_class' );
if ( ! empty ( $dev_wrapper_classes ) ) {
	$wrapper_classes[] = $dev_wrapper_classes;
}
$display_breadcrumb   = get_field( 'display_breadcrumb' );
$display_publish_date = get_field( 'display_publish_date' );
?>

<?php if ( have_posts() ) :
	while ( have_posts() ) :
		the_post(); ?>

		<?php
		if ( $display_breadcrumb ) {
			echo '<div class="wp-block-breadcrumbs">'; // Having the `wp-block-` prefix will help align it with the other Blocks
			get_component( 'breadcrumbs', [] );
			echo '</div>';
		}
		if ( $display_publish_date ) {
			echo '<div class="wp-block-published-date">';
			echo "<p class='mt-3 mb-4'><strong>" . __( 'Published:', 'wicket' ) . ' ' . get_the_date( 'd-m-Y' ) . "</strong></p>";
			echo '</div>';
		}
		?>
		<main class="<?php echo implode( ' ', $wrapper_classes ) ?>" id="main-content">
			<?php the_content(); ?>
		</main>

	<?php endwhile; endif; ?>

<?php get_footer();
