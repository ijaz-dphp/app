<?php
/**
 * Template Name: Front Page (Home)
 * The main template file for the homepage
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 col-md-8">
                <div class="hero-content">
                    <h1 class="hero-title">Specialist M&E Recruitment For Projects Under Pressure.</h1>
                    
                    <p class="hero-description">
                        When Mechanical And Electrical Projects Are Under Pressure,<br>
                        The Last Thing You Need Is Uncertainty.
                    </p>
                    
                    <p class="hero-subtitle">
                        We Help M&E Contractors Regain Control By Supplying Fully<br>
                        Vetted Professionals, Once Requirements And Rates Are Agreed.
                    </p>
                    
                    <div class="hero-buttons">
                        <button class="btn submit-cv-btn">Speak To An M&E Recruitment Specialist</button>
                        <button class="btn btn-dark-custom">Submit Your CV As A Candidate</button>
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
            <div class="col-lg-8 col-md-10">
                <div class="video-content text-center">
                    <h2 class="section-title">A Short Introduction</h2>
                    
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
                        <button class="btn submit-cv-btn">Speak To A Recruitment Consultant</button>
                        <button class="btn btn-dark-custom">Speak To A Recruitment Consultant</button>
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
                    <h2 class="features-title">What You Get With Site Ready M&E Recruitment</h2>
                    
                    <h3 class="features-subtitle">You Get Clarity.</h3>
                    <p class="features-description">Before Anyone Is Put Forward, We Confirm:</p>
                    
                    <ul class="features-list">
                        <li class="features-item">
                            <span class="features-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/arrow.svg" alt="Arrow Icon">
                            </span>
                            <span class="features-text">Relevant Mechanical Or Electrical Experience</span>
                        </li>
                        <li class="features-item">
                            <span class="features-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/arrow.svg" alt="Arrow Icon">
                            </span>
                            <span class="features-text">Right To Work</span>
                        </li>
                        <li class="features-item">
                            <span class="features-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/arrow.svg" alt="Arrow Icon">
                            </span>
                            <span class="features-text">Required Qualifications</span>
                        </li>
                        <li class="features-item">
                            <span class="features-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/arrow.svg" alt="Arrow Icon">
                            </span>
                            <span class="features-text">References</span>
                        </li>
                        <li class="features-item">
                            <span class="features-icon">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/icons/arrow.svg" alt="Arrow Icon">
                            </span>
                            <span class="features-text">Availability To Start</span>
                        </li>
                    </ul>
                    
                    <div class="features-footer">
                        <p class="features-result">Once Terms Are Agreed, You Receive A Shortlist Within 48 Hours.</p>
                        <p class="features-promise">No Uncertainty. No Wasted Time.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="features-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/men-hand-electric-image.png" alt="Electrical technician working on control panel" class="img-fluid">
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
                    <h2 class="roles-title">Critical M&E Site<br>
                         Roles Supplied Fast</h2>
                    
                    <div class="roles-tags">
                        <div class="roles-row">
                            <span class="role-tag">Mechanical Supervisors</span>
                            <span class="role-tag">Mechanical Project Managers</span>
                            <span class="role-tag">Electrical Supervisors</span>
                        </div>
                        
                        <div class="roles-row">
                            <span class="role-tag">Electrical Project Managers</span>
                            <span class="role-tag">M&E Project Managers</span>
                            <span class="role-tag">Commissioning Engineers</span>
                            <span class="role-tag">Site Managers</span>
                        </div>
                        
                        <div class="roles-row">
                            <span class="role-tag">Construction Managers With M&E Experience</span>
                            <span class="role-tag">Contract And Permanent</span>
                            <span class="role-tag">+ Many More</span>
                        </div>
                    </div>
                    
                    <div class="roles-cta">
                        <button class="btn submit-cv-btn">View Open M&E Roles</button>
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
                    <h2 class="how-it-works-title">How It Works</h2>
                    
                    <div class="how-it-works-steps">
                        <div class="row align-items-start">
                            <div class="col-lg-4 col-md-4">
                                <div class="step-item">
                                    <div class="step-number">
                                        <span>1</span>
                                    </div>
                                    <div class="step-connector step-connector-right"></div>
                                    <div class="card-box">
                                        <h3 class="step-title">Understand Your Site</h3>
                                        <p class="step-description">We Take A Clear Brief Covering Scope, Programme Risk, And Start Dates.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4">
                                <div class="step-item">
                                    <div class="step-number">
                                        <span>2</span>
                                    </div>
                                    <div class="step-connector step-connector-left"></div>
                                    <div class="step-connector step-connector-right"></div>
                                    <div class="card-box">                                        
                                    <h3 class="step-title">Shortlist Site Ready Professionals</h3>
                                    <p class="step-description">Only Vetted And Available Candidates Are Submitted.</p>
                                </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4">
                                <div class="step-item">
                                    <div class="step-number">
                                        <span>3</span>
                                    </div>
                                    <div class="step-connector step-connector-left"></div>
                                    <div class="card-box">
                                    <h3 class="step-title">Mobilise Without Delay</h3>
                                    <p class="step-description">We Support Fast Onboarding So Your Programme Keeps Moving.</p>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="how-it-works-cta">
                        <button class="btn submit-cv-btn">Speak To An M&E Recruitment Specialist</button>
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
            <div class="col-lg-6 col-md-6">
                <div class="professionals-image">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/professional-working-on-laptop.png" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="professionals-content">
                    <h2 class="professionals-title">For M&E Site Professionals</h2>
                    
                    <p class="professionals-description">
                        If You Are A Mechanical Or Electrical Supervisor, Manager, Or Commissioning Engineer Looking For Consistent Work On Well Run Projects, We Want To Hear From You.
                    </p>
                    
                    <p class="professionals-subtitle">
                        We Work With Professionals Who Value Clear Start Dates, Honest Communication, And Proper M&E Projects.
                    </p>
                    
                    <p class="professionals-subtitle">
                        Register Once And Speak To Someone Who Understands Your Experience And What You Are Looking For Next.
                    </p>
                    
                    <div class="professionals-cta">
                        <button class="btn submit-cv-btn">Submit Your CV</button>
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
                    <h2 class="latest-roles-title text-center">View Our Latest Roles</h2>
                    
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
                        <button class="btn submit-cv-btn">View All Jobs</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Latest Roles Section End -->

<?php get_footer(); ?>
