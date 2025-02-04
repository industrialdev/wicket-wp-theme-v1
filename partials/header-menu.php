<?php
  $menu = wp_nav_menu( ['theme_location' => 'header'] );

  write_log($menu, true);
  die();

  $post = get_field('header_menu', 'options');
  $dropdown_count = 0;
?>

<?php if($post) : ?>
  <?php setup_postdata($post); ?>
  <nav class="nav nav--header">
    <?php if(have_rows('menu_items')) : ?>
      <ul class="nav__menu">
        <?php while(have_rows('menu_items')) : the_row(); ?>
          <?php 
            $link = get_sub_field('link');
            $icon = get_sub_field('icon');
            $is_mega = get_sub_field('is_mega_menu');
            $has_dropdown = false;
            if(have_rows('submenu_items')){
              $dropdown_count++;
              $unique_id = uniqid();
              $submenu_id = 'submenu-' . $unique_id;
              $submenu_toggle_id = 'submenu-' . $unique_id . '-toggle';
              $has_dropdown = true;
              get_row(); // similar to resetting postdata
            }
          ?>
          <li class="nav__menu-item<?php if($is_mega) : ?> nav__menu-item--mega<?php endif; ?><?php if($has_dropdown) : ?> dropdown<?php endif; ?>">
            <a <?php if($has_dropdown) : ?>id="<?php echo $submenu_toggle_id; ?>"<?php endif; ?> class="nav__link<?php if($has_dropdown) : ?> dropdown__toggle<?php endif; ?>" href="<?php echo $link['url']; ?>" <?php if($link['target'] == "_blank") : ?>target="<?php echo $link['target']; ?>"<?php endif; ?> <?php if($has_dropdown) : ?>aria-controls="<?php echo $submenu_id; ?>" aria-expanded="false"<?php endif; ?>>
              <?php if($icon) : ?>
                <span class="nav__icon-wrapper">
                  <i class="icon <?php echo $icon; ?>" aria-hidden="true"></i>
                  <?php echo $link['title']; ?>
                </span>
              <?php else : ?>
                <?php echo $link['title']; ?>
              <?php endif; ?>
              <?php if($has_dropdown && $link['target'] !== '_blank') : ?>
                <i class="fas fa-caret-down icon" aria-hidden="true"></i>
              <?php endif; ?>
              <?php if($link['target'] == '_blank') : ?>
                <i class="icon far fa-external-link" aria-hidden="true"></i>
              <?php endif; ?>
            </a>
            <?php if($is_mega) : ?>
              <div id="<?php echo $submenu_id; ?>" class="nav__submenu-wrapper dropdown__content" aria-labelledby="<?php echo $submenu_toggle_id; ?>" aria-expanded="false">
            <?php endif; ?>
            <?php if(have_rows('submenu_items')) : ?>
              <ul class="nav__submenu<?php if(!$is_mega) : ?> dropdown__content<?php endif; ?>" <?php if(!$is_mega) : ?>id="<?php echo $submenu_id; ?>" aria-labelledby="<?php echo $submenu_toggle_id; ?>" aria-expanded="false"<?php endif; ?>>
                <?php while(have_rows('submenu_items')) : the_row(); ?>
                  <?php 
                    $link = get_sub_field('link'); 
                    $icon = get_sub_field('icon');
                  ?>
                  <li class="submenu__menu-item">
                    <a class="submenu__link" href="<?php echo $link['url']; ?>" <?php if($link['target'] == "_blank") : ?>target="<?php echo $link['target']; ?>"<?php endif; ?>>
                      <?php if($icon) : ?>
                        <span class="nav__icon-wrapper">
                          <i class="icon <?php echo $icon; ?>" aria-hidden="true"></i>
                          <?php echo $link['title']; ?>
                        </span>
                      <?php else : ?>
                        <?php echo $link['title']; ?>
                      <?php endif; ?>
                      <?php if($link['target'] == '_blank') : ?>
                        <i class="icon far fa-external-link" aria-hidden="true"></i>
                      <?php endif; ?>
                    </a>
                    <?php if(have_rows('submenu_items_2') && $is_mega) : ?>
                      <ul class="">
                        <?php while(have_rows('submenu_items_2')) : the_row(); ?>
                          <?php 
                            $link = get_sub_field('link'); 
                            $icon = get_sub_field('icon');
                          ?>
                          <li>
                            <a href="<?php echo $link['url']; ?>" <?php if($link['target'] == "_blank") : ?>target="<?php echo $link['target']; ?>"<?php endif; ?>>
                              <?php if($icon) : ?>
                                <span class="nav__icon-wrapper">
                                  <i class="icon <?php echo $icon; ?>" aria-hidden="true"></i>
                                  <?php echo $link['title']; ?>
                                </span>
                              <?php else : ?>
                                <?php echo $link['title']; ?>
                              <?php endif; ?>
                              <?php if($link['target'] == '_blank') : ?>
                                <i class="icon far fa-external-link" aria-hidden="true"></i>
                              <?php endif; ?>
                            </a>
                          </li>
                        <?php endwhile; ?>
                      </ul>
                    <?php endif; ?>
                  </li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>
            <?php if($is_mega) : ?>
              </div>
            <?php endif; ?>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>
  </nav>
  <?php wp_reset_postdata(); ?>
<?php endif; ?>