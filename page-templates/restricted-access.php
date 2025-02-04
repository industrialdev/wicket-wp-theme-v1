<?php /* Template Name: Restricted Access */ ?>
<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php $has_sidebar = false; ?>
	<main id="main-content">
		<section id="page-content">
			<div class="container">
				<?php if ($post->post_parent || $sidebar_content) : ?>
					<?php $has_sidebar = true; ?>
					<div class="grid">
						<div class="grid__col--md-8">
							<h1><?php the_title(); ?></h1>
							<?php the_content(); ?>
						</div>
						<div class="grid__col--md-4 sidebar"> 
							<?php if ($sidebar_content) : ?>
								<hr>
								<?= $sidebar_content; ?>
							<?php endif; ?>
						</div>
					</div>
				<?php else : ?>
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
					<?php $locale = ICL_LANGUAGE_CODE == 'fr' ? '&locale=fr' : '&locale=en'; ?>
					<?php $referrer = isset($_GET['referrer']) ? WP_HOME.$_GET['referrer'].$locale : home_url($wp->request, 'https').'/'.$locale; ?>

					<?php get_component( 'link', [
						'variant' => 'primary',
						'text'    => __( 'Login', 'wicket' ),
						'url'    => get_option('wp_cassify_base_url').'login?service='.$referrer,
					] ) ?>

					<?php get_component( 'link', [
						'variant' => 'secondary',
						'text'    => __( 'Create an Account', 'wicket' ),
						'url'    => '/create-account',
					] ) ?>

				<?php endif; ?>
			</div>
		</section>
	</main>

<?php endwhile; endif; ?>

<?php get_footer();
