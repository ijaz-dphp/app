<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header(); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-1">404</h1>
            <h2>Page Not Found</h2>
            <p class="lead">Sorry, the page you are looking for could not be found.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn submit-cv-btn">Return to Homepage</a>
        </div>
    </div>
</div>

<?php get_footer(); ?>
