<!-- Job Application Sidebar -->
<div class="job-sidebar">
    <div class="cv-form-container sidebar-form">
        <div class="cv-form">
            <h2 class="form-title">Apply For This Job</h2>
            <p class="form-subtitle">Submit your CV and we'll get back to you within 24 hours</p>
            
            <?php
            // Handle form submission - function does its own validation
            me_recruitment_process_candidate_submission();
            ?>
            
            <form id="cvSubmissionFormSidebar" method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('submit_candidate_cv', 'candidate_cv_nonce'); ?>
                
                <div class="form-group">
                    <input type="text" name="candidate_name" class="form-control" placeholder="Your Name *" required>
                </div>
                
                <div class="form-group">
                    <input type="text" name="job_title" class="form-control" placeholder="Job Title *" value="<?php echo esc_attr(get_the_title()); ?>" readonly>
                </div>
                
                <div class="form-group">
                    <input type="email" name="candidate_email" class="form-control" placeholder="Email Address *" required>
                </div>
                
                <div class="form-group">
                    <input type="tel" name="candidate_phone" class="form-control" placeholder="Phone Number *" required>
                </div>
                
                <div class="form-group">
                    <textarea name="message" class="form-control" rows="3" placeholder="Cover Letter / Message (Optional)"></textarea>
                </div>
                
                <div class="form-group file-upload-group">
                    <label for="cvFileSidebar" class="file-upload-label">
                        <i class="fas fa-paperclip"></i>
                        <span class="file-text">Attach Your CV *</span>
                        <span class="file-placeholder" id="fileNameSidebar">No file chosen</span>
                    </label>
                    <input type="file" id="cvFileSidebar" name="cv_file" class="file-input" accept=".pdf,.doc,.docx" required onchange="updateFileNameSidebar(this)">
                </div>
                
                <p class="file-note">* File: .pdf, .doc or .docx (Max 5MB)</p>
                
                <input type="hidden" name="applied_job_id" value="<?php echo get_the_ID(); ?>">
                
                <button type="submit" name="submit_cv_sidebar" class="submit-btn">
                    <span class="btn-loader"></span>
                    <span class="btn-text">Submit Application</span>
                </button>
            </form>
            
            <!-- Loader Overlay -->
            <div class="form-loader-overlay" id="formLoaderSidebar">
                <div class="loader-spinner"></div>
                <div class="loader-text">Submitting Your Application...</div>
                <div class="loader-subtext">Please wait while we process your CV</div>
            </div>
        </div>
    </div>
    
    <!-- Contact Information Card -->
    <div class="contact-info-card mt-4">
        <h3 class="contact-title">Need Help?</h3>
        <p class="contact-subtitle">Our recruitment team is here to help</p>
        
        <?php 
        $contact_email = get_option('me_contact_email', get_theme_mod('contact_email', 'info@m&erecruitment.com'));
        $contact_phone = get_option('me_contact_phone', get_theme_mod('phone_number', '0208 298 9977'));
        ?>
        
        <div class="contact-detail">
            <i class="fas fa-envelope"></i>
            <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
        </div>
        
        <div class="contact-detail">
            <i class="fas fa-phone"></i>
            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a>
        </div>
        
        <div class="contact-hours">
            <p><strong>Opening Hours:</strong></p>
            <p>Monday - Friday: 9:00 AM - 5:30 PM</p>
            <p>Saturday - Sunday: Closed</p>
        </div>
    </div>
    
    <!-- Browse More Jobs -->
    <div class="browse-jobs-card mt-4">
        <h3 class="browse-title">Browse More Jobs</h3>
        
        <?php
        $categories = get_terms(array(
            'taxonomy' => 'job_category',
            'hide_empty' => true,
        ));
        
        if (!empty($categories) && !is_wp_error($categories)) :
        ?>
        <ul class="job-category-list">
            <?php foreach ($categories as $category) : ?>
                <li>
                    <a href="<?php echo esc_url(get_term_link($category)); ?>">
                        <i class="fas fa-chevron-right"></i>
                        <?php echo esc_html($category->name); ?>
                        <span class="job-count">(<?php echo $category->count; ?>)</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        
        <a href="<?php echo esc_url(get_post_type_archive_link('job')); ?>" class="btn btn-outline-dark w-100 mt-3">
            View All Jobs
        </a>
    </div>
</div>

<script>
function updateFileNameSidebar(input) {
    const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
    const fileSize = input.files[0] ? ' (' + (input.files[0].size / 1024).toFixed(1) + ' KB)' : '';
    document.getElementById('fileNameSidebar').textContent = fileName + fileSize;
}

// Form submission loader
document.getElementById('cvSubmissionFormSidebar').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('.submit-btn');
    const loader = document.getElementById('formLoaderSidebar');
    
    // Add loading state to button
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    // Show overlay loader
    loader.classList.add('active');
});
</script>

<style>
/* Job Sidebar Styles - Matching Candidate Page */
.job-sidebar {
    position: sticky;
    top: 20px;
}

.sidebar-form {
    background: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.sidebar-form .form-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.sidebar-form .form-subtitle {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1.5rem;
}

/* Contact Info Card */
.contact-info-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.contact-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #333;
}

.contact-subtitle {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 1rem;
}

.contact-detail {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
    padding: 10px;
    background: white;
    border-radius: 5px;
}

.contact-detail i {
    color: #007bff;
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

.contact-detail a {
    color: #333;
    text-decoration: none;
    font-size: 0.95rem;
}

.contact-detail a:hover {
    color: #007bff;
}

.contact-hours {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

.contact-hours p {
    margin-bottom: 5px;
    font-size: 0.9rem;
    color: #666;
}

.contact-hours strong {
    color: #333;
}

/* Browse Jobs Card */
.browse-jobs-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.browse-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: #333;
}

.job-category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.job-category-list li {
    margin-bottom: 10px;
}

.job-category-list a {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.job-category-list a:hover {
    background: #007bff;
    color: white;
    transform: translateX(5px);
}

.job-category-list i {
    margin-right: 10px;
    font-size: 0.8rem;
}

.job-count {
    margin-left: auto;
    background: white;
    color: #007bff;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}

.job-category-list a:hover .job-count {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Loader Overlay - Same as Candidate Page */
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
    width: 60px;
    height: 60px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-text {
    margin-top: 20px;
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
}

.loader-subtext {
    margin-top: 5px;
    font-size: 0.9rem;
    color: #666;
}

/* Button Loading State */
.submit-btn {
    position: relative;
}

.submit-btn.loading .btn-loader {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-right: 8px;
}

.submit-btn .btn-loader {
    display: none;
}

.submit-btn.loading {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 991px) {
    .job-sidebar {
        position: static;
        margin-top: 30px;
    }
}
</style>
