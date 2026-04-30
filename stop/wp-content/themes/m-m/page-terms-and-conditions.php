<?php

/**
 * Template Name: Terms and Conditions Page
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
                    <span><?php the_title(); ?></span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Terms and Conditions Content Section -->
<section class="terms-content-section" style="min-height: 60vh; padding: 60px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="terms-content-wrapper">
                    <h1 class="terms-page-title"><?php the_title(); ?></h1>
                    
                    <div class="terms-content">
                        <?php 
                        while (have_posts()) : the_post();
                            if (get_the_content()) {
                                the_content();
                            } else {
                                echo '<p>Content coming soon...</p>';
                            }
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Terms and Conditions Content Section End -->

<?php get_footer(); ?>