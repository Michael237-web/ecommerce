document.addEventListener("DOMContentLoaded", function() {
    const slides = document.querySelectorAll(".slide");
    const nextBtn = document.querySelector(".slider-btn.next");
    const prevBtn = document.querySelector(".slider-btn.prev");
    const dotsContainer = document.querySelector(".slider-dots");
    const progressBar = document.querySelector(".slider-progress-bar");

    if (slides.length === 0) return;

    let current = 0;
    let sliderInterval;
    let isTransitioning = false;
    const slideInterval = 3000;
    const transitionDuration = 800;

    function getTransitionType(index) {
        return slides[index].getAttribute('data-transition') || 'fade';
    }

    if (dotsContainer) {
        dotsContainer.innerHTML = '';
        slides.forEach((_, index) => {
            const dot = document.createElement("span");
            dot.classList.add("dot");
            if (index === 0) dot.classList.add("active");
            dot.dataset.index = index;
            dot.addEventListener("click", function() {
                if (!isTransitioning && parseInt(this.dataset.index) !== current) {
                    goToSlide(parseInt(this.dataset.index));
                    resetSlider();
                }
            });
            dotsContainer.appendChild(dot);
        });
    }

    function goToSlide(index, direction = 'next') {
        if (isTransitioning || index === current) return;
        if (index < 0 || index >= slides.length) return;

        isTransitioning = true;

        const currentSlide = slides[current];
        const nextSlide = slides[index];
        const transitionType = getTransitionType(index);

        currentSlide.classList.remove('active');
        
        if (transitionType === 'slide') {
            if (direction === 'next') {
                nextSlide.style.transform = 'translateX(100%)';
            } else {
                nextSlide.style.transform = 'translateX(-100%)';
            }
            nextSlide.style.opacity = '0';
            nextSlide.style.visibility = 'visible';
            
            void nextSlide.offsetHeight;
            
            nextSlide.classList.add('active');
            nextSlide.style.transform = 'translateX(0)';
            nextSlide.style.opacity = '1';
            
            currentSlide.style.transform = direction === 'next' ? 'translateX(-100%)' : 'translateX(100%)';
            currentSlide.style.opacity = '0';
        } else if (transitionType === 'zoom') {
            nextSlide.style.transform = 'scale(1.1)';
            nextSlide.style.opacity = '0';
            nextSlide.style.visibility = 'visible';
            
            void nextSlide.offsetHeight;
            
            nextSlide.classList.add('active');
            nextSlide.style.transform = 'scale(1)';
            nextSlide.style.opacity = '1';
            
            currentSlide.style.transform = 'scale(1.05)';
            currentSlide.style.opacity = '0';
        } else {
            nextSlide.style.opacity = '0';
            nextSlide.style.visibility = 'visible';
            
            void nextSlide.offsetHeight;
            
            nextSlide.classList.add('active');
            nextSlide.style.opacity = '1';
            
            currentSlide.style.opacity = '0';
        }

        setTimeout(() => {
            currentSlide.style.transform = '';
            currentSlide.style.opacity = '';
            currentSlide.style.visibility = '';
            nextSlide.style.transform = '';
            nextSlide.style.opacity = '';
            nextSlide.style.visibility = '';
            
            slides.forEach(s => s.classList.remove('prev-slide'));
            
            isTransitioning = false;
        }, transitionDuration);

        current = index;

        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll(".dot");
            dots.forEach((dot, i) => {
                dot.classList.toggle("active", i === index);
            });
        }

        if (progressBar) {
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.transition = `width ${slideInterval}ms linear`;
                progressBar.style.width = '100%';
            }, 50);
        }
    }

    function nextSlide() {
        if (isTransitioning) return;
        let next = current + 1;
        if (next >= slides.length) next = 0;
        goToSlide(next, 'next');
    }

    function prevSlide() {
        if (isTransitioning) return;
        let prev = current - 1;
        if (prev < 0) prev = slides.length - 1;
        goToSlide(prev, 'prev');
    }

    function startSlider() {
        if (sliderInterval) {
            clearInterval(sliderInterval);
        }
        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.transition = `width ${slideInterval}ms linear`;
                progressBar.style.width = '100%';
            }, 50);
        }
        sliderInterval = setInterval(nextSlide, slideInterval);
    }

    function resetSlider() {
        clearInterval(sliderInterval);
        startSlider();
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", function(e) {
            e.preventDefault();
            if (!isTransitioning) {
                nextSlide();
                resetSlider();
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", function(e) {
            e.preventDefault();
            if (!isTransitioning) {
                prevSlide();
                resetSlider();
            }
        });
    }

    document.addEventListener("keydown", function(e) {
        if (e.key === "ArrowRight" || e.key === "ArrowDown") {
            e.preventDefault();
            if (!isTransitioning) {
                nextSlide();
                resetSlider();
            }
        } else if (e.key === "ArrowLeft" || e.key === "ArrowUp") {
            e.preventDefault();
            if (!isTransitioning) {
                prevSlide();
                resetSlider();
            }
        }
    });

    let touchStartX = 0;
    let touchEndX = 0;
    const slider = document.querySelector(".hero-slider");

    if (slider) {
        slider.addEventListener("touchstart", function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        slider.addEventListener("touchend", function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    if (!isTransitioning) {
                        nextSlide();
                        resetSlider();
                    }
                } else {
                    if (!isTransitioning) {
                        prevSlide();
                        resetSlider();
                    }
                }
            }
        }, { passive: true });
    }

    if (slider) {
        slider.addEventListener("mouseenter", function() {
            clearInterval(sliderInterval);
            if (progressBar) {
                progressBar.style.transition = 'none';
            }
        });
        slider.addEventListener("mouseleave", function() {
            startSlider();
        });
    }

    setTimeout(function() {
        slides.forEach((slide, index) => {
            if (index === 0) {
                slide.classList.add('active');
                slide.style.opacity = '1';
                slide.style.visibility = 'visible';
            } else {
                slide.classList.remove('active');
                slide.style.opacity = '0';
                slide.style.visibility = 'hidden';
            }
        });
        startSlider();
    }, 100);

    document.addEventListener("visibilitychange", function() {
        if (!document.hidden) {
            resetSlider();
        }
    });
});

function toggleMenu() {
    const nav = document.getElementById('mainNav');
    if (nav) {
        nav.classList.toggle('active');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');
    
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            if (window.innerWidth <= 991) {
                e.preventDefault();
                const parent = this.closest('.dropdown');
                if (parent) {
                    parent.classList.toggle('active');
                }
            }
        });
    });
    
    const userDropdown = document.querySelector('.user-dropdown');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 991) {
                e.preventDefault();
                this.classList.toggle('active');
            }
        });
    }
});

document.addEventListener('click', function(event) {
    const nav = document.getElementById('mainNav');
    const toggle = document.querySelector('.menu-toggle');
    
    if (nav && toggle && window.innerWidth <= 991) {
        const isClickInsideNav = nav.contains(event.target);
        const isClickOnToggle = toggle.contains(event.target);
        
        if (!isClickInsideNav && !isClickOnToggle) {
            nav.classList.remove('active');
            
            document.querySelectorAll('.dropdown.active').forEach(el => {
                el.classList.remove('active');
            });
            document.querySelectorAll('.user-dropdown.active').forEach(el => {
                el.classList.remove('active');
            });
        }
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const nav = document.getElementById('mainNav');
        if (nav) {
            nav.classList.remove('active');
        }
        document.querySelectorAll('.dropdown.active').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll('.user-dropdown.active').forEach(el => {
            el.classList.remove('active');
        });
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 991) {
        const nav = document.getElementById('mainNav');
        if (nav) {
            nav.classList.remove('active');
        }
        document.querySelectorAll('.dropdown.active').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll('.user-dropdown.active').forEach(el => {
            el.classList.remove('active');
        });
    }
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== "#") {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    });
});

document.querySelectorAll('.btn-add-cart').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const originalText = this.textContent;
        this.textContent = 'Added! ✓';
        this.style.background = '#16a34a';
        setTimeout(() => {
            this.textContent = originalText;
            this.style.background = '#2563eb';
        }, 2000);
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('alertMessage');
    if (alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        }, 3000);
    }
});

function closeAlert() {
    const alert = document.getElementById('alertMessage');
    if (alert) {
        alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';
        setTimeout(function() {
            alert.style.display = 'none';
        }, 300);
    }
}

const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();

        if (name.length < 2) {
            e.preventDefault();
            showFieldError('name', 'Please enter your full name.');
            return false;
        }

        if (!email || !email.includes('@') || !email.includes('.')) {
            e.preventDefault();
            showFieldError('email', 'Please enter a valid email address.');
            return false;
        }

        if (subject.length < 3) {
            e.preventDefault();
            showFieldError('subject', 'Please enter a subject (minimum 3 characters).');
            return false;
        }

        if (message.length < 10) {
            e.preventDefault();
            showFieldError('message', 'Please enter your message (minimum 10 characters).');
            return false;
        }

        return true;
    });
}

function showFieldError(fieldId, errorMessage) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.style.borderColor = '#dc2626';
        field.focus();
        
        let errorEl = document.getElementById(fieldId + '_error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.id = fieldId + '_error';
            errorEl.style.cssText = 'color: #dc2626; font-size: 0.85rem; margin-top: 4px;';
            field.parentNode.appendChild(errorEl);
        }
        errorEl.textContent = '⚠️ ' + errorMessage;

        field.addEventListener('input', function() {
            this.style.borderColor = '#e2e8f0';
            const err = document.getElementById(fieldId + '_error');
            if (err) err.remove();
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const contactItems = document.querySelectorAll('.floating-contact .contact-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    contactItems.forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(50px)';
        observer.observe(item);
    });

    if (window.innerWidth > 768) {
        const items = document.querySelectorAll('.floating-contact .contact-item');
        items.forEach(item => {
            item.addEventListener('mouseenter', function() {
                const tooltip = this.querySelector('.tooltip');
                if (tooltip) {
                    tooltip.style.display = 'block';
                }
            });
            item.addEventListener('mouseleave', function() {
                const tooltip = this.querySelector('.tooltip');
                if (tooltip) {
                    tooltip.style.display = 'none';
                }
            });
        });
    }
});