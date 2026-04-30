// Common JavaScript functionality for all pages



// Function to load header and footer

function loadIncludes() {

    // Load header

    fetch('includes/header.html')

        .then(response => response.text())

        .then(data => {

            document.getElementById('header-placeholder').innerHTML = data;

            // Initialize theme toggle after header is loaded

            initializeThemeToggle();

        })

        .catch(error => console.error('Error loading header:', error));



    // Load footer

    fetch('includes/footer.html')

        .then(response => response.text())

        .then(data => {

            document.getElementById('footer-placeholder').innerHTML = data;

        })

        .catch(error => console.error('Error loading footer:', error));

}



// Theme toggle functionality

function initializeThemeToggle() {

    const themeToggle = document.getElementById('themeToggle');

    const body = document.body;

    

    if (themeToggle) {

        const icon = themeToggle.querySelector('i');

        

        // Check for saved theme preference or default to light mode

        const currentTheme = localStorage.getItem('theme') || 'light';

        if (currentTheme === 'dark') {

            body.classList.add('dark-theme');

            if (icon) icon.className = 'fas fa-sun';

        }

        

        themeToggle.addEventListener('click', () => {

            body.classList.toggle('dark-theme');

            

            if (body.classList.contains('dark-theme')) {

                localStorage.setItem('theme', 'dark');

                if (icon) icon.className = 'fas fa-sun';

            } else {

                localStorage.setItem('theme', 'light');

                if (icon) icon.className = 'fas fa-moon';

            }

        });

    }

}



// Video play function for home page

function playVideo(videoId) {

    const videoWrapper = document.querySelector('.video-thumbnail');

    if (videoWrapper) {

        const iframe = `<iframe width="100%" height="400" src="https://www.youtube.com/embed/${videoId}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;

        videoWrapper.innerHTML = iframe;

    }

}



// Initialize everything when DOM is loaded

document.addEventListener('DOMContentLoaded', function() {

    loadIncludes();

});



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