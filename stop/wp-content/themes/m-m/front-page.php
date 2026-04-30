<?php

/**
 * Template Name: Front Page (Home)
 * The main template file for the homepage
 */

get_header(); ?>

<!-- Hero Section -->
<?php $hero_bg_image = get_field('hero_image'); ?>
<section class="hero-section" <?php if ($hero_bg_image): ?>style="background-image: url('<?php echo esc_url($hero_bg_image); ?>');"<?php endif; ?>>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-8">
                <div class="hero-content">
                    <h1 class="hero-title"><?php the_field('hero_title'); ?></h1>

                    <p class="hero-description">
                        <?php the_field('hero_description'); ?>
                    </p>

                    <p class="hero-subtitle">
                        <?php the_field('hero_sub_title'); ?>
                    </p>

                    <div class="hero-buttons">
                        <a href="<?php the_field('hero_button_link'); ?>" class="btn submit-cv-btn"><?php the_field('hero_button_text'); ?></a>
                        <a href="<?php the_field('hero_button_two_link'); ?>" class="btn btn-dark-custom"><?php the_field('hero_button_two_text'); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-4">
                <!-- Background image handled by CSS -->
            </div>
        </div>
    </div>
</section>
<!-- Hero Section  End-->

<!-- Video Introduction Section -->
<section class="video-introduction-section bg-gray">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-6">
                              <div class="features-content">
                    <h2 class="features-title"><?php the_field('feature_title'); ?></h2>

                    <h3 class="features-subtitle"><?php the_field('feature_sub_title'); ?></h3>
                    <p class="features-description"><?php the_field('feature_description'); ?></p>

                    <ul class="features-list">
                        <?php if( have_rows('features') ): ?>
                            <?php while( have_rows('features') ): the_row(); 
                                $feature_text = get_sub_field('feature_text');
                                $feature_images = get_sub_field('feature_images');
                            ?>
                                <li class="features-item">
                                    <span class="features-icon">
                                        <img src="<?php echo esc_url($feature_images); ?>" alt="<?php echo esc_attr($feature_text); ?>">
                                    </span>
                                    <span class="features-text"><?php echo esc_html($feature_text); ?></span>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>

                    <div class="features-footer">
                        <p class="features-result"><?php the_field('feature_result'); ?></p>
                        <p class="features-promise"><?php the_field('feature_promise'); ?></p>
                    </div>
                </div>
            </div>
            <?php 
            $video_thumbnail_link = 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
            $video_url = get_field('video_url_');
            $video_thumbnail_link = get_field('video_thumbnail_link');
            $video_id = 'dQw4w9WgXcQ'; // Default video ID
            
            if ($video_url) {
                // Extract YouTube video ID from various URL formats
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                    $video_id = $matches[1];
                }
                 
            }
            ?>
            <div class="col-lg-6 col-md-6">
                <div class="video-content text-center">
        
                    <div class="video-wrapper">
                        <div class="video-thumbnail" onclick="playVideo('<?php echo esc_attr($video_id); ?>')">
                            <img src="<?php echo esc_url($video_thumbnail_link); ?>" alt="Industrial construction site" class="video-placeholder">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                            <!-- <div class="video-controls">
                                <span class="video-time">02:00/04:55</span>
                                <div class="video-controls-icons">
                                    <i class="fas fa-expand"></i>
                                    <i class="fas fa-cog"></i>
                                </div>
                            </div> -->
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
<!-- Video Introduction Section End -->



<!-- Roles Section -->
<section class="roles-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="roles-content text-center">
                    <h2 class="roles-title"><?php the_field('role_title'); ?></h2>

                    <div class="roles-tags">
                        <?php 
                        if( have_rows('roles') ): 
                            $counter = 0;
                            $row_pattern = array(3, 4, 3); // First row: 3, Second row: 4, Third row: 3
                            $current_row = 0;
                            $items_in_current_row = 0;
                            
                            echo '<div class="roles-row">';
                            
                            while( have_rows('roles') ): the_row(); 
                                $role_tags = get_sub_field('role_tags');
                                
                                // Check if we need to start a new row
                                if ($items_in_current_row >= $row_pattern[$current_row % 3]) {
                                    echo '</div><div class="roles-row">';
                                    $current_row++;
                                    $items_in_current_row = 0;
                                }
                        ?>
                            <span class="role-tag"><?php echo esc_html($role_tags); ?></span>
                        <?php 
                                $items_in_current_row++;
                            endwhile;
                            
                            echo '</div>';
                        endif; 
                        ?>
                    </div>

                    <div class="roles-cta">
                        <a href="<?php the_field('role_button_link'); ?>" class="btn submit-cv-btn"><?php the_field('role_button_text'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Roles Section End -->

<!-- Get In Touch Section -->
<section class="get-in-touch-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="contact-form-wrapper">
                    <h2 class="contact-title"><?php the_field('get_in_touch_heading'); ?></h2>
                    
                    <?php echo do_shortcode('[contact-form-7 id="da15bae" title="Get in touch"]'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Get In Touch Section End -->



<!-- For Professionals Section -->
<section class="for-professionals-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 m-order-2">
                <div class="professionals-image">
                    <img src="<?php the_field('section_image'); ?>" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 m-order-1">
                <div class="professionals-content">
                    <h2 class="professionals-title"><?php the_field('professional_title'); ?></h2>

                    <?php the_field('prefessional_description'); ?>

                    <div class="professionals-cta">
                        <a href="<?php the_field('professional_button_link'); ?>" class="btn submit-cv-btn"><?php the_field('professional_button_text'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- For Professionals Section End -->

<!-- Latest Roles Section -->
<section class="latest-roles-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="latest-roles-content">
                    <h2 class="latest-roles-title text-center">View Our <br class="hidden-desktop"> Latest Roles</h2>

                    <div class="row">
                        <?php
                        // Query latest job posts from custom post type
                        $args = array(
                            'post_type' => 'job',
                            'posts_per_page' => 6,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        );
                        $job_query = new WP_Query($args);

                        if ($job_query->have_posts()) :
                            while ($job_query->have_posts()) : $job_query->the_post();
                                $job_location = get_post_meta(get_the_ID(), '_job_location', true);
                                $job_salary = get_post_meta(get_the_ID(), '_job_salary', true);
                                $job_contract_type = get_post_meta(get_the_ID(), '_job_contract_type', true);
                        ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="job-card">
                                        <div class="job-posted">Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></div>
                                        <h3 class="job-title"><?php the_title(); ?></h3>
                                        <div class="job-details">
                                            <div class="job-detail">
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/pin.svg" alt="" class="img-fluid">
                                                <span><?php echo esc_html($job_location ?: 'Not Specified'); ?></span>
                                            </div>
                                            <div class="job-detail">
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/euro.svg" alt="" class="img-fluid">
                                                <span><?php echo esc_html($job_salary ?: 'Competitive'); ?></span>
                                            </div>
                                            <div class="job-detail">
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/permanent.svg" alt="" class="img-fluid">
                                                <span><?php echo esc_html($job_contract_type ?: 'Permanent'); ?></span>
                                            </div>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-dark-custom job-btn">Find Out More</a>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            ?>
                            <!-- Static Jobs as Fallback -->
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="job-card">
                                    <div class="job-posted">Posted 1 Day Ago</div>
                                    <h3 class="job-title">Project Support Administrator</h3>
                                    <div class="job-details">
                                        <div class="job-detail">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/pin.svg" alt="" class="img-fluid">
                                            <span>Dartford</span>
                                        </div>
                                        <div class="job-detail">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/euro.svg" alt="" class="img-fluid">
                                            <span>£26,000 – £32,000</span>
                                        </div>
                                        <div class="job-detail">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/icons/permanent.svg" alt="" class="img-fluid">
                                            <span>Permanent</span>
                                        </div>
                                    </div>
                                    <button class="btn btn-dark-custom job-btn">Find Out More</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="latest-roles-cta text-center">
                        <button class="btn submit-cv-btn" onclick="location.href='<?php echo esc_url(home_url('/jobs/')); ?>'">View All Jobs</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Roles Section End -->




<?php get_footer(); ?>