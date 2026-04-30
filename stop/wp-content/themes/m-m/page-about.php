<?php

/**
 * Template Name: About Page
 */

get_header(); ?>

<div class="breadcrumbs">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span>About Us</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- About Hero Section -->
<section class="about-hero-section">
    <div class="about-container">
        <div class="about-content-wrapper">
            <!-- Left Content -->
            <div class="about-left-content m-order-1">
                <div class="about-content">
                    <h1 class="about-title">
                        <?php the_field('hero_heading'); ?>
                    </h1>

                    <?php the_field('hero_description'); ?>
                </div>
            </div>

            <!-- Right Image -->
            <div class="about-right-image m-order-2">
                <img src="<?php the_field('hero_image'); ?>" alt="Professional using tablet in electrical facility" class="about-image">
            </div>
        </div>
    </div>
</section>

<section class="for-professionals-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 m-order-2">
                <div class="professionals-image">
                    <img src="<?php the_field('section_two_image'); ?>" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 m-order-1">
                <div class="professionals-content">
                    <h2 class="professionals-title"><?php the_field('section_two_heading'); ?></h2>

                    <p class="professionals-description">
                        <?php the_field('section_two_sub_heading'); ?>
                    </p>

                    <ul class="features-list">
                        <?php if (have_rows('section_two_features')): ?>
                            <?php while (have_rows('section_two_features')): the_row();
                                $feature_icon = get_sub_field('feature_icon');
                                $feature_text = get_sub_field('feature_text');
                            ?>
                                <li class="features-item">
                                    <span class="features-icon">
                                        <img src="<?php echo esc_url($feature_icon); ?>" alt="<?php echo esc_attr($feature_text); ?>">
                                    </span>
                                    <span class="features-text"><?php echo esc_html($feature_text); ?></span>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>

                    <h3 class="step-title"><?php the_field('sub_heading'); ?></h3>
                    <p class="professionals-description">
                        <?php the_field('sub_description'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="features-content">
                    <h2 class="features-title"><?php the_field('focus_heading'); ?></h2>

                    <h3 class="features-subtitle"><?php the_field('focus_sub_heading'); ?></h3>
                    <p class="features-description"><?php the_field('focus_description'); ?></p>

                    <ul class="features-list">
                        <?php if (have_rows('focus_features')): ?>
                            <?php while (have_rows('focus_features')): the_row();
                                $feature_icon = get_sub_field('focus_icon');
                                $feature_text = get_sub_field('focus_text');
                            ?>
                                <li class="features-item">
                                    <span class="features-icon">
                                        <img src="<?php echo esc_url($feature_icon); ?>" alt="<?php echo esc_attr($feature_text); ?>">
                                    </span>
                                    <span class="features-text"><?php echo esc_html($feature_text); ?></span>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>

                    <div class="features-footer">
                        <p class="features-result">
                           <?php the_field('focus_content'); ?></p>
                        <p class="features-promise"> <?php the_field('focus_sub_content'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="features-image">
                    <img src="<?php the_field('focus_image'); ?>" alt="Electrical technician working on control panel" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="how-it-works-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="how-it-works-content text-center">
                    <h2 class="how-it-works-title"><?php the_field('how_we_work_heading'); ?></h2>
                    <p class="sub-text">
                      <?php the_field('how_we_work_sub_text'); ?>
                    </p>

                    <div class="how-it-works-steps">
                        <div class="row align-items-start">
                            <?php if (have_rows('how_we_work_repeat')): ?>
                                <?php while (have_rows('how_we_work_repeat')): the_row();
                                    $heading = get_sub_field('heading');
                                ?>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="step-item">
                                            <div class="card-box">
                                                <h3 class="step-title"><?php echo esc_html($heading); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="sub-text">
                       <?php the_field('how_we_work_description'); ?>
                    </p>

                    <div class="how-it-works-cta">
                        <a href="<?php the_field('button_link'); ?>" class="btn submit-cv-btn"><?php the_field('button_text'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="for-professionals-section section-we-work-with">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6  m-order-2">
                <div class="professionals-image">
                    <img src="<?php the_field('professionals_image'); ?>" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6 col-md-6 m-order-1">
                <div class="professionals-content">
                    <h2 class="professionals-title"><?php the_field('profession_heading'); ?></h2>

                    <p class="professionals-description">
                       <?php the_field('profession_description'); ?>
                    </p>

                    <ul class="features-list">
                        <?php if (have_rows('profession_repeat')): ?>
                            <?php while (have_rows('profession_repeat')): the_row();
                                $icon = get_sub_field('profession_icon');
                                $heading = get_sub_field('profession_heading');
                            ?>
                                <li class="features-item">
                                    <span class="features-icon">
                                        <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($heading); ?>">
                                    </span>
                                    <span class="features-text"><?php echo esc_html($heading); ?></span>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                    <p class="professionals-description">
                        <?php the_field('profession_sub_text'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// If page has content, display it
if (have_posts()) :
    while (have_posts()) : the_post();
        the_content();
    endwhile;
endif;
?>

<?php get_footer(); ?>