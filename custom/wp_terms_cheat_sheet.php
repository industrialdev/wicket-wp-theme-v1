<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp/wp-load.php');

global $current_user;
$user_roles = $current_user->roles;

if( !in_array( 'administrator', $user_roles ) || empty( $user_roles ) ) {
  die();
}
?>

<style>
  .toggleable:hover {
    cursor: pointer;
  }
</style>
<script src="//unpkg.com/alpinejs" defer></script>

<h1 id="taxonomies" >Taxonomies:</h1><?php

$taxonomies = get_taxonomies();
wicket_write_log( $taxonomies, true );

?><h1>Taxonomy Terms (click to expand):</h1><?php

foreach( $taxonomies as $taxonomy_slug => $taxonomy_slug2 ) {?>
  <div x-data="{ open: false }" @click="open = ! open" class="toggleable">
    <strong>▼ Terms for taxonomy '<?php echo $taxonomy_slug; ?>':</strong>
    <div x-show="open">
      <?php
      wicket_write_log( get_terms( [
        'taxonomy' => $taxonomy_slug,
        'hide_empty' => false,
      ] ), true );
      ?>
    </div>
  </div>
<?php }

?><h1 id="post_types" >Post Types:</h1><?php

$post_types = get_post_types();
wicket_write_log( $post_types, true );

?><h1>Post IDs and their pages:</h1><?php

$args = [ 
  //'post_type'      => [ 'post', 'page' ],
  'post_type'      => 'any',
  'fields'          => 'all',
  'posts_per_page'  => -1,
  'post_status'     => ['private', 'draft', 'publish'],
  //'paged'          => $paged,
  //'orderby'        => 'date',
  //'order'          => $order,
  //'s'              => $search_term,
  //'post__not_in'   => $excluded_post_ids,
  //'tax_query'      => $tax_query,
];

$query       = new WP_Query( $args );
$posts       = $query->posts;

$cleaned_posts = [];
foreach( $posts as $post ) {
  $cleaned_posts[] = [
    'post-title' => $post->post_title,
    'post-id'    => $post->ID,
    'post-type'  => $post->post_type,
  ];
}

wicket_write_log( $cleaned_posts, true );
