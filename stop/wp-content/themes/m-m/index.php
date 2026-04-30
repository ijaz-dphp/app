<?php
/**
 * The main template file
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="entry-meta">
                            Posted on <?php echo get_the_date(); ?> by <?php the_author(); ?>
                        </div>
                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn btn-dark-custom">Read More</a>
                    </article>
                <?php endwhile; ?>
                
                <?php the_posts_navigation(); ?>
            <?php else : ?>
                <p><?php _e('No posts found.', 'me-recruitment'); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
