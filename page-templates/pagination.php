<?php

$defaults = array(
	'classes'    => [],
	'current'    => 1,
	'total'      => 1,
	'prev_text'  => 'Previous',
	'next_text'  => 'Next',
	'prev_icon'  => [ 
		'classes' => [ 'pagination-icon' ],
		'icon'    => 'fa fa-arrow-left', // Replace with desired Font Awesome classes
		'text'    => '',
	],
	'next_icon'  => [ 
		'classes' => [ 'pagination-icon' ],
		'icon'    => 'fa fa-arrow-right', // Replace with desired Font Awesome classes
		'text'    => '',
	],
	'show_range' => true, // Whether to show a range of page numbers
);

$args       = wp_parse_args( $args, $defaults );
$classes    = $args['classes'];
$current    = $args['current'];
$total      = $args['total'];
$prev_text  = $args['prev_text'];
$next_text  = $args['next_text'];
$prev_icon  = $args['prev_icon'];
$next_icon  = $args['next_icon'];
$show_range = $args['show_range'];
$classes[]  = 'pagination';

?>

<div class="<?php echo implode( ' ', $classes ) ?>">

	<?php if ( $total > 1 ) : ?>

		<div class="pagination-inner">

			<?php if ( $current > 1 ) : ?>
				<a href="#" class="pagination-prev">
					<?php get_component( 'icon', $prev_icon ); ?>
					<?php echo esc_html( $prev_text ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $show_range ) : ?>
				<div class="pagination-pages">
					<?php
					$start = max( 1, $current - 5 );
					$end   = min( $total, $start + 10 );

					for ( $i = $start; $i <= $end; $i++ ) {
						$active_class = ( $i === $current ) ? 'active' : '';
						echo '<a href="#" class="pagination-page ' . $active_class . '">' . $i . '</a>';
					}
					?>
				</div>

			<?php else : ?>
				<span class="pagination-current">
					<?php echo esc_html( $current ); ?>
				</span>
			<?php endif; ?>

			<?php if ( $current < $total ) : ?>
				<a href="#" class="pagination-next">
					<?php get_component( 'icon', $next_icon ); ?>
					<?php echo esc_html( $next_text ); ?>
				</a>
			<?php endif; ?>

		</div>

	<?php else : ?>

		<p>No pagination needed.</p>

	<?php endif; ?>

</div>