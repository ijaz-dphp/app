<?php
/**
 * Template Name: Candidate Page
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
                    <span>Candidate</span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Candidate Hero Section -->
<section class="candidate-hero-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Content -->
            <div class="col-lg-7 col-md-6 position-relative">
                <div class="row">
                    <div class="col-md-8">
                        
                            <?php
                            if (have_posts()) :
                                while (have_posts()) : the_post();
                                    the_content();
                                endwhile;
                            endif;
                            ?>
                       
                    </div>                                            
                </div>

                <div class="hand-image-container">
                    <?php if (has_post_thumbnail()) : ?>
                        <img class="hand-questionmark" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="<?php the_title_attribute(); ?>" />
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Right Form -->
            <div class="col-lg-5 col-md-6">
                <div class="cv-form-container">
                    <div class="cv-form">
                        <h2 class="form-title">Submit <br class="hidden-desktop">Your CV</h2>
                        
                        <!-- AJAX messages will appear here -->
                        
                        <form id="candidate-cv-form" class="candidate-cv-form" method="post" enctype="multipart/form-data">
                            <?php wp_nonce_field('submit_candidate_cv', 'candidate_cv_nonce'); ?>
                            
                            <div class="form-group">
                                <input type="text" name="candidate_name" class="form-control" placeholder="Name *" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" name="job_title" class="form-control" placeholder="Job Title *" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="email" name="candidate_email" class="form-control" placeholder="E-Mail *" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="tel" name="candidate_phone" class="form-control" placeholder="Phone *" required>
                            </div>
                            
                            <div class="form-group">
                                <textarea name="message" class="form-control" rows="3" placeholder="Cover Letter / Message (Optional)"></textarea>
                            </div>
                            
                            <div class="form-group file-upload-group">
                                <label for="cvFile" class="file-upload-label">
                                    <i class="fas fa-paperclip"></i>
                                    <span class="file-text">Attach Your CV *</span>
                                    <span class="file-placeholder" id="fileName">No file chosen</span>
                                </label>
                                <input type="file" id="cvFile" name="cv_file" class="file-input" accept=".pdf,.doc,.docx" required onchange="updateFileName(this)">
                            </div>
                            
                            <p class="file-note">* File should be in .pdf, .doc or .docx format (Max 5MB)</p>
                            
                            <input type="hidden" name="applied_job_id" value="<?php echo isset($_GET['job_id']) ? intval($_GET['job_id']) : ''; ?>">
                            
                            <button type="submit" name="submit_cv" class="submit-btn">
                                <span class="btn-loader"></span>
                                <span class="btn-text">Submit Application</span>
                            </button>
                        </form>
                        
                        <script>
                        function updateFileName(input) {
                            const fileName = input.files[0] ? input.files[0].name : 'No file chosen';
                            document.getElementById('fileName').textContent = fileName;
                        }
                        </script>
                        
                        <style>
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
                        
                        /* Spinner Animation */
                        .loader-spinner {
                            width: 70px;
                            height: 70px;
                            border: 5px solid #f3f3f3;
                            border-top: 5px solid #3498db;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
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
                        
                        /* Button Loader (inline spinner) */
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
                        
                        .submit-btn.loading .btn-loader {
                            display: inline-block;
                        }
                        
                        .submit-btn.loading .btn-text {
                            opacity: 0.8;
                        }
                        
                        .submit-btn:disabled {
                            opacity: 0.8;
                            cursor: not-allowed;
                        }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
