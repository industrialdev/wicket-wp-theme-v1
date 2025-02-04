<?php get_header();

use Wicket\Blocks\Wicket_Listing;

$title            = get_post_type_object( get_post_type() )->labels->archives;
$post_type        = '';
$taxonomy_filters = [];

if ( is_post_type_archive() ) {
	$title     = get_post_type_object( get_post_type() )->labels->archives;
	$post_type = get_post_type_object( get_post_type() )->name;
} elseif ( is_tax() ) {
	$title = single_term_title();
} elseif ( is_search() ) {
	$title = 'Search results for: ' . get_search_query();
} elseif ( is_singular() ) {
	$title = get_the_title();
}
?>

<main id="main-content">
	<?php
	get_component( 'banner', [
		'title'            => $title,
		'show_breadcrumbs' => true,
		'classes'          => [ 'mb-0' ],
	] );

	$block['post_type'] = $post_type;
	echo Wicket_Listing\init( $block );
	?>

</main>

<?php get_footer();
