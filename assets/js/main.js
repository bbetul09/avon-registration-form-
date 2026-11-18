document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggler
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuToggle.classList.toggle('active');

            // Toggle hamburger icon
            const icon = menuToggle.querySelector('i');
            if (icon) {
                icon.className = navLinks.classList.contains('active') ? 'fas fa-times' : 'fas fa-bars';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!menuToggle.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                menuToggle.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                if (icon) icon.className = 'fas fa-bars';
            }
        });

        // Close menu on scroll
        window.addEventListener('scroll', () => {
            if (navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
                menuToggle.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                if (icon) icon.className = 'fas fa-bars';
            }
        }, { passive: true });
    }

    // Close mobile menu when a link is clicked
    const links = document.querySelectorAll('.nav-links a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks) {
                navLinks.classList.remove('active');
                if (menuToggle) {
                    menuToggle.classList.remove('active');
                    const icon = menuToggle.querySelector('i');
                    if (icon) icon.className = 'fas fa-bars';
                }
            }
        });
    });

    // 2. AJAX Form Submissions (Register and Contact forms)
    const handleFormSubmit = (formId, submitUrl) => {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const alertSuccess = form.querySelector('.alert-success');
            const alertError = form.querySelector('.alert-error');
            
            // Reset alerts
            if (alertSuccess) alertSuccess.style.display = 'none';
            if (alertError) alertError.style.display = 'none';
            
            // Disable button and show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.innerText;
                submitBtn.innerText = 'Gönderiliyor...';
            }

            const formData = new FormData(form);
            formData.append('js_check', 'ok'); // bot koruması: gerçek tarayıcı kontrolü

            try {
                const response = await fetch(submitUrl, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    if (alertSuccess) {
                        alertSuccess.innerText = result.message;
                        alertSuccess.style.display = 'block';
                    }
                    form.reset();
                } else {
                    if (alertError) {
                        alertError.innerText = result.message;
                        alertError.style.display = 'block';
                    }
                }
            } catch (error) {
                console.error('Hata:', error);
                if (alertError) {
                    alertError.innerText = 'Bir sistem hatası oluştu, lütfen daha sonra tekrar deneyin.';
                    alertError.style.display = 'block';
                }
            } finally {
                // Restore button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerText = submitBtn.dataset.originalText;
                }
            }
        });
    };

    // Initialize forms
    handleFormSubmit('register-form', 'submit-form.php');
    handleFormSubmit('contact-form', 'submit-form.php');

    // 3. Testimonial Slider
    const testimonialTrack = document.querySelector('.testimonial-track');
    const slides = document.querySelectorAll('.testimonial-slide');
    const dotsContainer = document.querySelector('.testimonial-dots');

    if (testimonialTrack && slides.length > 0) {
        let currentSlide = 0;
        let slideInterval;
        const slideCount = slides.length;

        // Create dots dynamically
        dotsContainer.innerHTML = '';
        for (let i = 0; i < slideCount; i++) {
            const dot = document.createElement('div');
            dot.classList.add('testimonial-dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }

        const dots = document.querySelectorAll('.testimonial-dot');

        const updateDots = () => {
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        };

        const goToSlide = (n) => {
            currentSlide = (n + slideCount) % slideCount;
            testimonialTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateDots();
            resetInterval();
        };

        const nextSlide = () => {
            goToSlide(currentSlide + 1);
        };

        const startInterval = () => {
            slideInterval = setInterval(nextSlide, 5000); // Auto slide every 5s
        };

        const resetInterval = () => {
            clearInterval(slideInterval);
            startInterval();
        };

        // Initialize slider
        startInterval();
    }

    // 4. Blog Category Filter (Client-side page filtering)
    const filterButtons = document.querySelectorAll('.filter-btn');
    const blogCards = document.querySelectorAll('.blog-card');

    if (filterButtons.length > 0 && blogCards.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Toggle active class on buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filterValue = button.getAttribute('data-filter');

                blogCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (filterValue === 'all' || cardCategory === filterValue) {
                        card.style.display = 'flex';
                        // Trigger fade in animation
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transition = 'opacity 0.4s ease';
                        }, 50);
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
});
