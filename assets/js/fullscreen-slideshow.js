/**
 * Gremaza Fullscreen Slideshow
 * Handles slideshow navigation and autoplay
 */

(function($) {
    'use strict';
    
    class GremazaSlideshow {
        constructor(element) {
            this.$element = $(element);
            this.$container = this.$element.find('.gremaza-slideshow-container');
            this.$slides = this.$element.find('.gremaza-slide');
            this.$dots = this.$element.find('.gremaza-slideshow-dot');
            this.$prevBtn = this.$element.find('.gremaza-slideshow-prev');
            this.$nextBtn = this.$element.find('.gremaza-slideshow-next');
            
            this.currentSlide = 0;
            this.totalSlides = this.$slides.length;
            this.autoplay = this.$element.data('autoplay') === 'yes';
            this.autoplaySpeed = parseInt(this.$element.data('autoplay-speed')) || 5000;
            this.autoplayTimer = null;
            
            if (this.totalSlides > 0) {
                this.init();
            }
        }
        
        init() {
            // Set up event listeners
            this.$prevBtn.on('click', () => this.prevSlide());
            this.$nextBtn.on('click', () => this.nextSlide());
            
            this.$dots.on('click', (e) => {
                const slideIndex = parseInt($(e.currentTarget).data('slide'));
                this.goToSlide(slideIndex);
            });
            
            // Keyboard navigation
            $(document).on('keydown', (e) => {
                if (this.$element.is(':visible')) {
                    if (e.key === 'ArrowLeft') {
                        this.prevSlide();
                    } else if (e.key === 'ArrowRight') {
                        this.nextSlide();
                    }
                }
            });
            
            // Pause autoplay on hover
            this.$element.on('mouseenter', () => this.pauseAutoplay());
            this.$element.on('mouseleave', () => this.resumeAutoplay());
            
            // Touch/swipe support
            this.setupTouchEvents();
            
            // Start autoplay if enabled
            if (this.autoplay) {
                this.startAutoplay();
            }
        }
        
        goToSlide(index) {
            // Remove active class from current slide
            this.$slides.eq(this.currentSlide).removeClass('active');
            this.$dots.eq(this.currentSlide).removeClass('active');
            
            // Update current slide index
            this.currentSlide = index;
            
            // Add active class to new slide
            this.$slides.eq(this.currentSlide).addClass('active');
            this.$dots.eq(this.currentSlide).addClass('active');
            
            // Reset autoplay timer if active
            if (this.autoplay) {
                this.resetAutoplay();
            }
        }
        
        nextSlide() {
            const nextIndex = (this.currentSlide + 1) % this.totalSlides;
            this.goToSlide(nextIndex);
        }
        
        prevSlide() {
            const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.goToSlide(prevIndex);
        }
        
        startAutoplay() {
            if (!this.autoplay) return;
            
            this.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, this.autoplaySpeed);
        }
        
        pauseAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
        
        resumeAutoplay() {
            if (this.autoplay && !this.autoplayTimer) {
                this.startAutoplay();
            }
        }
        
        resetAutoplay() {
            this.pauseAutoplay();
            this.resumeAutoplay();
        }
        
        setupTouchEvents() {
            let touchStartX = 0;
            let touchEndX = 0;
            
            this.$element.on('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
            });
            
            this.$element.on('touchend', (e) => {
                touchEndX = e.changedTouches[0].clientX;
                this.handleSwipe(touchStartX, touchEndX);
            });
        }
        
        handleSwipe(startX, endX) {
            const swipeThreshold = 50;
            const diff = startX - endX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swiped left
                    this.nextSlide();
                } else {
                    // Swiped right
                    this.prevSlide();
                }
            }
        }
        
        destroy() {
            this.pauseAutoplay();
            this.$prevBtn.off('click');
            this.$nextBtn.off('click');
            this.$dots.off('click');
            this.$element.off('mouseenter mouseleave touchstart touchend');
        }
    }
    
    // Initialize slideshows when document is ready
    $(document).ready(function() {
        $('.gremaza-fullscreen-slideshow').each(function() {
            new GremazaSlideshow(this);
        });
    });
    
    // Reinitialize on AJAX complete (for dynamic content)
    $(document).on('ajaxComplete', function() {
        $('.gremaza-fullscreen-slideshow').each(function() {
            if (!$(this).data('slideshow-initialized')) {
                new GremazaSlideshow(this);
                $(this).data('slideshow-initialized', true);
            }
        });
    });
    
})(jQuery);
