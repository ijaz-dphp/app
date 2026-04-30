<?php
/**
 * The template for displaying job archive
 */

get_header(); ?>
<style>
header .jobs-active a{
        background-color: var(--accent-color);
}
.post-type-archive-job .jobs-pagination .page-numbers a:hover, .jobs-pagination .page-numbers .current{
          background-color: var(--accent-color);
    border-color: var(--accent-color);
}


    body.dark-theme   header .jobs-active a {
           color: var(--button-text-primary) !important;
    }


</style>

    <!-- jobs CSS -->
     <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jobs-styles.css?v=<?php echo filemtime(get_template_directory() . '/css/jobs-styles.css'); ?>">
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
                    <span>All Jobs</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- M&E Opportunities Hero Section -->
<section class="me-opportunities-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-10">
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
            <!-- Main Content -->
            <div class="col-lg-10">
                <div class="latest-roles-content">
                    
                    <!-- Filter Section -->
                    <div class="jobs-filter mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    <?php
                                    global $wp_query;
                                    $total_jobs = $wp_query->found_posts;
                                    echo '<strong>' . $total_jobs . '</strong> job' . ($total_jobs != 1 ? 's' : '') . ' found';
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <?php
                                // Get current taxonomy if on taxonomy archive
                                if (is_tax('job_category')) {
                                    $term = get_queried_object();
                                    echo '<span class="badge bg-primary">Category: ' . esc_html($term->name) . '</span>';
                                } elseif (is_tax('job_type')) {
                                    $term = get_queried_object();
                                    echo '<span class="badge bg-secondary">Type: ' . esc_html($term->name) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php
                        if (have_posts()) :
                            while (have_posts()) : the_post();
                                $job_location = get_post_meta(get_the_ID(), '_job_location', true);
                                $job_salary = get_post_meta(get_the_ID(), '_job_salary', true);
                                $job_contract_type = get_post_meta(get_the_ID(), '_job_contract_type', true);
                                $job_featured = get_post_meta(get_the_ID(), '_job_featured', true);
                        ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="job-card <?php echo $job_featured == '1' ? 'featured-job' : ''; ?>">
                                <?php if ($job_featured == '1') : ?>
                                    <div class="featured-badge">
                                        <i class="fas fa-star"></i> Featured
                                    </div>
                                <?php endif; ?>
                                <div class="job-posted">Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></div>
                                <h3 class="job-title"><?php the_title(); ?></h3>
                                
                                <?php if (has_excerpt()) : ?>
                                    <p class="job-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                <?php endif; ?>
                                
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
                        else :
                        ?>
                        <div class="col-12 text-center">
                            <p>No jobs available at the moment. Please check back later.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($wp_query->max_num_pages > 1) : ?>
                    <div class="jobs-pagination mt-5">
                        <?php
                        echo paginate_links(array(
                            'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
                            'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
                            'type' => 'list',
                            'class' => 'pagination-list'
                        ));
                        ?>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.featured-job {
    border: 2px solid #ffc107;
    position: relative;
}

.featured-badge {
    position: absolute;
    top: -10px;
    right: 10px;
    background: #ffc107;
    color: #000;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
    z-index: 10;
}

.job-excerpt {
    font-size: 0.9rem;
    color: #666;
    margin: 10px 0;
}

.jobs-filter {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
}

.jobs-pagination {
    display: flex;
    justify-content: center;
}

.jobs-pagination .page-numbers {
    display: inline-flex;
    list-style: none;
    padding: 0;
    gap: 5px;
}

.jobs-pagination .page-numbers li {
    display: inline-block;
}

.jobs-pagination .page-numbers a,
.jobs-pagination .page-numbers span {
    padding: 8px 15px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
    background: #fff;
    transition: all 0.3s ease;
}


</style>

<?php get_footer(); ?>
