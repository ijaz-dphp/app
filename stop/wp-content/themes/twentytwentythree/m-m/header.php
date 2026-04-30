<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="<?php echo esc_url(home_url('/')); ?>">
                <?php if (has_custom_logo()): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <img class="light-logo" src="<?php echo get_template_directory_uri(); ?>/images/logo.svg" alt="<?php bloginfo('name'); ?>" />
                    <img class="dark-logo" src="<?php echo get_template_directory_uri(); ?>/images/logo-dark.svg" alt="<?php bloginfo('name'); ?>" />
                <?php endif; ?>
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'navbar-nav me-auto',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                    'depth'          => 2,
                    'walker'         => new class extends Walker_Nav_Menu {
                        function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                            $classes = empty($item->classes) ? array() : (array) $item->classes;
                            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
                            
                            $output .= '<li class="nav-item ' . esc_attr($class_names) . '">';
                            
                            $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
                            $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
                            $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
                            $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';
                            $attributes .= ' class="nav-link"';
                            
                            $item_output = $args->before;
                            $item_output .= '<a'. $attributes .'>';
                            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
                            $item_output .= '</a>';
                            $item_output .= $args->after;
                            
                            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
                        }
                    }
                ));
                ?>
                
                <!-- Contact Info -->
                <div class="contact-info">
                    <div class="contact-section">
                        <?php 
                        $whatsapp = get_theme_mod('whatsapp_number', '+442082989977');
                        $phone = get_theme_mod('phone_number', '0208 298 9977');
                        ?>
                        <a href="https://wa.me/<?php echo esc_attr(str_replace('+', '', $whatsapp)); ?>" class="whatsapp-icon" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>" class="phone-number">
                            <i class="fas fa-phone" style="font-size: 14px; margin-right: 5px;"></i>
                            <?php echo esc_html($phone); ?>
                        </a>
                    </div>
                    <button class="btn submit-cv-btn">Submit Your CV</button>
                    <button id="themeToggle" class="btn ms-2">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>
