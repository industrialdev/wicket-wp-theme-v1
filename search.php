<?php get_header(); ?>

<?php 

  $options_group           = get_field( 'search_listing_page', 'option' );
  //wicket_write_log($options_group, true);

  $show_search_bar         = $options_group['show_search_bar'] ?? true;

  // Filter configs
  $show_filter_bar         = $options_group['show_filter_bar'] ?? true;
  $taxonomy_filters_field  = $options_group['taxonomy_filters'] ?? '';
  $post_type_filters_field  = $options_group['post_type_filters'] ?? '';
  $show_filter_by_pub_date = $options_group['show_filter_by_published_date'] ?? true;

  // Card listing view configs
  $show_featured_image     = $options_group['card_show_featured_image'] ?? true;
  $show_content_type_tags  = $options_group['card_show_content_type_tags'] ?? true; // TODO: Update
  $show_pub_date           = $options_group['card_show_published_date'] ?? true;
  $show_excerpt            = $options_group['card_show_excerpt'] ?? true;

  // TODO: Add support for advanced/elastic search

  // Create taxonomy filters array
  $taxonomy_filters_field = explode( ',', $taxonomy_filters_field );
  $taxonomy_filters = [];
  foreach( $taxonomy_filters_field as $taxonomy ) {
    $taxonomy_filters[] = [
      'slug'    => $taxonomy,
      'tooltip' => '',
    ];
  }

  // Create post type filters array
  $post_type_filters_field = $post_type_filters_field === '' ? [] : explode(',', $post_type_filters_field);
  $post_type_filters = [];
  foreach( $post_type_filters_field as $post_type ) {
    $post_type_filters[] = [
      'key'    => $post_type
    ];
  }


  //$query       = new WP_Query( $args );
  $query       = $wp_query; // Using the WordPress-provided query object that gets modified in custom/search.php
  $posts       = $query->posts;
  $total_posts = $query->found_posts;
  $page_num    = ( $paged == 0 ) ? 1 : $paged;
  $start_page  = ($page_num * $posts_per_page) - ($posts_per_page - 1);
  $end_page    = ($page_num * $posts_per_page);
  $end_page    = ($total_posts < $end_page) ? $total_posts : $end_page;
  $page_count  = ceil( $total_posts / $posts_per_page );

  // wicket_write_log( 'Total posts: ' . $total_posts, true );
  // wicket_write_log( 'Page num: ' . $page_num, true );
  // wicket_write_log( 'Start page: ' . $start_page, true );
  // wicket_write_log( 'End page: ' . $end_page, true );
  //wicket_write_log( $posts, true );


?>

<form action="" x-data="
  { foo: 'bar', 
    updateUrlParam(url, param, value) {
      let the_url = new URL(url);
      let search_params = the_url.searchParams;
      search_params.set(param, value);

      the_url.search = search_params.toString();

      return the_url.toString();
    }
  }">
  <?php /** 
          * Add the value of s as a hidden form field so the filter-form bar will pass along its 
          * value and keep us on the search page.
          * 
          * Might add a duplicate 's' param to URL in some cases, but that won't cause error and will
          * Ensure we're staying on the search page when filters are applied.
          */
  ?>
  <input type="hidden" name="s" value="<?php echo $search_term; ?>" />

  <div class="px-4 py-5 lg:px-0">
      <div class="container max-w-screen-lg mx-auto">
        <h1 class="search-page-heading text-heading-xl font-bold text-center"><?php _e( 'Search Results', 'wicket' ); ?></h1>
      </div>
    </div>

  <?php if ( $show_search_bar ) : ?>
    <div class="px-4 pb-12 lg:px-0">
      <div class="max-w-screen-lg mx-auto">
        <p class="search-page-results-count text-heading-xs mb-8 text-center"><?php echo $total_posts; ?> <?php _e( 'Results for:', 'wicket' ); ?></p>
        <?php 
        get_component( 'search-form', [
          'url-param' => 's',
        ] ); ?>
        <a href="/?s=" class="search-page-clear-button block text-body-md text-center mt-4"><i class="fa-solid fa-x"></i> <?php _e( 'Clear Search', 'wicket' ); ?></a>

      </div>
    </div>
  <?php endif; ?>

  <?php
  echo '<div class="search-page-results-wrapper bg-light-010 bg-opacity-15 overflow-x-hidden">';
  ?>

  <div class="container">
    <div class="search-page-results-flex-wrap flex flex-col lg:flex-row gap-4">

      <?php if( $show_filter_bar ): ?>
        <div
          class="search-page-left-col basis-1/4 bg-white relative after:content-[''] after:absolute after:top-0 after:bottom-0 after:right-full after:bg-white after:w-[30vw] before:block lg:before:hidden before:content-[''] before:absolute before:top-0 before:bottom-0 before:left-full before:bg-white before:w-[30vw]">
          <?php
          get_component( 'filter-form', [ 
            'taxonomies'       => $taxonomy_filters,
            'post_types'       => $post_type_filters,
            'hide_date_filter' => ! $show_filter_by_pub_date,
          ] );
          ?>
        </div>
      <?php endif; // End if $show_filter_bar ?>

      <div class="search-page-right-col pt-4 lg:pt-10 <?php if( !$show_filter_bar ) { echo 'max-w-screen-lg lg:mx-auto'; } else { echo 'basis-3/4' ;} ?>">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-7 px-4 lg:px-0">
          <div><?php _e('Displaying:', 'wicket'); ?>
            <?php
            if ( $total_posts === 0 ) {
              echo '<span class="font-bold">0</span>' . __( ' Results', 'wicket' );
            } else {
              echo '<span class="font-bold">' . $start_page . '-' . $end_page . '</span> of ' . $total_posts . ' Results';
            }
            ?>
          </div>
          <div class="flex items-center">
            <div class="font-bold mr-4"><?php _e('Sort by', 'wicket'); ?></div>
            <select x-on:change="window.location.href = updateUrlParam(window.location.href, 'sortby', $el.value);" class="pr-8">
              <option value="new-to-old" <?php if(isset($_GET['sortby'])) { if ($_GET['sortby'] == 'new-to-old') {echo 'selected';} } ?>><?php _e('Date (Newest - Oldest)', 'wicket'); ?></option>
              <option value="old-to-new" <?php if(isset($_GET['sortby'])) { if ($_GET['sortby'] == 'old-to-new') {echo 'selected';} } ?>><?php _e('Date (Oldest - Newest)', 'wicket'); ?></option>
            </select>
          </div>
          
        </div>

        <?php
        if ( $query->have_posts() ) : ?>
          <div class="pb-24 px-4 lg:px-0">
            <?php
            while ( $query->have_posts() ) :
              $query->the_post();
              $post_id              = get_the_ID();
              $permalink            = get_the_permalink( $post_id );
              // TODO: Update taxonomies array this reads from
              $content_type         = ! is_wp_error( get_the_terms( $post_id, 'categories' ) ) ? get_the_terms( $post_id, 'categories' ) : [];
              $title                = get_the_title( $post_id );
              $excerpt              = get_the_excerpt( $post_id );
              $pub_date             = get_the_date( 'M d, Y', $post_id );
              $featured_image       = get_post_thumbnail_id( $post_id );
              $member_only          = is_member_only( $post_id );

              $card_params = [ 
                'classes'           => [ 'mb-6' ],
                //'content_type'      => $content_type,
                'date'              => $show_pub_date ? $pub_date : '',
                'topics'            => $show_content_type_tags && !empty($content_type) ? $content_type[0]->name : '',
                'title'             => $title,
                'excerpt'           => $show_excerpt ? $excerpt : '',
                'featured_image'    => $show_featured_image ? $featured_image : '',
                'cta_style'         => 'button',
                'link'              => [ 
                  'url'       => $permalink,
                  'text'      => 'Read more',
                  'target'    => '_self',
                ],
                'link_type'         => 'title',
                'member_only'       => $member_only,
              ];

              get_component( 'card-listing', $card_params );

            endwhile;
            the_wicket_pagination( [ 
              'total' => $page_count,
              'format' => '?paged=%#%',
            ] );
            ?>
          </div>
          <?php
        else : ?>
          <div class="p-10">
            <h2 class="text-center font-bold text-heading-md mb-6">
              <?php echo __( 'No results found.', 'wicket' ) ?>
            </h2>
            <div class="text-center">
              <?php echo __( 'Try adjusting your search or filter to find what you are looking for.', 'wicket' ) ?>
            </div>
          </div>
          <?php
        endif; ?>
      </div>
    </div>
  </div>

  </div>
</form>

<?php get_footer();
