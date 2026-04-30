<?php
/**
 * The template for displaying a single post
 */

get_header(); ?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h1><?php the_title(); ?></h1>
                    
                    <div class="entry-meta mb-3">
                        <span>Posted on <?php echo get_the_date(); ?></span> |
                        <span>By <?php the_author(); ?></span>
                    </div>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail mb-4">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Job Meta Information -->
                    <div class="job-meta mt-4">
                        <?php
                        $location = get_post_meta(get_the_ID(), 'job_location', true);
                        $salary = get_post_meta(get_the_ID(), 'job_salary', true);
                        $type = get_post_meta(get_the_ID(), 'job_type', true);
                        
                        if ($location || $salary || $type) :
                        ?>
                        <h3>Job Details</h3>
                        <ul class="list-unstyled">
                            <?php if ($location) : ?>
                                <li><strong>Location:</strong> <?php echo esc_html($location); ?></li>
                            <?php endif; ?>
                            <?php if ($salary) : ?>
                                <li><strong>Salary:</strong> <?php echo esc_html($salary); ?></li>
                            <?php endif; ?>
                            <?php if ($type) : ?>
                                <li><strong>Job Type:</strong> <?php echo esc_html($type); ?></li>
                            <?php endif; ?>
                        </ul>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('candidate'))); ?>" class="btn submit-cv-btn">Apply for this Position</a>
                        </div>
                    </div>
                </article>
            <?php
                endwhile;
            endif;
            ?>
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
