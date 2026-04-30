<?php
/**
 * The template for displaying single job posts
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
                <li class="breadcrumb-item">
                    <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="breadcrumb-link">
                        <span>Jobs</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span><?php the_title(); ?></span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5 mb-0">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                
                // Get custom meta fields
                $job_location = get_post_meta(get_the_ID(), '_job_location', true);
                $job_salary = get_post_meta(get_the_ID(), '_job_salary', true);
                $job_contract_type = get_post_meta(get_the_ID(), '_job_contract_type', true);
                $job_experience = get_post_meta(get_the_ID(), '_job_experience', true);
                $job_qualifications = get_post_meta(get_the_ID(), '_job_qualifications', true);
                $job_responsibilities = get_post_meta(get_the_ID(), '_job_responsibilities', true);
                $job_requirements = get_post_meta(get_the_ID(), '_job_requirements', true);
                $job_deadline = get_post_meta(get_the_ID(), '_job_deadline', true);
                $job_contact_email = get_post_meta(get_the_ID(), '_job_contact_email', true);
                $job_contact_phone = get_post_meta(get_the_ID(), '_job_contact_phone', true);
            ?>
                <article id="job-<?php the_ID(); ?>" <?php post_class(); ?>>
                    
                    <!-- Job Header -->
                    <div class="job-header mb-4">
                        <h1 class="job-title-single mb-3"><?php the_title(); ?></h1>
                        
                        <div class="job-meta-info d-flex flex-wrap gap-3 mb-4">
                            <?php if ($job_location) : ?>
                            <div class="job-meta-item">
                                <!-- <i class="fas fa-map-marker-alt me-2"></i> -->
                                  <img src="<?php echo get_template_directory_uri(); ?>/images/icons/pin.svg" alt="" class="img-fluid">
                                <strong>Location:</strong> <?php echo esc_html($job_location); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($job_salary) : ?>
                            <div class="job-meta-item">
                                <!-- <i class="fas fa-pound-sign me-2"></i> -->
                                 <img src="<?php echo get_template_directory_uri(); ?>/images/icons/euro.svg" alt="" class="img-fluid">
                                <strong>Salary:</strong> <?php echo esc_html($job_salary); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($job_contract_type) : ?>
                            <div class="job-meta-item">
                                <!-- <i class="fas fa-briefcase me-2"></i> -->
                                        <img src="<?php echo get_template_directory_uri(); ?>/images/icons/permanent.svg" alt="" class="img-fluid">                                 
                                <strong>Type:</strong> <?php echo esc_html($job_contract_type); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($job_experience) : ?>
                            <div class="job-meta-item">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Experience:</strong> <?php echo esc_html($job_experience); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="job-posted-date text-muted mb-3">
                            <i class="fas fa-calendar me-2"></i>
                            Posted <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?>
                        </div>
                        
                        <?php if ($job_deadline) : ?>
                        <div class="job-deadline alert alert-info">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Application Deadline:</strong> <?php echo date('F j, Y', strtotime($job_deadline)); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="job-thumbnail mb-4">
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Job Description -->
                    <div class="job-description mb-4">
                        <h2 class="section-heading">Job Description</h2>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <!-- Key Responsibilities -->
                    <?php if ($job_responsibilities) : ?>
                    <div class="job-responsibilities mb-4">
                        <h2 class="section-heading">Key Responsibilities</h2>
                        <ul class="job-list features-list">
                            <?php
                            $responsibilities = explode("\n", $job_responsibilities);
                            foreach ($responsibilities as $responsibility) {
                                if (trim($responsibility)) {
                                    echo '<li class="features-item">
                                    <span class="features-icon">
                                        <img src="' . get_template_directory_uri() . '/images/icons/arrow.svg" alt="Arrow Icon">
                                    </span>
                                    <span class="features-text">' . esc_html(trim($responsibility)) . '</span>
                                    </li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Requirements -->
                    <?php if ($job_requirements) : ?>
                    <div class="job-requirements mb-4">
                        <h2 class="section-heading">Requirements</h2>
                        <ul class="job-list features-list">
                            <?php
                            $requirements = explode("\n", $job_requirements);
                            foreach ($requirements as $requirement) {
                                if (trim($requirement)) {
                                    echo '<li class="features-item">
                                    <span class="features-icon">
                                        <img src="' . get_template_directory_uri() . '/images/icons/arrow.svg" alt="Arrow Icon">
                                    </span>
                                     <span class="features-text">' . esc_html(trim($requirement)) . '</span></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Qualifications -->
                    <?php if ($job_qualifications) : ?>
                    <div class="job-qualifications mb-4">
                        <h2 class="section-heading">Qualifications Required</h2>
                        <ul class="job-list features-list">
                            <?php
                            $qualifications = explode("\n", $job_qualifications);
                            foreach ($qualifications as $qualification) {
                                if (trim($qualification)) {
                                    echo '<li class="features-item"> <span class="features-icon">
                                        <img src="' . get_template_directory_uri() . '/images/icons/arrow.svg" alt="Arrow Icon">
                                    </span><span class="features-text">' . esc_html(trim($qualification)) . '</span></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Job Categories/Tags -->
                    <?php
                    $categories = get_the_terms(get_the_ID(), 'job_category');
                    $types = get_the_terms(get_the_ID(), 'job_type');
                    ?>
                    
                    <?php if ($categories || $types) : ?>
                    <div class="job-taxonomy mb-4">
                        <?php if ($categories && !is_wp_error($categories)) : ?>
                        <div class="mb-2">
                            <strong>Categories:</strong>
                            <?php
                            $category_names = array();
                            foreach ($categories as $category) {
                                $category_names[] = '<span class="badge bg-primary me-1">' . esc_html($category->name) . '</span>';
                            }
                            echo implode('', $category_names);
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($types && !is_wp_error($types)) : ?>
                        <div>
                            <strong>Job Types:</strong>
                            <?php
                            $type_names = array();
                            foreach ($types as $type) {
                                $type_names[] = '<span class="badge bg-secondary me-1">' . esc_html($type->name) . '</span>';
                            }
                            echo implode('', $type_names);
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Apply Section with Inline Form -->
                    <div class="job-apply-section">
                        <h2 class="section-heading">Apply for this Position</h2>
                        <div class="graphic-text">
                        <p class="mb-3">Fill out the form below to apply for this role. We'll review your application and get back to you soon.</p>
                        </div>
                        
                        <!-- AJAX messages will appear here -->
                        
                        <!-- Contact Info (Collapsible) -->
                        <?php if ($job_contact_email || $job_contact_phone) : ?>
                        <div class="contact-info-toggle mb-3">
                            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse" data-bs-target="#contactDetails">
                                <i class="fas fa-phone-alt me-2"></i>Prefer to contact us directly? Click here
                            </button>
                            <div class="collapse mt-2" id="contactDetails">
                                <div class="contact-details p-3 bg-white rounded">
                                    <?php if ($job_contact_email) : ?>
                                    <p class="mb-2">
                                        <i class="fas fa-envelope me-2"></i>
                                        <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($job_contact_email); ?>"><?php echo esc_html($job_contact_email); ?></a>
                                    </p>
                                    <?php endif; ?>
                                    
                                    <?php if ($job_contact_phone) : ?>
                                    <p class="mb-0">
                                        <i class="fas fa-phone me-2"></i>
                                        <strong>Phone:</strong> <a href="tel:<?php echo esc_attr(str_replace(' ', '', $job_contact_phone)); ?>"><?php echo esc_html($job_contact_phone); ?></a>
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Application Form -->
                        <div class="job-application-form mt-4">
                        <form id="candidate-cv-form" class="candidate-cv-form" method="post" enctype="multipart/form-data">
                                <?php wp_nonce_field('submit_candidate_cv', 'candidate_cv_nonce'); ?>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="candidateName" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="candidateName" name="candidate_name" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="candidateEmail" class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="candidateEmail" name="candidate_email" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="candidatePhone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="candidatePhone" name="candidate_phone" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="jobTitle" class="form-label">Applying For <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="jobTitle" name="job_title" value="<?php the_title(); ?>" readonly>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="coverMessage" class="form-label">Cover Letter / Message (Optional)</label>
                                        <textarea class="form-control" id="coverMessage" name="message" rows="4" placeholder="Tell us why you're interested in this position..."></textarea>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="cvFile" class="form-label">Upload Your CV <span class="text-danger">*</span></label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" class="form-control" id="cvFile" name="cv_file" accept=".pdf,.doc,.docx" required onchange="updateFileNameInline(this)">
                                            <div class="file-name-display mt-2 text-muted" id="fileNameDisplay">
                                                <small><i class="fas fa-info-circle me-1"></i>Accepted formats: PDF, DOC, DOCX (Max 5MB)</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="applied_job_id" value="<?php echo get_the_ID(); ?>">
                                    
                                    <div class="col-12">
                                        <button type="submit" name="submit_cv" class="btn submit-cv-btn w-100">
                                            <span class="btn-loader"></span>
                                            <span class="btn-text"><i class="fas fa-paper-plane me-2"></i>Submit Application</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </article>
            <?php
                endwhile;
            endif;
            ?>
        </div>
    </div>
</div>

<style>
.job-title-single {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}
.text-muted{
        color: var(--text-primary) !important;
}
.job-meta-item {
    padding: 0.5rem 0;
}

.section-heading {
    padding-bottom: 0.5rem;
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 5px;
    color: var(--text-primary);
}
.dark-theme .job-apply-section p
,.dark-theme .contact-info-toggle .btn-link
,.dark-theme  .job-application-form .form-label
,.dark-theme .file-name-display
,.dark-theme  i.fas.fa-phone-alt
{
      color: var(--button-text-primary);
}
.job-apply-section  .section-heading{
        text-align: center;
}
.dark-theme .job-apply-section  .section-heading{
  color: var(--button-text-primary);
}
.dark-theme .job-apply-section   .text-muted {
    color: var(--button-text-primary) !important;
}
.job-list {
    list-style: none;
    padding-left: 0;
}


.related-jobs-list {
    list-style: none;
    padding: 0;
}

.related-job-item {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 5px;
}

.related-job-item h4 a {
    color: var(--text-primary);
    text-decoration: none;
}

.related-job-item h4 a:hover {
    color: #007bff;
}

.widget-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #007bff;
}

/* Job Application Form Styles */
.job-application-form {

    padding: 1.5rem;

}

.job-application-form .form-label {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}





.file-upload-wrapper {
    position: relative;
}

.file-name-display {
    padding: 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}

.contact-info-toggle .btn-link {
     color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
}

.contact-info-toggle .btn-link:hover {
    text-decoration: underline;
}

.contact-details {
    border-left: 3px solid #007bff;
}

/* Loader Overlay */
.form-loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    z-index: 9999;
}

.form-loader-overlay.active {
    display: flex;
}

.loader-spinner {
    width: 70px;
    height: 70px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

.bg-primary{
    background-color: var(--accent-color) !important;
        color: var(--text-primary) !important;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-text {
    margin-top: 25px;
    color: #2c3e50;
    font-weight: 700;
    font-size: 20px;
    text-align: center;
}

.loader-subtext {
    margin-top: 10px;
    color: #7f8c8d;
    font-size: 15px;
    text-align: center;
}

/* Button Loader */
.btn-loader {
    display: none;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
    margin-right: 8px;
    vertical-align: middle;
}

.job-application-form  .submit-cv-btn.loading .btn-loader {
    display: inline-block;
}

.job-application-form .submit-cv-btn.loading .btn-text {
    opacity: 0.8;
}

.job-application-form .submit-cv-btn:disabled {
    opacity: 0.8;
    cursor: not-allowed;
}
.job-application-form .submit-cv-btn{
    width: 100%;
    padding: 15px;
    background-color: var(--button-text-primary);
    color: #ffffff;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}
/* Success/Error Messages */
.alert {
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

body.dark-theme  .bg-light{
    background-color: #1a1a1a !important;
}

.job-meta-item i,.job-posted-date,i.fas.fa-phone-alt
,i.fas.fa-calendar.me-2
{
    color: #f8de30;
    font-size: 20px;
}
i.fas.fa-phone-alt{
    color: var(--text-primary);
}
.job-taxonomy {
    display: flex;
    column-gap: 40px;
        justify-content: space-between;    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.job-apply-section {
    background: var(--accent-color);
    border-radius: 300px 300px 0% 0%;
    padding: 75px 44px 60px;
    width: 100%;
    position: relative;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}


.contact-info-toggle {
    max-width: 350px;
    margin-left: auto;
    margin-right: auto;
}
.graphic-text {
    max-width: 384px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}
.form-control {
    width: 100%;
    padding: 15px 20px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 500;
    background-color: #ffffff;
    color: var(--text-primary);
    transition: all 0.3s ease;
    box-shadow: none;
}
.job-meta-item .form-control{
        color: var(--text-secondary);
}
header .jobs-active a{
        background-color: var(--accent-color);
}

    body.dark-theme   header .jobs-active a {
           color: var(--button-text-primary) !important;
    }

</style>

<script>
function updateFileNameInline(input) {
    const fileDisplay = document.getElementById('fileNameDisplay');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
        
        fileDisplay.innerHTML = `
            <small class="text-success">
                <i class="fas fa-check-circle me-1"></i>
                <strong>Selected:</strong> ${fileName} (${fileSize} MB)
            </small>
        `;
    } else {
        fileDisplay.innerHTML = `
            <small>
                <i class="fas fa-info-circle me-1"></i>
                Accepted formats: PDF, DOC, DOCX (Max 5MB)
            </small>
        `;
    }
}

// Form submission loader
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('candidate-cv-form');
    if (form) {
        // Form is now handled by AJAX in common.js
        console.log('CV form found - AJAX handler will manage submission');
    }
    
    // Scroll to form if there's an error or success message
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');
    if (alerts.length > 0) {
        const applySection = document.querySelector('.job-apply-section');
        if (applySection) {
            applySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});
</script>

<?php get_footer(); ?>
