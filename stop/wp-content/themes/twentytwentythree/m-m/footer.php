<!-- Footer -->
<footer class="footer-section">
    <div class="container">
        <!-- Footer Header -->
        <div class="footer-header">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="footer-brand">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <img class="dark-logo-footer" src="<?php echo get_template_directory_uri(); ?>/images/logo-dark.svg" alt="<?php bloginfo('name'); ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="footer-social">
                        <span class="follow-us-text">FOLLOW US</span>
                        <div class="social-icons">
                            <?php 
                            $facebook = get_theme_mod('facebook_url', '#');
                            $instagram = get_theme_mod('instagram_url', '#');
                            $twitter = get_theme_mod('twitter_url', '#');
                            $linkedin = get_theme_mod('linkedin_url', '#');
                            ?>
                            <a href="<?php echo esc_url($facebook); ?>" class="social-icon" target="_blank">
                                 <img src="<?php echo get_template_directory_uri(); ?>/images/icons/f-icon.svg" alt="Facebook Icon">
                            </a>
                            <a href="<?php echo esc_url($instagram); ?>" class="social-icon" target="_blank">
                                 <img src="<?php echo get_template_directory_uri(); ?>/images/icons/i-icon.svg" alt="Instagram Icon">
                            </a>
                            <a href="<?php echo esc_url($twitter); ?>" class="social-icon" target="_blank">
                                 <img src="<?php echo get_template_directory_uri(); ?>/images/icons/x-icon.svg" alt="Twitter Icon">
                            </a>
                            <a href="<?php echo esc_url($linkedin); ?>" class="social-icon" target="_blank">
                                 <img src="<?php echo get_template_directory_uri(); ?>/images/icons/link-icon.svg" alt="LinkedIn Icon">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
        </div>
        
        <!-- Footer Navigation -->
        <div class="footer-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_class'     => 'footer-nav-list',
                'container'      => false,
                'fallback_cb'    => false,
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                'depth'          => 1,
                'walker'         => new class extends Walker_Nav_Menu {
                    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                        $output .= '<li>';
                        
                        $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
                        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target    ) .'"' : '';
                        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) .'"' : '';
                        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url       ) .'"' : '';
                        $attributes .= ' class="footer-nav-link"';
                        
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
        </div>
        
        <!-- Footer Contact -->
        <div class="footer-contact">
            <div class="d-flex">
                <?php 
                $email = get_theme_mod('contact_email', 'info@m&erecruitment.com');
                $phone = get_theme_mod('phone_number', '0208 298 9977');
                $address = get_theme_mod('contact_address', 'Unit 39H The Hop Store, Old Bexley Business Park, 19 Bourne Road, Bexley, Kent, DA5 1LR');
                ?>
                <div class="mb-3-ic">
                    <div class="contact-item">
                        <i class="fas fa-envelope contact-icon"></i>
                        <span><?php echo esc_html($email); ?></span>
                    </div>
                </div>
                <div class="mb-3-ic">
                    <div class="contact-item">
                        <i class="fas fa-phone contact-icon"></i>
                        <span><?php echo esc_html($phone); ?></span>
                    </div>
                </div>
                <div class="mb-3-ic">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt contact-icon"></i>
                        <span><?php echo esc_html($address); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Legal -->
        <div class="footer-legal">
            <ul class="legal-links">
                <li><span class="copyright">Copyright © <?php echo date('Y'); ?> <?php bloginfo('name'); ?></span></li>
                <li><a href="#" class="legal-link">Privacy</a></li>
                <li><a href="#" class="legal-link">Cookies</a></li>
                <li><a href="#" class="legal-link">Legal</a></li>
                <li><a href="#" class="legal-link">Complaints</a></li>
            </ul>
        </div>
        
        <!-- Footer Text -->
        <div class="footer-text">
            <p>The Company is a recruitment business which provides work-finding services to its clients and work-seekers. The Company must process personal data (including sensitive personal data) so that it can provide these services – in doing so, the Company acts as a data controller.</p>
            
            <p>You may give your personal details to the Company directly, such as on an application or registration form or via our website, or we may collect them from another source such as a jobs board. The Company must have a legal basis for processing your personal data. For the purposes of providing you with work-finding services and/or information relating to roles relevant to you we will only use your personal data in accordance with this privacy statement. At all times we will comply with current data protection laws.</p>
        </div>
    </div>
</footer>
<!-- Footer End -->

<?php wp_footer(); ?>
</body>
</html>
