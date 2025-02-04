<?php
  $post = get_field('header_utility_menu', 'options');
  $dropdown_count = 0;
?>

<?php if($post) : ?>
  <?php setup_postdata($post); ?>
  <nav class="nav nav--header-utility">
    <?php if(have_rows('menu_items')) : ?>
      <ul class="nav__menu">
        <?php while(have_rows('menu_items')) : the_row(); ?>
          <?php 
            $link = get_sub_field('link');
            $icon = get_sub_field('icon');
            $has_dropdown = false;
            if(have_rows('submenu_items')){
              $dropdown_count++;
              $unique_id = uniqid();
              $submenu_id = 'submenu-' . $unique_id;
              $submenu_toggle_id = 'submenu-' . $unique_id . '-toggle';
              $has_dropdown = true;
              get_row();
            }
          ?>
          <li class="nav__menu-item<?php if($has_dropdown) : ?> dropdown<?php endif; ?>">
            <a <?php if($has_dropdown) : ?>id="<?php echo $submenu_toggle_id; ?>"<?php endif; ?> class="nav__link<?php if($has_dropdown) : ?> dropdown__toggle<?php endif; ?>" href="<?php echo $link['url']; ?>" <?php if($link['target'] == "_blank") : ?>target="<?php echo $link['target']; ?>"<?php endif; ?> <?php if($has_dropdown) : ?>aria-controls="<?php echo $submenu_id; ?>" aria-expanded="false"<?php endif; ?>>
              <?php if($icon) : ?>
                <span class="nav__icon-wrapper">
                  <i class="icon <?php echo $icon; ?>" aria-hidden="true"></i><?php echo $link['title']; ?>
                </span>
              <?php else : ?>
                <?php echo $link['title']; ?>
              <?php endif; ?>
              <?php if($link['target'] == '_blank') : ?>
                <i class="icon far fa-external-link" aria-hidden="true"></i>
              <?php endif; ?>
            </a>
            <?php if(have_rows('submenu_items')) : ?>
              <ul id="<?php echo $submenu_id; ?>" class="nav__submenu dropdown__content" aria-labelledby="<?php echo $submenu_toggle_id; ?>" aria-expanded="false">
                <?php while(have_rows('submenu_items')) : the_row(); ?>
                  <?php 
                    $link = get_sub_field('link');
                    $icon = get_sub_field('icon'); 
                  ?>
                  <li class="submenu__menu-item">
                    <a class="submenu__link" href="<?php echo $link['url']; ?>" <?php if($link['target'] == "_blank") : ?>target="<?php echo $link['target']; ?>"<?php endif; ?>>
                      <?php if($icon) : ?>
                        <span class="nav__icon-wrapper">
                          <i class="icon <?php echo $icon; ?>" aria-hidden="true"></i><?php echo $link['title']; ?>
                        </span>
                      <?php else : ?>
                        <?php echo $link['title']; ?>
                      <?php endif; ?>
                    </a>
                  </li>
                <?php endwhile; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endwhile; ?>
        <?php get_template_part('/partials/auth-menu-items'); ?>
      </ul>
    <?php endif; ?>
  </nav>
  <?php wp_reset_postdata(); ?>
<?php endif; ?>