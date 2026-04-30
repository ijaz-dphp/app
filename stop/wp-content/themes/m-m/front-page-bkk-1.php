<?php

/**
 * Template Name: Front Page  backup 01(Home)
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
<section class="video-introduction-section">
    <div class="container">
        <div class="row justify-content-center">
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
            <div class="col-lg-6 col-md-6">
                <div class="video-content text-center">
                    <h2 class="section-title"><?php the_field('short_introduction_text'); ?></h2>

                    <div class="video-wrapper">
                        <div class="video-thumbnail" onclick="playVideo('dQw4w9WgXcQ')">
                            <img src="https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Industrial construction site" class="video-placeholder">
                            <div class="play-button">
                                <i class="fas fa-play"></i>
                            </div>
                            <div class="video-controls">
                                <span class="video-time">02:00/04:55</span>
                                <div class="video-controls-icons">
                                    <i class="fas fa-expand"></i>
                                    <i class="fas fa-cog"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="video-buttons">
                        <a href="<?php the_field('speak_button_link'); ?>" class="btn submit-cv-btn"><?php the_field('speak_button_text'); ?></a>
                        <a href="<?php the_field('speak_button_two_link'); ?>" class="btn btn-dark-custom"><?php the_field('speak_button_two'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Video Introduction Section End -->

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
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
            <div class="col-lg-6 col-md-6">
                <div class="features-image">
                    <img src="<?php the_field('feature_image'); ?>" alt="Electrical technician working on control panel" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Features Section End -->

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

<!-- How It Works Section -->
<section class="how-it-works-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="how-it-works-content text-center">
                    <h2 class="how-it-works-title"><?php the_field('how_its_work_title'); ?></h2>

                    <div class="how-it-works-steps">
                        <div class="row align-items-start">
                            <?php 
                            if( have_rows('how_its_work') ): 
                                $step_count = 1;
                                $total_steps = count(get_field('how_its_work'));
                                
                                while( have_rows('how_its_work') ): the_row(); 
                                    $heading = get_sub_field('heading');
                                    $sub_heading = get_sub_field('sub_heading');
                            ?>
                                <div class="col-lg-4 col-md-4">
                                    <div class="step-item">
                                        <div class="step-number">
                                            <span><?php echo $step_count; ?></span>
                                        </div>
                                        <?php if($step_count == 1): ?>
                                            <div class="step-connector step-connector-right"></div>
                                        <?php elseif($step_count == $total_steps): ?>
                                            <div class="step-connector step-connector-left"></div>
                                        <?php else: ?>
                                            <div class="step-connector step-connector-left"></div>
                                            <div class="step-connector step-connector-right"></div>
                                        <?php endif; ?>
                                        <div class="card-box">
                                            <h3 class="step-title"><?php echo esc_html($heading); ?></h3>
                                            <p class="step-description"><?php echo esc_html($sub_heading); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                    $step_count++;
                                endwhile; 
                            endif; 
                            ?>
                        </div>
                    </div>

                    <div class="how-it-works-cta">
                        <a href="<?php the_field('how_it_work_button_link'); ?>" class="btn submit-cv-btn"><?php the_field('how_it_work_button_text'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- How It Works Section End -->

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
<script>
    function playVideo(videoId) {
alert("dddd");
        const videoWrapper = document.querySelector('.video-thumbnail');

        if (videoWrapper) {

            const iframe = `<iframe width="100%" height="400" src="https://www.youtube.com/embed/${videoId}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;

            videoWrapper.innerHTML = iframe;

        }

    }
</script>
<?php get_footer(); ?>