<?php
/**
 * Theme Options Admin Panel
 * 
 * Centralized admin panel for all theme settings:
 * - Contact Information
 * - Social Media Links
 * - Footer Settings
 * - General Settings
 */

// Add Theme Options Menu
add_action('admin_menu', 'me_recruitment_theme_options_menu');
function me_recruitment_theme_options_menu() {
    add_menu_page(
        'M&E Theme Options',           // Page title
        'Theme Options',               // Menu title
        'manage_options',              // Capability
        'me-theme-options',            // Menu slug
        'me_recruitment_theme_options_page', // Callback function
        'dashicons-admin-settings',    // Icon
        61                             // Position
    );
    
    // Add submenu pages
    add_submenu_page(
        'me-theme-options',
        'Contact Settings',
        'Contact Info',
        'manage_options',
        'me-theme-options',
        'me_recruitment_theme_options_page'
    );
    
    add_submenu_page(
        'me-theme-options',
        'Social Media',
        'Social Media',
        'manage_options',
        'me-social-options',
        'me_recruitment_social_options_page'
    );
    
    add_submenu_page(
        'me-theme-options',
        'Footer Settings',
        'Footer Settings',
        'manage_options',
        'me-footer-options',
        'me_recruitment_footer_options_page'
    );
}

// Register Settings
add_action('admin_init', 'me_recruitment_register_theme_settings');
function me_recruitment_register_theme_settings() {
    // Contact Settings
    register_setting('me_theme_options_group', 'me_contact_email');
    register_setting('me_theme_options_group', 'me_contact_phone');
    register_setting('me_theme_options_group', 'me_contact_address');
    
    // Social Media
    register_setting('me_social_options_group', 'me_facebook_url');
    register_setting('me_social_options_group', 'me_instagram_url');
    register_setting('me_social_options_group', 'me_twitter_url');
    register_setting('me_social_options_group', 'me_linkedin_url');
    
    // Footer Settings
    register_setting('me_footer_options_group', 'me_footer_copyright_text');
    register_setting('me_footer_options_group', 'me_footer_legal_text');
    register_setting('me_footer_options_group', 'me_footer_privacy_url');
    register_setting('me_footer_options_group', 'me_footer_cookies_url');
    register_setting('me_footer_options_group', 'me_footer_legal_url');
    register_setting('me_footer_options_group', 'me_footer_complaints_url');
}

// Main Theme Options Page (Contact Info)
function me_recruitment_theme_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    // Save settings
    if (isset($_POST['me_save_contact_options']) && check_admin_referer('me_contact_options_action', 'me_contact_options_nonce')) {
        update_option('me_contact_email', sanitize_email($_POST['me_contact_email']));
        update_option('me_contact_phone', sanitize_text_field($_POST['me_contact_phone']));
        update_option('me_contact_address', sanitize_textarea_field($_POST['me_contact_address']));
        echo '<div class="notice notice-success"><p>Contact settings saved successfully!</p></div>';
    }
    
    $contact_email = get_option('me_contact_email', 'info@m&erecruitment.com');
    $contact_phone = get_option('me_contact_phone', '0208 298 9977');
    $contact_address = get_option('me_contact_address', 'Unit 39H The Hop Store, Old Bexley Business Park, 19 Bourne Road, Bexley, Kent, DA5 1LR');
    ?>
    <div class="wrap">
        <h1><i class="dashicons dashicons-admin-settings"></i> M&E Theme Options - Contact Information</h1>
        
        <div style="background: white; padding: 20px; margin-top: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form method="post" action="">
                <?php wp_nonce_field('me_contact_options_action', 'me_contact_options_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="me_contact_email">Contact Email</label>
                        </th>
                        <td>
                            <input type="email" id="me_contact_email" name="me_contact_email" value="<?php echo esc_attr($contact_email); ?>" class="regular-text" />
                            <p class="description">Displayed in footer and used for contact forms</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_contact_phone">Phone Number</label>
                        </th>
                        <td>
                            <input type="text" id="me_contact_phone" name="me_contact_phone" value="<?php echo esc_attr($contact_phone); ?>" class="regular-text" />
                            <p class="description">Displayed in footer and job listings</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_contact_address">Contact Address</label>
                        </th>
                        <td>
                            <textarea id="me_contact_address" name="me_contact_address" rows="3" class="large-text"><?php echo esc_textarea($contact_address); ?></textarea>
                            <p class="description">Full business address displayed in footer</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="me_save_contact_options" class="button button-primary" value="Save Contact Settings" />
                </p>
            </form>
        </div>
        
        <div style="background: #fff3cd; padding: 15px; margin-top: 20px; border-left: 4px solid #ffc107; border-radius: 3px;">
            <strong>💡 Tip:</strong> These settings are used throughout your theme in headers, footers, and contact forms.
        </div>
    </div>
    <?php
}

// Social Media Options Page
function me_recruitment_social_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    if (isset($_POST['me_save_social_options']) && check_admin_referer('me_social_options_action', 'me_social_options_nonce')) {
        update_option('me_facebook_url', esc_url_raw($_POST['me_facebook_url']));
        update_option('me_instagram_url', esc_url_raw($_POST['me_instagram_url']));
        update_option('me_twitter_url', esc_url_raw($_POST['me_twitter_url']));
        update_option('me_linkedin_url', esc_url_raw($_POST['me_linkedin_url']));
        echo '<div class="notice notice-success"><p>Social media settings saved successfully!</p></div>';
    }
    
    $facebook_url = get_option('me_facebook_url', '#');
    $instagram_url = get_option('me_instagram_url', '#');
    $twitter_url = get_option('me_twitter_url', '#');
    $linkedin_url = get_option('me_linkedin_url', '#');
    ?>
    <div class="wrap">
        <h1><i class="dashicons dashicons-share"></i> M&E Theme Options - Social Media</h1>
        
        <div style="background: white; padding: 20px; margin-top: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form method="post" action="">
                <?php wp_nonce_field('me_social_options_action', 'me_social_options_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="me_facebook_url"><i class="dashicons dashicons-facebook"></i> Facebook URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_facebook_url" name="me_facebook_url" value="<?php echo esc_attr($facebook_url); ?>" class="regular-text" placeholder="https://facebook.com/yourpage" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_instagram_url"><i class="dashicons dashicons-instagram"></i> Instagram URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_instagram_url" name="me_instagram_url" value="<?php echo esc_attr($instagram_url); ?>" class="regular-text" placeholder="https://instagram.com/yourprofile" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_twitter_url"><i class="dashicons dashicons-twitter"></i> Twitter (X) URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_twitter_url" name="me_twitter_url" value="<?php echo esc_attr($twitter_url); ?>" class="regular-text" placeholder="https://twitter.com/yourprofile" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_linkedin_url"><i class="dashicons dashicons-linkedin"></i> LinkedIn URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_linkedin_url" name="me_linkedin_url" value="<?php echo esc_attr($linkedin_url); ?>" class="regular-text" placeholder="https://linkedin.com/company/yourcompany" />
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="me_save_social_options" class="button button-primary" value="Save Social Media Settings" />
                </p>
            </form>
        </div>
        
        <div style="background: #d1ecf1; padding: 15px; margin-top: 20px; border-left: 4px solid #17a2b8; border-radius: 3px;">
            <strong>ℹ️ Info:</strong> Social media links appear in the footer. Leave as '#' to hide or add your full profile URLs.
        </div>
    </div>
    <?php
}

// Footer Options Page
function me_recruitment_footer_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    if (isset($_POST['me_save_footer_options']) && check_admin_referer('me_footer_options_action', 'me_footer_options_nonce')) {
        update_option('me_footer_copyright_text', sanitize_text_field($_POST['me_footer_copyright_text']));
        update_option('me_footer_legal_text', wp_kses_post($_POST['me_footer_legal_text']));
        update_option('me_footer_privacy_url', esc_url_raw($_POST['me_footer_privacy_url']));
        update_option('me_footer_cookies_url', esc_url_raw($_POST['me_footer_cookies_url']));
        update_option('me_footer_legal_url', esc_url_raw($_POST['me_footer_legal_url']));
        update_option('me_footer_complaints_url', esc_url_raw($_POST['me_footer_complaints_url']));
        echo '<div class="notice notice-success"><p>Footer settings saved successfully!</p></div>';
    }
    
    $copyright_text = get_option('me_footer_copyright_text', get_bloginfo('name'));
    $legal_text = get_option('me_footer_legal_text', 'The Company is a recruitment business which provides work-finding services to its clients and work-seekers. The Company must process personal data (including sensitive personal data) so that it can provide these services – in doing so, the Company acts as a data controller.

You may give your personal details to the Company directly, such as on an application or registration form or via our website, or we may collect them from another source such as a jobs board. The Company must have a legal basis for processing your personal data. For the purposes of providing you with work-finding services and/or information relating to roles relevant to you we will only use your personal data in accordance with this privacy statement. At all times we will comply with current data protection laws.');
    $privacy_url = get_option('me_footer_privacy_url', '#');
    $cookies_url = get_option('me_footer_cookies_url', '#');
    $legal_url = get_option('me_footer_legal_url', '#');
    $complaints_url = get_option('me_footer_complaints_url', '#');
    ?>
    <div class="wrap">
        <h1><i class="dashicons dashicons-editor-alignleft"></i> M&E Theme Options - Footer Settings</h1>
        
        <div style="background: white; padding: 20px; margin-top: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form method="post" action="">
                <?php wp_nonce_field('me_footer_options_action', 'me_footer_options_nonce'); ?>
                
                <h2>Copyright & Legal Text</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="me_footer_copyright_text">Copyright Text</label>
                        </th>
                        <td>
                            <input type="text" id="me_footer_copyright_text" name="me_footer_copyright_text" value="<?php echo esc_attr($copyright_text); ?>" class="regular-text" />
                            <p class="description">Company name for copyright notice (year is automatic)</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_footer_legal_text">Footer Legal Text</label>
                        </th>
                        <td>
                            <textarea id="me_footer_legal_text" name="me_footer_legal_text" rows="8" class="large-text"><?php echo esc_textarea($legal_text); ?></textarea>
                            <p class="description">GDPR and legal compliance text displayed at bottom of footer</p>
                        </td>
                    </tr>
                </table>
                
                <hr style="margin: 30px 0;" />
                
                <h2>Footer Menu Links</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="me_footer_privacy_url">Privacy Policy URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_footer_privacy_url" name="me_footer_privacy_url" value="<?php echo esc_attr($privacy_url); ?>" class="regular-text" placeholder="/privacy-policy/" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_footer_cookies_url">Cookies Policy URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_footer_cookies_url" name="me_footer_cookies_url" value="<?php echo esc_attr($cookies_url); ?>" class="regular-text" placeholder="/cookies-policy/" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_footer_legal_url">Legal Page URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_footer_legal_url" name="me_footer_legal_url" value="<?php echo esc_attr($legal_url); ?>" class="regular-text" placeholder="/legal/" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="me_footer_complaints_url">Complaints Page URL</label>
                        </th>
                        <td>
                            <input type="url" id="me_footer_complaints_url" name="me_footer_complaints_url" value="<?php echo esc_attr($complaints_url); ?>" class="regular-text" placeholder="/complaints/" />
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="me_save_footer_options" class="button button-primary" value="Save Footer Settings" />
                </p>
            </form>
        </div>
        
        <div style="background: #fff3cd; padding: 15px; margin-top: 20px; border-left: 4px solid #ffc107; border-radius: 3px;">
            <strong>💡 Note:</strong> Footer menu can also be managed via <a href="<?php echo admin_url('nav-menus.php'); ?>">Appearance > Menus</a>. Create pages for Privacy, Cookies, Legal, and Complaints, then link them here or add to footer menu.
        </div>
    </div>
    <?php
}
