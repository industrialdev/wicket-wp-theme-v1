<?php
/**
 * Template Name: Kitchen Sink Page
 */

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post(); ?>

		<main class="py-5">
			<div class="container">
				<!--Find all files inside of the components folder and loop through them-->
				<?php foreach ( glob( get_components_dir() . '*.php' ) as $filename ) { ?>

					<?php
					// Create an array of excluded files
					$excluded_files = [ 
						'card-product.php',
						'org-search-select.php',
					];

					// If the current file is in the excluded files array, skip it
					if ( in_array( basename( $filename ), $excluded_files ) ) {
						continue;
					}
					?>
					<div class="mb-3">
						<?php get_component( basename( $filename, '.php' ) ); ?>
					</div>
				<?php } ?>

				<hr>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Primary buttons</h2>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'primary',
							'size'        => 'lg',
							'label'       => __( 'Primary large', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'primary',
							'size'        => 'lg',
							'label'       => __( 'Primary large disabled', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
							'disabled'    => true,
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'primary',
							'label'       => __( 'Primary default', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'primary',
							'size'        => 'sm',
							'label'       => __( 'Primary small', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Secondary buttons</h2>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'secondary',
							'size'        => 'lg',
							'label'       => __( 'Secondary large', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'secondary',
							'size'        => 'lg',
							'label'       => __( 'Secondary large disabled', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
							'disabled'    => true,
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'secondary',
							'label'       => __( 'Secondary default', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'secondary',
							'size'        => 'sm',
							'label'       => __( 'Secondary small', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Ghost buttons</h2>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'ghost',
							'size'        => 'lg',
							'label'       => __( 'Ghost large', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'ghost',
							'size'        => 'lg',
							'label'       => __( 'Ghost large disabled', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
							'disabled'    => true,
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'ghost',
							'label'       => __( 'Ghost default', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
					<div class="mb-3">
						<?php get_component( 'button', [ 
							'variant'     => 'ghost',
							'size'        => 'sm',
							'label'       => __( 'Ghost small', 'wicket' ),
							'prefix_icon' => 'fa fa-calendar-alt',
							'suffix_icon' => 'fa fa-external-link-alt',
						] ) ?>
					</div>
				</section>

				<section class="bg-dark-100 p-8 mb-6">
					<div class="py-3">
						<h2 class="text-heading-lg mb-3 text-white">Primary reversed</h2>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'primary',
								'size'        => 'lg',
								'label'       => __( 'Primary large', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'primary',
								'size'        => 'lg',
								'label'       => __( 'Primary large disabled', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
								'disabled'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'primary',
								'label'       => __( 'Primary default', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'primary',
								'size'        => 'sm',
								'label'       => __( 'Primary small', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>

						<h2 class="text-heading-lg my-3 text-white">Secondary reversed</h2>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'secondary',
								'size'        => 'lg',
								'label'       => __( 'Secondary large', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'secondary',
								'size'        => 'lg',
								'label'       => __( 'Secondary large disabled', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
								'disabled'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'secondary',
								'label'       => __( 'Secondary default', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'secondary',
								'size'        => 'sm',
								'label'       => __( 'Secondary small', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>

						<h2 class="text-heading-lg my-3 text-white">Ghost reversed</h2>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'ghost',
								'size'        => 'lg',
								'label'       => __( 'Ghost large', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'ghost',
								'size'        => 'lg',
								'label'       => __( 'Ghost large disabled', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
								'disabled'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'ghost',
								'label'       => __( 'Ghost default', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
						<div class="mb-3">
							<?php get_component( 'button', [ 
								'variant'     => 'ghost',
								'size'        => 'sm',
								'label'       => __( 'Ghost small', 'wicket' ),
								'prefix_icon' => 'fa fa-calendar-alt',
								'suffix_icon' => 'fa fa-external-link-alt',
								'reversed'    => true,
							] ) ?>
						</div>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Icon</h2>
					<div class="row mb-6">
						<div class="col-sm-4">
							<?php get_component( 'icon', [ 
								'classes' => [ 'custom-icon-class' ],
								'icon'    => 'fa fa-check', // Replace with the desired Font Awesome classes
								'text'    => __( 'Icon Text' ),
							] ); ?>
						</div>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Social Sharing</h2>
					<div class="mb-6">
						<?php get_component( 'social-sharing' ); ?>
					</div>
					<div class="mb-6 p-4 bg-dark-100">
						<?php get_component( 'social-sharing', [ 
							'reversed' => true,
						] ); ?>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Social Media Links</h2>
					<div class="row mb-6">
						<div class="col-sm-4">
							<?php get_component( 'social-links', [ 
								'variant' => 'default' // 'default' or 'reversed'
							] ); ?>
						</div>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Tag</h2>
					<div class="mb-6">
						<div class="mb-4">
							<?php get_component( 'tag', [ 
								'label' => __( 'Members Only', 'wicket' ),
								'icon'  => 'fa-regular fa-lock',
							] ); ?>
						</div>
						<div class="mb-4">
							<?php get_component( 'tag', [ 
								'label'    => __( 'Members Only', 'wicket' ),
								'icon'     => 'fa-regular fa-lock',
								'reversed' => true,
							] ); ?>
						</div>
						<div class="mb-4">
							<?php get_component( 'tag', [ 
								'label' => __( '', 'wicket' ),
								'icon'  => 'fa-regular fa-lock',
							] ); ?>
						</div>
						<div class="mb-4">
							<?php get_component( 'tag', [ 
								'label'    => __( '', 'wicket' ),
								'icon'     => 'fa-regular fa-lock',
								'reversed' => true,
							] ); ?>
						</div>
						<div class="mb-4">
							<?php get_component( 'tag', [ 
								'label' => __( 'Topic tag', 'wicket' ),
								'link'  => 'https://wicket.io/',
								'icon'  => '',
							] ); ?>
						</div>
					</div>
				</section>

				<section class="py-8">
					<h2 class="text-heading-lg mb-3">Link</h2>
					<div class="mb-6">
						<div class="mb-4">
							<?php get_component( 'link', [ 
								'url' => '#',
							] ); ?>
						</div>
						<div class="mb-4">
							<?php get_component( 'link', [ 
								'url'        => '#',
								'text'       => 'Link label',
								'icon_start' => [ 
									'icon' => 'fa-regular fa-suitcase',
									'text' => 'Icon text',
								],
								'icon_end'   => [ 
									'icon' => 'fa-solid fa-arrow-right',
									'text' => 'Icon text',
								],
							] ); ?>
						</div>
						<div class="bg-dark-100 p-8">
							<div class="mb-4">
								<?php get_component( 'link', [ 
									'reversed'   => true,
									'url'        => '#',
									'text'       => 'Reversed link',
									'icon_start' => [ 
										'icon' => 'fa-regular fa-suitcase',
										'text' => 'Icon text',
									],
									'icon_end'   => [ 
										'icon' => 'fa-solid fa-arrow-right',
										'text' => 'Icon text',
									],
								] ); ?>
							</div>
						</div>
					</div>
				</section>

				<section class="p-8 bg-light-020">
					<h2 class="text-heading-lg mb-3">Listing Card</h2>
					<?php
					$args = array(
						'post_type'      => 'listing',
						'posts_per_page' => 2,
						'orderby'        => 'date',
						'order'          => 'DESC',
					);

					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							$post_type      = get_post_type_object( get_post_type() );
							$title          = get_the_title();
							$excerpt        = get_the_excerpt();
							$post_date      = get_the_date( 'F j, Y' );
							$featured_image = get_post_thumbnail_id();
							$member_only    = is_member_only( get_the_ID() );

							get_component( 'card-listing', [ 
								'classes'        => [ 'mb-6' ],
								'content_type'   => $post_type->label,
								'title'          => $title,
								'excerpt'        => $excerpt,
								'date'           => $post_date,
								'featured_image' => $featured_image,
								'topics'         => [],
								'link'           => [ 
									'url'    => '#',
									'text'   => 'Go somewhere',
									'target' => '_self',
								],
								'member_only'    => $member_only,
							] );
						}
						wp_reset_postdata();
					}

					?>
				</section>

			</div>
		</main>

	<?php endwhile; endif; ?>

<?php get_footer(); ?>