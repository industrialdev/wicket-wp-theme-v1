<?php 

// See https://developer.wordpress.org/reference/hooks/pre_get_posts/ for reference
add_action( 'pre_get_posts', function( $query ) {

  // Check that it is the query we want to change: front-end search query
  if( $query->is_main_query() && ! is_admin() && $query->is_search() ) {

    $options_group           = get_field( 'search_listing_page', 'option' );

    // Filter configs
    $excluded_post_types     = $options_group['excluded_post_types'] ?? '';
    $excluded_post_ids       = $options_group['excluded_post_ids'] ?? '';
    $posts_per_page          = $options_group['posts_per_page'] ?? 10;
    $taxonomy_filters_field  = $options_group['taxonomy_filters'] ?? '';
    $post_type_filters_field  = $options_group['post_type_filters'] ?? '';

    // URL parameters
    $paged       = $_GET['paged'] ?? 1;

    // Set the page sorting based on parameter
    $order = 'DESC';
    if( isset( $_GET['sortby'] ) ) {
      if( $_GET['sortby'] == 'old-to-new' ) {
        $order = 'ASC';
      }
    }

    // Set excluded post types
    $excluded_post_types .= ',acf-field'; // Add the post_types we never want to see
    $excluded_post_types = explode( ',', $excluded_post_types );
    $all_post_types = get_post_types();
    $post_types = array_keys( array_diff( $all_post_types, $excluded_post_types ) );

    // Set filtered post types
    $filtered_post_types = $post_type_filters_field === '' ? [] : explode(',', $post_type_filters_field);
    if ( ! empty( $filtered_post_types ) ) {
      if( isset( $_GET['post_type'] ) && is_array( $_GET['post_type'] ) ) {
        // Only keep the post types that are specified in the filter settings
        $post_types = array_intersect( $filtered_post_types, $_GET['post_type'] );
      }
    }

    // Set excluded post IDs
    $excluded_post_ids = explode( ',', $excluded_post_ids );

    // Create taxonomy filters array
    $taxonomy_filters_field = explode( ',', $taxonomy_filters_field );
    $taxonomy_filters = [];
    foreach( $taxonomy_filters_field as $taxonomy ) {
      $taxonomy_filters[] = [
        'slug'    => $taxonomy,
        'tooltip' => '',
      ];
    }

    // Loop through each taxonomy, check if it is a URL param, and build the tax_query based on those
    $tax_query = [ 
      'relation' => 'AND',
    ];

    foreach( $taxonomy_filters_field as $taxonomy_slug ) {
      if( isset( $_GET[$taxonomy_slug] ) ) {
        $taxonomy_args = [ 
          'taxonomy' => $taxonomy_slug,
          'field'    => 'slug',
          'operator' => 'IN',
          'terms'    => $_GET[$taxonomy_slug],
        ];
        array_push( $tax_query, $taxonomy_args );
      }
    }

    // Change the query parameters
    $query->set( 'post_type', $post_types );
    $query->set( 'posts_per_page', $posts_per_page );
    $query->set( 'paged', $paged );
    $query->set( 'orderby', 'date' );
    $query->set( 'order', $order );
    $query->set( 'post__not_in', $excluded_post_ids );
    $query->set( 'tax_query', $tax_query );
    $query->set( 'posts_per_page', $posts_per_page );

    /* Add start date and end date to tax query if they are set */
    if ( isset( $_GET['start_date'] ) && isset( $_GET['end_date'] ) ) {
      $start_date = $_GET['start_date'];
      $end_date   = $_GET['end_date'];

      $query->set( 'date_query', [ 
        'after'     => $start_date,
        'before'    => $end_date,
        'inclusive' => true,
      ] );
    }

  }

} );