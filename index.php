<?php get_header(); ?>

<main class="my-5" id="main-content">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<?php if ( is_singular() ) : ?>
				<h1 class="text-heading-md bold mb-4">
					<?= get_the_title(); ?>
				</h1>
			<?php elseif ( is_tax() ) : ?>
				<h1 class="text-heading-md bold mb-4">
					<?= single_term_title(); ?>
				</h1>
			<?php else : ?>
				<h1 class="text-heading-md bold mb-4">
					<?= get_post_type_object( get_post_type() )->labels->archives; ?>
				</h1>
			<?php endif; ?>

			<?php while ( have_posts() ) :
				the_post();
				$post_id              = get_the_ID();
				$post_type            = get_post_type();
				$related_content_type = get_related_content_type( $post_type );
				$content_type         = ! is_wp_error( get_the_terms( $post_id, $related_content_type ) ) ? get_the_terms( $post_id, $related_content_type ) : [];
				$listing_types        = get_the_terms( get_the_ID(), 'listing-type' );
				$related_topic_type   = get_related_topic_type( get_post_type( $post_id ) );
				$topics               = get_the_terms( $post_id, $related_topic_type );

				get_component( 'card-listing', [ 
					'classes'        => [ 'mb-6' ],
					'content_type'   => $content_type[0]->name,
					'title'          => get_the_title(),
					'excerpt'        => get_the_excerpt(),
					'date'           => get_the_date( 'F j, Y' ),
					'featured_image' => get_post_thumbnail_id(),
					'link'           => [ 
						'url'    => get_the_permalink(),
						'text'   => 'Read more',
						'target' => '_self',
					],
					'member_only'    => is_member_only( get_the_ID() ),
					'topics'         => $topics,
				] );
				?>
			<?php endwhile; ?>
			<?php the_wicket_pagination(); ?>
		<?php else : ?>
			<?php
			$postType = get_post_type();
			$notFound = "No pages found.";
			if ( $postType ) {
				$notFound = get_post_type_object( get_post_type() )->labels->not_found;
			}
			?>
			<h2>
				<?php echo $notFound; ?>
			</h2>
			<p><i class="fal fa-map-marked-alt fa-9x" aria-hidden="true"></i></p>
			<p><a href="/" class="button button--primary">Go home</a></p>
		<?php endif; ?>
	</div>
</main>

<?php get_footer();
