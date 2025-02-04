<?php if(get_field('header_menu', 'options') || get_field('header_utility_menu', 'options')) : ?>
  <nav id="mobile-menu" class="nav nav--mobile">
    <ul class="nav__menu nav__menu--auth">
      <?php get_template_part('partials/auth-menu-items'); ?>
    </ul>

    <?php 
      $post = get_field('header_menu', 'options'); 
      setup_postdata($post);  
      get_template_part('partials/menu-items');
      wp_reset_postdata();
    ?>

    <?php 
      $post = get_field('header_utility_menu', 'options'); 
      setup_postdata($post);  
      get_template_part('partials/menu-items');
      wp_reset_postdata();
    ?>

    <?php
      do_action('wpml_add_language_selector');
    ?>
  </nav>
<?php endif; ?>