




// Video play function for home page





// Initialize everything when DOM is loaded

document.addEventListener('DOMContentLoaded', function() {



    

    // Initialize CV Forms immediately

    initializeCVForms();

    

    // Use event delegation as backup - capture ALL form submissions

    document.addEventListener('submit', function(e) {

        const form = e.target;

        

        // Check if this is a CV submission form

        if (form.classList.contains('candidate-cv-form') || 

            form.id === 'candidate-cv-form' ||

            form.querySelector('input[name="submit_cv"]') ||

            form.querySelector('input[name="candidate_cv_nonce"]')) {

            

            console.log('Form submission captured via event delegation!');

            

            // If not already handled by AJAX, handle it now

            if (!form.dataset.ajaxInitialized) {

                console.log('Form not initialized - handling now');

                e.preventDefault();

                e.stopImmediatePropagation();

                handleFormSubmit(form, e);

                return false;

            }

        }

    }, true); // Use capture phase

});



// Also try after window load (backup)

window.addEventListener('load', function() {

    initializeCVForms();

});



// Initialize CV Form AJAX submissions

function initializeCVForms() {

    console.log('Initializing CV Forms...');

    

    // Select all possible CV forms

    const cvForms = document.querySelectorAll(

        '.candidate-cv-form, ' +

        '#candidate-cv-form, ' +

        'form[id*="cv"], ' +

        'form[action*="candidate"], ' +

        'form:has(input[name="submit_cv"]), ' +

        'form:has(input[name="candidate_cv_nonce"])'

    );

    

    console.log('Found ' + cvForms.length + ' CV forms');

    

    cvForms.forEach(function(form, index) {

        console.log('Processing form ' + (index + 1) + ':', form.id || 'no-id', form.className);

        

        // Skip if already initialized

        if (form.dataset.ajaxInitialized) {

            console.log('Form already initialized, skipping');

            return;

        }

        form.dataset.ajaxInitialized = 'true';

        

        form.addEventListener('submit', function(e) {

            console.log('Form submit event triggered!');

            e.preventDefault();

            e.stopImmediatePropagation();

            

            handleFormSubmit(form, e);

            

            return false; // Extra safety

        }, true); // Use capture phase

        

        console.log('Form ' + (index + 1) + ' initialized successfully');

    });

}



// Handle form submission (extracted to reusable function)

function handleFormSubmit(form, event) {

    if (event) {

        event.preventDefault();

        event.stopImmediatePropagation();

    }

    

    console.log('Handling form submission...');

    

    const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

    const messageDiv = form.querySelector('.form-message') || createMessageDiv(form);

    const formData = new FormData(form);

    

    console.log('Form data prepared');

    

    // Add AJAX action

    formData.append('action', 'submit_candidate_cv');

    

    // Check if recruitmentAjax exists

    if (typeof recruitmentAjax === 'undefined') {

        console.error('recruitmentAjax not defined!');

        messageDiv.className = 'form-message alert alert-danger';

        messageDiv.innerHTML = '<strong>✗ Error!</strong><br>AJAX configuration missing. Please refresh the page.';

        messageDiv.style.display = 'block';

        return false;

    }

    

    console.log('Sending AJAX request to:', recruitmentAjax.ajaxurl);

    

    // Disable submit button and show loading

    if (submitButton) {

        submitButton.disabled = true;

        const originalText = submitButton.value || submitButton.textContent;

        submitButton.dataset.originalText = originalText;

        

        if (submitButton.tagName === 'BUTTON') {

            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';

        } else {

            submitButton.value = 'Submitting...';

        }

    }

    

    messageDiv.style.display = 'none';

    

    // Send AJAX request

    fetch(recruitmentAjax.ajaxurl, {

        method: 'POST',

        body: formData

    })

    .then(response => {

        console.log('Response received:', response.status);

        return response.json();

    })

    .then(data => {

        console.log('Response data:', data);

        

        if (data.success) {

            // Success

            messageDiv.className = 'form-message alert alert-success';

            messageDiv.innerHTML = '<strong>✓ Success!</strong><br>' + data.data.message;

            messageDiv.style.display = 'block';

            

            // Reset form

            form.reset();

            

            // Scroll to message

            messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

        } else {

            // Error

            messageDiv.className = 'form-message alert alert-danger';

            messageDiv.innerHTML = '<strong>✗ Error!</strong><br>' + data.data.message;

            messageDiv.style.display = 'block';

            

            // Scroll to message

            messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

        }

    })

    .catch(error => {

        console.error('AJAX Error:', error);

        

        // Network error

        messageDiv.className = 'form-message alert alert-danger';

        messageDiv.innerHTML = '<strong>✗ Error!</strong><br>Network error. Please check your connection and try again.';

        messageDiv.style.display = 'block';

    })

    .finally(() => {

        // Re-enable submit button

        if (submitButton) {

            submitButton.disabled = false;

            const originalText = submitButton.dataset.originalText || 'Submit Application';

            

            if (submitButton.tagName === 'BUTTON') {

                submitButton.innerHTML = originalText;

            } else {

                submitButton.value = originalText;

            }

        }

    });

    

    return false;

}



// Create message div if it doesn't exist

function createMessageDiv(form) {

    const messageDiv = document.createElement('div');

    messageDiv.className = 'form-message';

    messageDiv.style.display = 'none';

    messageDiv.style.marginBottom = '20px';

    form.insertBefore(messageDiv, form.firstChild);

    return messageDiv;

}



// Set active navigation link based on current page

function setActiveNavigation() {

    const currentPage = window.location.pathname.split('/').pop();

    const navLinks = document.querySelectorAll('.nav-link');

    

    navLinks.forEach(link => {

        const href = link.getAttribute('href');

        if (href === currentPage || (currentPage === '' && href === 'home.html')) {

            link.classList.add('active');

        }

    });

}