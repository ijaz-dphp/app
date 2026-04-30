<?php
/**
 * M&E Recruitment Theme Functions
 */

// Theme Setup
function me_recruitment_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'me-recruitment'),
        'footer' => __('Footer Menu', 'me-recruitment')
    ));
}
add_action('after_setup_theme', 'me_recruitment_setup');

// Enqueue Styles and Scripts
function me_recruitment_scripts() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
    
    // Google Fonts - Inter
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
    
    // Theme Styles
    wp_enqueue_style('theme-styles', get_template_directory_uri() . '/css/theme-styles.css', array(), '1.0.0');
    
    // Page Specific Styles
    if (is_page_template('page-about.php')) {
        wp_enqueue_style('about-styles', get_template_directory_uri() . '/css/about-styles.css', array(), '1.0.0');
    }
    if (is_page_template('page-candidate.php')) {
        wp_enqueue_style('candidate-styles', get_template_directory_uri() . '/css/candidate-styles.css', array(), '1.0.0');
    }
    if (is_page_template('page-jobs.php')) {
        wp_enqueue_style('jobs-styles', get_template_directory_uri() . '/css/jobs-styles.css', array(), '1.0.0');
    }
    
    // Main Theme Stylesheet
    wp_enqueue_style('me-recruitment-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Bootstrap JS
    wp_enqueue_script('bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true);
    
    // Common JS
    wp_enqueue_script('common-js', get_template_directory_uri() . '/js/common.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'me_recruitment_scripts');

// Custom Logo Support
function me_recruitment_custom_logo_setup() {
    $defaults = array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'me_recruitment_custom_logo_setup');

// Register Widget Areas
function me_recruitment_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'me-recruitment'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'me-recruitment'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'me-recruitment'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'me-recruitment'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'me_recruitment_widgets_init');

// Custom Excerpt Length
function me_recruitment_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'me_recruitment_excerpt_length', 999);

// Custom Excerpt More
function me_recruitment_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'me_recruitment_excerpt_more');

// Add Phone Number and WhatsApp to Customizer
function me_recruitment_customize_register($wp_customize) {
    // Contact Section
    $wp_customize->add_section('me_recruitment_contact', array(
        'title'    => __('Contact Information', 'me-recruitment'),
        'priority' => 30,
    ));
    
    // Phone Number
    $wp_customize->add_setting('phone_number', array(
        'default'           => '0208 298 9977',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('phone_number', array(
        'label'    => __('Phone Number', 'me-recruitment'),
        'section'  => 'me_recruitment_contact',
        'type'     => 'text',
    ));
    
    // WhatsApp Number
    $wp_customize->add_setting('whatsapp_number', array(
        'default'           => '+442082989977',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('whatsapp_number', array(
        'label'    => __('WhatsApp Number', 'me-recruitment'),
        'section'  => 'me_recruitment_contact',
        'type'     => 'text',
    ));
    
    // Email
    $wp_customize->add_setting('contact_email', array(
        'default'           => 'info@m&erecruitment.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('contact_email', array(
        'label'    => __('Contact Email', 'me-recruitment'),
        'section'  => 'me_recruitment_contact',
        'type'     => 'email',
    ));
    
    // Address
    $wp_customize->add_setting('contact_address', array(
        'default'           => 'Unit 39H The Hop Store, Old Bexley Business Park, 19 Bourne Road, Bexley, Kent, DA5 1LR',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('contact_address', array(
        'label'    => __('Contact Address', 'me-recruitment'),
        'section'  => 'me_recruitment_contact',
        'type'     => 'textarea',
    ));
    
    // Social Media Section
    $wp_customize->add_section('me_recruitment_social', array(
        'title'    => __('Social Media Links', 'me-recruitment'),
        'priority' => 31,
    ));
    
    // Facebook
    $wp_customize->add_setting('facebook_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('facebook_url', array(
        'label'    => __('Facebook URL', 'me-recruitment'),
        'section'  => 'me_recruitment_social',
        'type'     => 'url',
    ));
    
    // Instagram
    $wp_customize->add_setting('instagram_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('instagram_url', array(
        'label'    => __('Instagram URL', 'me-recruitment'),
        'section'  => 'me_recruitment_social',
        'type'     => 'url',
    ));
    
    // Twitter/X
    $wp_customize->add_setting('twitter_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('twitter_url', array(
        'label'    => __('Twitter/X URL', 'me-recruitment'),
        'section'  => 'me_recruitment_social',
        'type'     => 'url',
    ));
    
    // LinkedIn
    $wp_customize->add_setting('linkedin_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('linkedin_url', array(
        'label'    => __('LinkedIn URL', 'me-recruitment'),
        'section'  => 'me_recruitment_social',
        'type'     => 'url',
    ));
}
add_action('customize_register', 'me_recruitment_customize_register');

// Register Custom Post Type for Jobs
function me_recruitment_register_job_post_type() {
    $labels = array(
        'name'                  => _x('Jobs', 'Post Type General Name', 'me-recruitment'),
        'singular_name'         => _x('Job', 'Post Type Singular Name', 'me-recruitment'),
        'menu_name'             => __('Jobs', 'me-recruitment'),
        'name_admin_bar'        => __('Job', 'me-recruitment'),
        'archives'              => __('Job Archives', 'me-recruitment'),
        'attributes'            => __('Job Attributes', 'me-recruitment'),
        'parent_item_colon'     => __('Parent Job:', 'me-recruitment'),
        'all_items'             => __('All Jobs', 'me-recruitment'),
        'add_new_item'          => __('Add New Job', 'me-recruitment'),
        'add_new'               => __('Add New', 'me-recruitment'),
        'new_item'              => __('New Job', 'me-recruitment'),
        'edit_item'             => __('Edit Job', 'me-recruitment'),
        'update_item'           => __('Update Job', 'me-recruitment'),
        'view_item'             => __('View Job', 'me-recruitment'),
        'view_items'            => __('View Jobs', 'me-recruitment'),
        'search_items'          => __('Search Job', 'me-recruitment'),
        'not_found'             => __('Not found', 'me-recruitment'),
        'not_found_in_trash'    => __('Not found in Trash', 'me-recruitment'),
        'featured_image'        => __('Featured Image', 'me-recruitment'),
        'set_featured_image'    => __('Set featured image', 'me-recruitment'),
        'remove_featured_image' => __('Remove featured image', 'me-recruitment'),
        'use_featured_image'    => __('Use as featured image', 'me-recruitment'),
        'insert_into_item'      => __('Insert into job', 'me-recruitment'),
        'uploaded_to_this_item' => __('Uploaded to this job', 'me-recruitment'),
        'items_list'            => __('Jobs list', 'me-recruitment'),
        'items_list_navigation' => __('Jobs list navigation', 'me-recruitment'),
        'filter_items_list'     => __('Filter jobs list', 'me-recruitment'),
    );
    
    $args = array(
        'label'                 => __('Job', 'me-recruitment'),
        'description'           => __('Job Listings', 'me-recruitment'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
        'taxonomies'            => array('job_category', 'job_type'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array('slug' => 'jobs'),
    );
    
    register_post_type('job', $args);
}
add_action('init', 'me_recruitment_register_job_post_type', 0);

// Register Custom Taxonomies for Jobs
function me_recruitment_register_job_taxonomies() {
    // Job Category Taxonomy
    $category_labels = array(
        'name'              => _x('Job Categories', 'taxonomy general name', 'me-recruitment'),
        'singular_name'     => _x('Job Category', 'taxonomy singular name', 'me-recruitment'),
        'search_items'      => __('Search Job Categories', 'me-recruitment'),
        'all_items'         => __('All Job Categories', 'me-recruitment'),
        'parent_item'       => __('Parent Job Category', 'me-recruitment'),
        'parent_item_colon' => __('Parent Job Category:', 'me-recruitment'),
        'edit_item'         => __('Edit Job Category', 'me-recruitment'),
        'update_item'       => __('Update Job Category', 'me-recruitment'),
        'add_new_item'      => __('Add New Job Category', 'me-recruitment'),
        'new_item_name'     => __('New Job Category Name', 'me-recruitment'),
        'menu_name'         => __('Job Categories', 'me-recruitment'),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $category_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('job_category', array('job'), $category_args);

    // Job Type Taxonomy
    $type_labels = array(
        'name'              => _x('Job Types', 'taxonomy general name', 'me-recruitment'),
        'singular_name'     => _x('Job Type', 'taxonomy singular name', 'me-recruitment'),
        'search_items'      => __('Search Job Types', 'me-recruitment'),
        'all_items'         => __('All Job Types', 'me-recruitment'),
        'edit_item'         => __('Edit Job Type', 'me-recruitment'),
        'update_item'       => __('Update Job Type', 'me-recruitment'),
        'add_new_item'      => __('Add New Job Type', 'me-recruitment'),
        'new_item_name'     => __('New Job Type Name', 'me-recruitment'),
        'menu_name'         => __('Job Types', 'me-recruitment'),
    );

    $type_args = array(
        'hierarchical'      => false,
        'labels'            => $type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'job-type'),
        'show_in_rest'      => true,
    );

    register_taxonomy('job_type', array('job'), $type_args);
}
add_action('init', 'me_recruitment_register_job_taxonomies', 0);

// Add Custom Meta Boxes for Jobs
function me_recruitment_add_job_meta_boxes() {
    add_meta_box(
        'job_details',
        __('Job Details', 'me-recruitment'),
        'me_recruitment_job_details_callback',
        'job',
        'normal',
        'high'
    );
    
    add_meta_box(
        'job_application',
        __('Application Information', 'me-recruitment'),
        'me_recruitment_job_application_callback',
        'job',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'me_recruitment_add_job_meta_boxes');

// Job Details Meta Box Callback
function me_recruitment_job_details_callback($post) {
    wp_nonce_field('me_recruitment_save_job_details', 'me_recruitment_job_details_nonce');
    
    $job_location = get_post_meta($post->ID, '_job_location', true);
    $job_salary = get_post_meta($post->ID, '_job_salary', true);
    $job_contract_type = get_post_meta($post->ID, '_job_contract_type', true);
    $job_experience = get_post_meta($post->ID, '_job_experience', true);
    $job_qualifications = get_post_meta($post->ID, '_job_qualifications', true);
    $job_responsibilities = get_post_meta($post->ID, '_job_responsibilities', true);
    $job_requirements = get_post_meta($post->ID, '_job_requirements', true);
    ?>
    
    <style>
        .job-meta-field { margin-bottom: 20px; }
        .job-meta-field label { display: block; font-weight: 600; margin-bottom: 5px; }
        .job-meta-field input[type="text"],
        .job-meta-field select,
        .job-meta-field textarea { width: 100%; padding: 8px; }
        .job-meta-field textarea { min-height: 100px; }
    </style>
    
    <div class="job-meta-field">
        <label for="job_location"><?php _e('Job Location', 'me-recruitment'); ?></label>
        <input type="text" id="job_location" name="job_location" value="<?php echo esc_attr($job_location); ?>" placeholder="e.g., Dartford, London" />
    </div>
    
    <div class="job-meta-field">
        <label for="job_salary"><?php _e('Salary Range', 'me-recruitment'); ?></label>
        <input type="text" id="job_salary" name="job_salary" value="<?php echo esc_attr($job_salary); ?>" placeholder="e.g., £26,000 – £32,000" />
    </div>
    
    <div class="job-meta-field">
        <label for="job_contract_type"><?php _e('Contract Type', 'me-recruitment'); ?></label>
        <select id="job_contract_type" name="job_contract_type">
            <option value=""><?php _e('Select Contract Type', 'me-recruitment'); ?></option>
            <option value="Permanent" <?php selected($job_contract_type, 'Permanent'); ?>><?php _e('Permanent', 'me-recruitment'); ?></option>
            <option value="Contract" <?php selected($job_contract_type, 'Contract'); ?>><?php _e('Contract', 'me-recruitment'); ?></option>
            <option value="Part Time" <?php selected($job_contract_type, 'Part Time'); ?>><?php _e('Part Time', 'me-recruitment'); ?></option>
            <option value="Temporary" <?php selected($job_contract_type, 'Temporary'); ?>><?php _e('Temporary', 'me-recruitment'); ?></option>
            <option value="Permanent Hybrid Working" <?php selected($job_contract_type, 'Permanent Hybrid Working'); ?>><?php _e('Permanent Hybrid Working', 'me-recruitment'); ?></option>
        </select>
    </div>
    
    <div class="job-meta-field">
        <label for="job_experience"><?php _e('Required Experience', 'me-recruitment'); ?></label>
        <input type="text" id="job_experience" name="job_experience" value="<?php echo esc_attr($job_experience); ?>" placeholder="e.g., 2-5 years" />
    </div>
    
    <div class="job-meta-field">
        <label for="job_qualifications"><?php _e('Qualifications Required', 'me-recruitment'); ?></label>
        <textarea id="job_qualifications" name="job_qualifications" placeholder="Enter qualifications (one per line)"><?php echo esc_textarea($job_qualifications); ?></textarea>
        <p class="description"><?php _e('Enter each qualification on a new line', 'me-recruitment'); ?></p>
    </div>
    
    <div class="job-meta-field">
        <label for="job_responsibilities"><?php _e('Key Responsibilities', 'me-recruitment'); ?></label>
        <textarea id="job_responsibilities" name="job_responsibilities" placeholder="Enter responsibilities (one per line)"><?php echo esc_textarea($job_responsibilities); ?></textarea>
        <p class="description"><?php _e('Enter each responsibility on a new line', 'me-recruitment'); ?></p>
    </div>
    
    <div class="job-meta-field">
        <label for="job_requirements"><?php _e('Job Requirements', 'me-recruitment'); ?></label>
        <textarea id="job_requirements" name="job_requirements" placeholder="Enter requirements (one per line)"><?php echo esc_textarea($job_requirements); ?></textarea>
        <p class="description"><?php _e('Enter each requirement on a new line', 'me-recruitment'); ?></p>
    </div>
    
    <?php
}

// Job Application Meta Box Callback
function me_recruitment_job_application_callback($post) {
    $job_deadline = get_post_meta($post->ID, '_job_deadline', true);
    $job_contact_email = get_post_meta($post->ID, '_job_contact_email', true);
    $job_contact_phone = get_post_meta($post->ID, '_job_contact_phone', true);
    $job_featured = get_post_meta($post->ID, '_job_featured', true);
    ?>
    
    <div class="job-meta-field">
        <label for="job_deadline"><?php _e('Application Deadline', 'me-recruitment'); ?></label>
        <input type="date" id="job_deadline" name="job_deadline" value="<?php echo esc_attr($job_deadline); ?>" style="width: 100%;" />
    </div>
    
    <div class="job-meta-field">
        <label for="job_contact_email"><?php _e('Contact Email', 'me-recruitment'); ?></label>
        <input type="email" id="job_contact_email" name="job_contact_email" value="<?php echo esc_attr($job_contact_email); ?>" style="width: 100%;" placeholder="contact@example.com" />
    </div>
    
    <div class="job-meta-field">
        <label for="job_contact_phone"><?php _e('Contact Phone', 'me-recruitment'); ?></label>
        <input type="tel" id="job_contact_phone" name="job_contact_phone" value="<?php echo esc_attr($job_contact_phone); ?>" style="width: 100%;" placeholder="+44 208 298 9977" />
    </div>
    
    <div class="job-meta-field">
        <label>
            <input type="checkbox" id="job_featured" name="job_featured" value="1" <?php checked($job_featured, '1'); ?> />
            <?php _e('Feature this job', 'me-recruitment'); ?>
        </label>
    </div>
    
    <?php
}

// Save Job Meta Data
function me_recruitment_save_job_meta_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['me_recruitment_job_details_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['me_recruitment_job_details_nonce'], 'me_recruitment_save_job_details')) {
        return;
    }
    
    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save meta data
    $meta_fields = array(
        '_job_location' => 'sanitize_text_field',
        '_job_salary' => 'sanitize_text_field',
        '_job_contract_type' => 'sanitize_text_field',
        '_job_experience' => 'sanitize_text_field',
        '_job_qualifications' => 'sanitize_textarea_field',
        '_job_responsibilities' => 'sanitize_textarea_field',
        '_job_requirements' => 'sanitize_textarea_field',
        '_job_deadline' => 'sanitize_text_field',
        '_job_contact_email' => 'sanitize_email',
        '_job_contact_phone' => 'sanitize_text_field',
    );
    
    foreach ($meta_fields as $meta_key => $sanitize_callback) {
        $field_name = ltrim($meta_key, '_');
        if (isset($_POST[$field_name])) {
            $meta_value = call_user_func($sanitize_callback, $_POST[$field_name]);
            update_post_meta($post_id, $meta_key, $meta_value);
        }
    }
    
    // Handle checkbox for featured job
    $job_featured = isset($_POST['job_featured']) ? '1' : '0';
    update_post_meta($post_id, '_job_featured', $job_featured);
}
add_action('save_post_job', 'me_recruitment_save_job_meta_data');

// Add custom columns to Jobs admin list
function me_recruitment_job_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => $columns['title'],
        'job_location' => __('Location', 'me-recruitment'),
        'job_salary' => __('Salary', 'me-recruitment'),
        'job_contract_type' => __('Contract Type', 'me-recruitment'),
        'job_featured' => __('Featured', 'me-recruitment'),
        'job_category' => __('Category', 'me-recruitment'),
        'date' => $columns['date'],
    );
    return $new_columns;
}
add_filter('manage_job_posts_columns', 'me_recruitment_job_columns');

// Populate custom columns
function me_recruitment_job_column_content($column, $post_id) {
    switch ($column) {
        case 'job_location':
            echo esc_html(get_post_meta($post_id, '_job_location', true) ?: '-');
            break;
        case 'job_salary':
            echo esc_html(get_post_meta($post_id, '_job_salary', true) ?: '-');
            break;
        case 'job_contract_type':
            echo esc_html(get_post_meta($post_id, '_job_contract_type', true) ?: '-');
            break;
        case 'job_featured':
            $featured = get_post_meta($post_id, '_job_featured', true);
            echo $featured == '1' ? '⭐ ' . __('Yes', 'me-recruitment') : __('No', 'me-recruitment');
            break;
        case 'job_category':
            $terms = get_the_terms($post_id, 'job_category');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $term_names));
            } else {
                echo '-';
            }
            break;
    }
}
add_action('manage_job_posts_custom_column', 'me_recruitment_job_column_content', 10, 2);

// Make columns sortable
function me_recruitment_job_sortable_columns($columns) {
    $columns['job_location'] = 'job_location';
    $columns['job_salary'] = 'job_salary';
    $columns['job_contract_type'] = 'job_contract_type';
    $columns['job_featured'] = 'job_featured';
    return $columns;
}
add_filter('manage_edit-job_sortable_columns', 'me_recruitment_job_sortable_columns');

// ============================================
// CANDIDATE SUBMISSIONS - CUSTOM DATABASE TABLE
// ============================================

// Create custom table on theme activation
function me_recruitment_create_candidate_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'candidate_submissions';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        candidate_name varchar(255) NOT NULL,
        job_title varchar(255) NOT NULL,
        candidate_email varchar(255) NOT NULL,
        candidate_phone varchar(50) NOT NULL,
        cv_file_path varchar(500) DEFAULT NULL,
        cv_file_name varchar(255) DEFAULT NULL,
        applied_job_id bigint(20) DEFAULT NULL,
        applied_job_title varchar(255) DEFAULT NULL,
        message text DEFAULT NULL,
        status varchar(50) DEFAULT 'new',
        ip_address varchar(100) DEFAULT NULL,
        user_agent text DEFAULT NULL,
        submission_date datetime DEFAULT CURRENT_TIMESTAMP,
        last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        notes text DEFAULT NULL,
        PRIMARY KEY  (id),
        KEY candidate_email (candidate_email),
        KEY applied_job_id (applied_job_id),
        KEY status (status),
        KEY submission_date (submission_date)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Set table version
    update_option('me_recruitment_candidate_table_version', '1.0');
}
add_action('after_switch_theme', 'me_recruitment_create_candidate_table');

// Add Candidate Submissions menu to admin
function me_recruitment_add_candidate_menu() {
    add_menu_page(
        'Candidate Submissions',
        'Candidates',
        'manage_options',
        'candidate-submissions',
        'me_recruitment_candidate_submissions_page',
        'dashicons-groups',
        6
    );
    
    add_submenu_page(
        'candidate-submissions',
        'All Submissions',
        'All Submissions',
        'manage_options',
        'candidate-submissions',
        'me_recruitment_candidate_submissions_page'
    );
    
    add_submenu_page(
        'candidate-submissions',
        'View Submission',
        null, // Hidden from menu
        'manage_options',
        'view-candidate-submission',
        'me_recruitment_view_candidate_submission'
    );
}
add_action('admin_menu', 'me_recruitment_add_candidate_menu');

// Candidate submissions listing page
function me_recruitment_candidate_submissions_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidate_submissions';
    
    // Handle status update
    if (isset($_POST['update_status']) && isset($_POST['submission_id']) && isset($_POST['new_status'])) {
        check_admin_referer('update_candidate_status');
        $submission_id = intval($_POST['submission_id']);
        $new_status = sanitize_text_field($_POST['new_status']);
        
        $wpdb->update(
            $table_name,
            array('status' => $new_status),
            array('id' => $submission_id),
            array('%s'),
            array('%d')
        );
        
        echo '<div class="notice notice-success"><p>Status updated successfully!</p></div>';
    }
    
    // Handle delete
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        check_admin_referer('delete_candidate_' . intval($_GET['id']));
        $id = intval($_GET['id']);
        
        // Delete CV file if exists
        $submission = $wpdb->get_row($wpdb->prepare("SELECT cv_file_path FROM $table_name WHERE id = %d", $id));
        if ($submission && $submission->cv_file_path && file_exists($submission->cv_file_path)) {
            unlink($submission->cv_file_path);
        }
        
        $wpdb->delete($table_name, array('id' => $id), array('%d'));
        echo '<div class="notice notice-success"><p>Submission deleted successfully!</p></div>';
    }
    
    // Get filter parameters
    $status_filter = isset($_GET['status_filter']) ? sanitize_text_field($_GET['status_filter']) : '';
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    
    // Build query
    $where = "WHERE 1=1";
    if ($status_filter) {
        $where .= $wpdb->prepare(" AND status = %s", $status_filter);
    }
    if ($search) {
        $where .= $wpdb->prepare(" AND (candidate_name LIKE %s OR candidate_email LIKE %s OR job_title LIKE %s)", 
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%'
        );
    }
    
    // Pagination
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    // Get total count
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name $where");
    $total_pages = ceil($total_items / $per_page);
    
    // Get submissions
    $submissions = $wpdb->get_results(
        "SELECT * FROM $table_name $where ORDER BY submission_date DESC LIMIT $offset, $per_page"
    );
    
    // Get status counts
    $status_counts = array(
        'all' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
        'new' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'new'"),
        'reviewing' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'reviewing'"),
        'shortlisted' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'shortlisted'"),
        'contacted' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'contacted'"),
        'rejected' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'rejected'"),
    );
    
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Candidate Submissions</h1>
        <a href="<?php echo admin_url('admin.php?page=candidate-submissions&export=csv'); ?>" class="page-title-action">Export to CSV</a>
        <hr class="wp-header-end">
        
        <!-- Status Filters -->
        <ul class="subsubsub">
            <li><a href="<?php echo admin_url('admin.php?page=candidate-submissions'); ?>" <?php echo empty($status_filter) ? 'class="current"' : ''; ?>>All <span class="count">(<?php echo $status_counts['all']; ?>)</span></a> |</li>
            <li><a href="<?php echo admin_url('admin.php?page=candidate-submissions&status_filter=new'); ?>" <?php echo $status_filter === 'new' ? 'class="current"' : ''; ?>>New <span class="count">(<?php echo $status_counts['new']; ?>)</span></a> |</li>
            <li><a href="<?php echo admin_url('admin.php?page=candidate-submissions&status_filter=reviewing'); ?>" <?php echo $status_filter === 'reviewing' ? 'class="current"' : ''; ?>>Reviewing <span class="count">(<?php echo $status_counts['reviewing']; ?>)</span></a> |</li>
            <li><a href="<?php echo admin_url('admin.php?page=candidate-submissions&status_filter=shortlisted'); ?>" <?php echo $status_filter === 'shortlisted' ? 'class="current"' : ''; ?>>Shortlisted <span class="count">(<?php echo $status_counts['shortlisted']; ?>)</span></a> |</li>
            <li><a href="<?php echo admin_url('admin.php?page=candidate-submissions&status_filter=contacted'); ?>" <?php echo $status_filter === 'contacted' ? 'class="current"' : ''; ?>>Contacted <span class="count">(<?php echo $status_counts['contacted']; ?>)</span></a> |</li>
            <li><a href="<?php echo admin_url('admin.php?page=candidate-submissions&status_filter=rejected'); ?>" <?php echo $status_filter === 'rejected' ? 'class="current"' : ''; ?>>Rejected <span class="count">(<?php echo $status_counts['rejected']; ?>)</span></a></li>
        </ul>
        
        <!-- Search Form -->
        <form method="get" style="float: right; margin-top: 10px;">
            <input type="hidden" name="page" value="candidate-submissions">
            <?php if ($status_filter): ?>
            <input type="hidden" name="status_filter" value="<?php echo esc_attr($status_filter); ?>">
            <?php endif; ?>
            <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search candidates...">
            <input type="submit" class="button" value="Search">
        </form>
        
        <div style="clear: both;"></div>
        
        <!-- Submissions Table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="15%">Name</th>
                    <th width="15%">Email</th>
                    <th width="10%">Phone</th>
                    <th width="15%">Job Title</th>
                    <th width="10%">Applied For</th>
                    <th width="8%">CV</th>
                    <th width="10%">Status</th>
                    <th width="12%">Date</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($submissions): ?>
                    <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo $submission->id; ?></td>
                        <td><strong><?php echo esc_html($submission->candidate_name); ?></strong></td>
                        <td><a href="mailto:<?php echo esc_attr($submission->candidate_email); ?>"><?php echo esc_html($submission->candidate_email); ?></a></td>
                        <td><a href="tel:<?php echo esc_attr($submission->candidate_phone); ?>"><?php echo esc_html($submission->candidate_phone); ?></a></td>
                        <td><?php echo esc_html($submission->job_title); ?></td>
                        <td>
                            <?php if ($submission->applied_job_id): ?>
                                <a href="<?php echo get_edit_post_link($submission->applied_job_id); ?>" target="_blank">
                                    <?php echo esc_html($submission->applied_job_title); ?>
                                </a>
                            <?php else: ?>
                                <span class="dashicons dashicons-minus"></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($submission->cv_file_path && file_exists($submission->cv_file_path)): ?>
                                <a href="<?php echo admin_url('admin.php?page=candidate-submissions&download_cv=' . $submission->id); ?>" class="button button-small">
                                    <span class="dashicons dashicons-download"></span> Download
                                </a>
                            <?php else: ?>
                                <span class="dashicons dashicons-minus"></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="post" style="margin: 0;">
                                <?php wp_nonce_field('update_candidate_status'); ?>
                                <input type="hidden" name="submission_id" value="<?php echo $submission->id; ?>">
                                <select name="new_status" onchange="this.form.submit()" class="status-<?php echo esc_attr($submission->status); ?>">
                                    <option value="new" <?php selected($submission->status, 'new'); ?>>New</option>
                                    <option value="reviewing" <?php selected($submission->status, 'reviewing'); ?>>Reviewing</option>
                                    <option value="shortlisted" <?php selected($submission->status, 'shortlisted'); ?>>Shortlisted</option>
                                    <option value="contacted" <?php selected($submission->status, 'contacted'); ?>>Contacted</option>
                                    <option value="rejected" <?php selected($submission->status, 'rejected'); ?>>Rejected</option>
                                </select>
                                <button type="submit" name="update_status" style="display: none;"></button>
                            </form>
                        </td>
                        <td><?php echo date('M j, Y g:i A', strtotime($submission->submission_date)); ?></td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=view-candidate-submission&id=' . $submission->id); ?>" class="button button-small">View</a>
                            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=candidate-submissions&action=delete&id=' . $submission->id), 'delete_candidate_' . $submission->id); ?>" class="button button-small" onclick="return confirm('Are you sure you want to delete this submission?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 20px;">No submissions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total' => $total_pages,
                    'current' => $current_page
                ));
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <style>
            select[name="new_status"] {
                padding: 3px 5px;
                border-radius: 3px;
            }
            .status-new { background: #fff3cd; }
            .status-reviewing { background: #cfe2ff; }
            .status-shortlisted { background: #d1e7dd; }
            .status-contacted { background: #d3d3d3; }
            .status-rejected { background: #f8d7da; }
        </style>
    </div>
    <?php
}

// View individual candidate submission
function me_recruitment_view_candidate_submission() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidate_submissions';
    
    if (!isset($_GET['id'])) {
        wp_die('Invalid submission ID');
    }
    
    $id = intval($_GET['id']);
    $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    
    if (!$submission) {
        wp_die('Submission not found');
    }
    
    // Handle notes update
    if (isset($_POST['update_notes'])) {
        check_admin_referer('update_candidate_notes_' . $id);
        $notes = sanitize_textarea_field($_POST['notes']);
        $wpdb->update(
            $table_name,
            array('notes' => $notes),
            array('id' => $id),
            array('%s'),
            array('%d')
        );
        echo '<div class="notice notice-success"><p>Notes updated successfully!</p></div>';
        $submission->notes = $notes;
    }
    
    ?>
    <div class="wrap">
        <h1>Candidate Submission Details</h1>
        <a href="<?php echo admin_url('admin.php?page=candidate-submissions'); ?>" class="page-title-action">← Back to List</a>
        
        <div style="background: #fff; padding: 20px; margin-top: 20px; border: 1px solid #ccc; border-radius: 5px;">
            <table class="form-table">
                <tr>
                    <th width="20%">Submission ID:</th>
                    <td><strong>#<?php echo $submission->id; ?></strong></td>
                </tr>
                <tr>
                    <th>Candidate Name:</th>
                    <td><strong><?php echo esc_html($submission->candidate_name); ?></strong></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><a href="mailto:<?php echo esc_attr($submission->candidate_email); ?>"><?php echo esc_html($submission->candidate_email); ?></a></td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td><a href="tel:<?php echo esc_attr($submission->candidate_phone); ?>"><?php echo esc_html($submission->candidate_phone); ?></a></td>
                </tr>
                <tr>
                    <th>Job Title:</th>
                    <td><?php echo esc_html($submission->job_title); ?></td>
                </tr>
                <tr>
                    <th>Applied For Job:</th>
                    <td>
                        <?php if ($submission->applied_job_id): ?>
                            <a href="<?php echo get_edit_post_link($submission->applied_job_id); ?>" target="_blank">
                                <?php echo esc_html($submission->applied_job_title); ?> (ID: <?php echo $submission->applied_job_id; ?>)
                            </a>
                            <br>
                            <a href="<?php echo get_permalink($submission->applied_job_id); ?>" target="_blank">View Job →</a>
                        <?php else: ?>
                            General Application (No specific job)
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if ($submission->message): ?>
                <tr>
                    <th>Message:</th>
                    <td><?php echo nl2br(esc_html($submission->message)); ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <th>CV File:</th>
                    <td>
                        <?php if ($submission->cv_file_path && file_exists($submission->cv_file_path)): ?>
                            <a href="<?php echo admin_url('admin.php?page=candidate-submissions&download_cv=' . $submission->id); ?>" class="button">
                                <span class="dashicons dashicons-download"></span> Download CV (<?php echo esc_html($submission->cv_file_name); ?>)
                            </a>
                        <?php else: ?>
                            No CV uploaded
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <span class="status-badge status-<?php echo esc_attr($submission->status); ?>">
                            <?php echo ucfirst($submission->status); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Submission Date:</th>
                    <td><?php echo date('F j, Y g:i A', strtotime($submission->submission_date)); ?></td>
                </tr>
                <tr>
                    <th>Last Updated:</th>
                    <td><?php echo date('F j, Y g:i A', strtotime($submission->last_updated)); ?></td>
                </tr>
                <tr>
                    <th>IP Address:</th>
                    <td><?php echo esc_html($submission->ip_address); ?></td>
                </tr>
                <tr>
                    <th>User Agent:</th>
                    <td style="word-break: break-all;"><?php echo esc_html($submission->user_agent); ?></td>
                </tr>
            </table>
            
            <hr>
            
            <h2>Internal Notes</h2>
            <form method="post">
                <?php wp_nonce_field('update_candidate_notes_' . $id); ?>
                <textarea name="notes" rows="6" style="width: 100%; padding: 10px;"><?php echo esc_textarea($submission->notes); ?></textarea>
                <p>
                    <button type="submit" name="update_notes" class="button button-primary">Update Notes</button>
                </p>
            </form>
        </div>
        
        <style>
            .status-badge {
                padding: 5px 15px;
                border-radius: 3px;
                font-weight: 600;
                display: inline-block;
            }
            .status-new { background: #fff3cd; color: #856404; }
            .status-reviewing { background: #cfe2ff; color: #084298; }
            .status-shortlisted { background: #d1e7dd; color: #0f5132; }
            .status-contacted { background: #d3d3d3; color: #333; }
            .status-rejected { background: #f8d7da; color: #842029; }
        </style>
    </div>
    <?php
}

// Handle CV download
function me_recruitment_download_cv() {
    if (isset($_GET['page']) && $_GET['page'] === 'candidate-submissions' && isset($_GET['download_cv'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'candidate_submissions';
        $id = intval($_GET['download_cv']);
        
        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        
        if ($submission && $submission->cv_file_path && file_exists($submission->cv_file_path)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $submission->cv_file_name . '"');
            header('Content-Length: ' . filesize($submission->cv_file_path));
            readfile($submission->cv_file_path);
            exit;
        }
    }
}
add_action('admin_init', 'me_recruitment_download_cv');

// Handle CSV export
function me_recruitment_export_candidates_csv() {
    if (isset($_GET['page']) && $_GET['page'] === 'candidate-submissions' && isset($_GET['export']) && $_GET['export'] === 'csv') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'candidate_submissions';
        
        $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submission_date DESC", ARRAY_A);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="candidate-submissions-' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, array('ID', 'Name', 'Email', 'Phone', 'Job Title', 'Applied For', 'Status', 'Submission Date'));
        
        // Data
        foreach ($submissions as $submission) {
            fputcsv($output, array(
                $submission['id'],
                $submission['candidate_name'],
                $submission['candidate_email'],
                $submission['candidate_phone'],
                $submission['job_title'],
                $submission['applied_job_title'] ?: 'General',
                ucfirst($submission['status']),
                $submission['submission_date']
            ));
        }
        
        fclose($output);
        exit;
    }
}
add_action('admin_init', 'me_recruitment_export_candidates_csv');

// Process candidate form submission
function me_recruitment_process_candidate_submission() {
    // Verify nonce
    if (!isset($_POST['candidate_cv_nonce']) || !wp_verify_nonce($_POST['candidate_cv_nonce'], 'submit_candidate_cv')) {
        return;
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidate_submissions';
    
    // Sanitize input
    $candidate_name = sanitize_text_field($_POST['candidate_name']);
    $job_title = sanitize_text_field($_POST['job_title']);
    $candidate_email = sanitize_email($_POST['candidate_email']);
    $candidate_phone = sanitize_text_field($_POST['candidate_phone']);
    $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
    $applied_job_id = isset($_POST['applied_job_id']) ? intval($_POST['applied_job_id']) : null;
    
    // Get applied job title if job ID exists
    $applied_job_title = null;
    if ($applied_job_id) {
        $job_post = get_post($applied_job_id);
        if ($job_post && $job_post->post_type === 'job') {
            $applied_job_title = $job_post->post_title;
        }
    }
    
    // Handle file upload
    $cv_file_path = null;
    $cv_file_name = null;
    
    if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = wp_upload_dir();
        $cv_upload_dir = $upload_dir['basedir'] . '/candidate-cvs';
        
        // Create directory if it doesn't exist
        if (!file_exists($cv_upload_dir)) {
            wp_mkdir_p($cv_upload_dir);
            // Add .htaccess to protect directory
            file_put_contents($cv_upload_dir . '/.htaccess', 'deny from all');
        }
        
        $file_info = pathinfo($_FILES['cv_file']['name']);
        $file_extension = strtolower($file_info['extension']);
        
        // Validate file type
        $allowed_extensions = array('pdf', 'doc', 'docx');
        if (!in_array($file_extension, $allowed_extensions)) {
            echo '<div class="alert alert-danger">Invalid file type. Only PDF, DOC, and DOCX files are allowed.</div>';
            return;
        }
        
        // Validate file size (5MB max)
        if ($_FILES['cv_file']['size'] > 5 * 1024 * 1024) {
            echo '<div class="alert alert-danger">File size too large. Maximum 5MB allowed.</div>';
            return;
        }
        
        // Generate unique filename
        $cv_file_name = sanitize_file_name($candidate_name . '_' . time() . '.' . $file_extension);
        $cv_file_path = $cv_upload_dir . '/' . $cv_file_name;
        
        // Move uploaded file
        if (!move_uploaded_file($_FILES['cv_file']['tmp_name'], $cv_file_path)) {
            echo '<div class="alert alert-danger">Failed to upload CV file.</div>';
            return;
        }
        
        $cv_file_name = $_FILES['cv_file']['name']; // Keep original name for display
    }
    
    // Get IP and user agent
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    // Insert into database
    $result = $wpdb->insert(
        $table_name,
        array(
            'candidate_name' => $candidate_name,
            'job_title' => $job_title,
            'candidate_email' => $candidate_email,
            'candidate_phone' => $candidate_phone,
            'cv_file_path' => $cv_file_path,
            'cv_file_name' => $cv_file_name,
            'applied_job_id' => $applied_job_id,
            'applied_job_title' => $applied_job_title,
            'message' => $message,
            'status' => 'new',
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'submission_date' => current_time('mysql'),
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s')
    );
    
    if ($result) {
        // Send email notification to admin
        $admin_email = get_option('admin_email');
        $subject = 'New Candidate Submission - ' . $candidate_name;
        $body = "New candidate submission received:\n\n";
        $body .= "Name: " . $candidate_name . "\n";
        $body .= "Email: " . $candidate_email . "\n";
        $body .= "Phone: " . $candidate_phone . "\n";
        $body .= "Job Title: " . $job_title . "\n";
        if ($applied_job_title) {
            $body .= "Applied For: " . $applied_job_title . "\n";
        }
        $body .= "\nView in admin: " . admin_url('admin.php?page=candidate-submissions');
        
        wp_mail($admin_email, $subject, $body);
        
        // Send confirmation email to candidate
        $candidate_subject = 'Thank you for your application - M&E Recruitment';
        $candidate_body = "Dear " . $candidate_name . ",\n\n";
        $candidate_body .= "Thank you for submitting your CV to M&E Recruitment.\n\n";
        $candidate_body .= "We have received your application and will review it shortly. If your qualifications match our current opportunities, we will be in touch.\n\n";
        $candidate_body .= "Best regards,\nM&E Recruitment Team";
        
        wp_mail($candidate_email, $candidate_subject, $candidate_body);
        
        // Success message
        echo '<div class="alert alert-success" style="background: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3 style="margin-top: 0;">✓ Submission Successful!</h3>
            <p>Thank you for submitting your CV. We have received your application and will be in touch soon.</p>
        </div>';
    } else {
        echo '<div class="alert alert-danger" style="background: #f8d7da; color: #842029; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3 style="margin-top: 0;">✗ Submission Failed</h3>
            <p>Sorry, there was an error submitting your application. Please try again or contact us directly.</p>
        </div>';
    }
}
