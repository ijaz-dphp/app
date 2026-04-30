<?php

/**
 * Template Name: Contact us Page
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
                    <span>Contact Us</span>
                </li>
            </ol>
        </nav>
    </div>
</div>
<!-- Get In Touch Section -->
<section class="get-in-touch-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="contact-form-wrapper">
                    <h2 class="contact-title"><?php the_field('form_heading'); ?></h2>

                    <?php echo do_shortcode('[contact-form-7 id="da15bae" title="Get in touch"]'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Get In Touch Section End -->
 <?php get_footer(); ?>