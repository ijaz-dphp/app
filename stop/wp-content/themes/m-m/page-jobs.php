<?php
/**
 * Template Name: Jobs Page
 */

get_header(); ?>
<style>
header .jobs-active a{
        background-color: var(--accent-color);
}

</style>

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
                    <span>Jobs</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- M&E Opportunities Hero Section -->
<section class="me-opportunities-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="hero-content text-center">
                    <h1 class="hero-title">Current M&E Opportunities</h1>
                    
                    <div class="hero-description">
                        <p class="subtitle">We Recruit Exclusively For Mechanical And Electrical Roles Across Contract And Permanent Positions.</p>
                        
                        <p class="opportunities-text">All Opportunities Listed Here Are Live Or Upcoming Projects With M&E Contractors We Actively Work With.</p>
                        
                        <div class="action-text">
                            <p class="qualified-text">If You Are Qualified, Reliable, And Ready For Your Next Role,</p>
                            <p class="apply-text">You Can Apply Directly Or Register Your CV For Future Opportunities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="latest-roles-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="latest-roles-content">
                    <div class="row">
                        <?php
                        // Query all job posts from custom post type
                        $args = array(
                            'post_type' => 'job',
                            'posts_per_page' => -1, // Show all jobs
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
                        <div class="col-12 text-center">
                            <p>No jobs available at the moment. Please check back later.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
