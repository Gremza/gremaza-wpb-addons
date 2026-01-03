/**
 * Gremaza Image Carousel
 * Handles carousel navigation with multiple visible items
 */

(function($) {
    'use strict';
    
    class GremazaImageCarousel {
        constructor(element) {
            this.$element = $(element);
            this.$track = this.$element.find('.gremaza-image-carousel-slides');
            this.$slides = this.$element.find('.gremaza-image-slide');
            this.$prevBtn = this.$element.find('.gremaza-image-carousel-prev');
            this.$nextBtn = this.$element.find('.gremaza-image-carousel-next');
            this.$dots = this.$element.find('.gremaza-image-carousel-dot');
            
            this.currentIndex = 0;
            this.totalSlides = this.$slides.length;
            this.itemsToShow = 4;
            this.itemWidth = 0;
            this.autoplayTimer = null;
            
            // Get settings
            this.autoplay = this.$element.data('autoplay') === 'yes';
            this.autoplaySpeed = parseInt(this.$element.data('autoplay-speed')) || 5000;
            
            if (this.totalSlides > 0) {
                this.init();
            }
        }
        
        init() {
            this.calculateDimensions();
            this.updateCarousel();
            this.attachEvents();
            this.updateArrowsVisibility();
            
            if (this.autoplay && this.totalSlides > this.itemsToShow) {
                this.startAutoplay();
            }
            
            // Recalculate on window resize
            $(window).on('resize', () => {
                clearTimeout(this.resizeTimer);
                this.resizeTimer = setTimeout(() => {
                    this.calculateDimensions();
                    this.updateCarousel();
                    this.updateArrowsVisibility();
                }, 250);
            });
        }
        
        calculateDimensions() {
            // Responsive items to show
            const windowWidth = $(window).width();
            if (windowWidth < 768) {
                this.itemsToShow = 1;
            } else if (windowWidth < 1024) {
                this.itemsToShow = 2;
            } else if (windowWidth < 1440) {
                this.itemsToShow = 3;
            } else {
                this.itemsToShow = 4;
            }
            
            // Calculate item width (subtract gap from container)
            const containerWidth = this.$element.width();
            const gapSize = 40; // Gap between items
            const totalGaps = this.itemsToShow * gapSize;
            this.itemWidth = (containerWidth - totalGaps) / this.itemsToShow;
            
            // Show 5th item partially (add 20% extra space)
            if (this.itemsToShow === 4) {
                this.itemWidth = (containerWidth - totalGaps) / 4.4;
            }
            
            // Set slide widths and display
            this.$slides.css({
                'width': this.itemWidth + 'px',
                'display': 'inline-block'
            });
            
            // Update track to flex layout
            this.$track.css({
                'display': 'flex',
                'transition': 'transform 0.5s ease'
            });
        }
        
        attachEvents() {
            // Navigation buttons
            this.$prevBtn.on('click', () => this.prevSlide());
            this.$nextBtn.on('click', () => this.nextSlide());
            
            // Dot indicators
            this.$dots.on('click', (e) => {
                const dotIndex = parseInt($(e.currentTarget).data('slide'));
                this.goToSlide(dotIndex);
            });
            
            // Pause autoplay on hover
            if (this.autoplay) {
                this.$element.on('mouseenter', () => this.stopAutoplay());
                this.$element.on('mouseleave', () => {
                    if (this.totalSlides > this.itemsToShow) {
                        this.startAutoplay();
                    }
                });
            }
        }
        
        goToSlide(index) {
            const maxIndex = Math.max(0, this.totalSlides - this.itemsToShow);
            
            // Constrain index to valid range
            if (index < 0) {
                index = 0;
            } else if (index > maxIndex) {
                index = maxIndex;
            }
            
            this.currentIndex = index;
            this.updateCarousel();
            this.updateDots();
            
            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                if (this.totalSlides > this.itemsToShow) {
                    this.startAutoplay();
                }
            }
        }
        
        updateCarousel() {
            // Calculate max index
            const maxIndex = Math.max(0, this.totalSlides - this.itemsToShow);
            
            // Constrain current index
            if (this.currentIndex > maxIndex) {
                this.currentIndex = maxIndex;
            }
            
            // Calculate offset (including gap)
            const gapSize = 40;
            const offset = -this.currentIndex * (this.itemWidth + gapSize);
            
            // Update track position
            this.$track.css('transform', 'translateX(' + offset + 'px)');
            
            // Update active states
            this.$slides.removeClass('active');
            for (let i = this.currentIndex; i < this.currentIndex + this.itemsToShow && i < this.totalSlides; i++) {
                this.$slides.eq(i).addClass('active');
            }
            
            // Update dots
            this.updateDots();
        }
        
        updateDots() {
            // Update dot active state based on current position
            this.$dots.removeClass('active');
            if (this.$dots.length > 0 && this.currentIndex < this.$dots.length) {
                this.$dots.eq(this.currentIndex).addClass('active');
            }
        }
        
        updateArrowsVisibility() {
            if (this.totalSlides <= this.itemsToShow) {
                this.$prevBtn.addClass('hidden');
                this.$nextBtn.addClass('hidden');
            } else {
                this.$prevBtn.removeClass('hidden');
                this.$nextBtn.removeClass('hidden');
            }
        }
        
        nextSlide() {
            const maxIndex = Math.max(0, this.totalSlides - this.itemsToShow);
            if (this.currentIndex < maxIndex) {
                this.currentIndex++;
            } else {
                this.currentIndex = 0; // Loop back
            }
            this.updateCarousel();
            
            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                if (this.totalSlides > this.itemsToShow) {
                    this.startAutoplay();
                }
            }
        }
        
        prevSlide() {
            const maxIndex = Math.max(0, this.totalSlides - this.itemsToShow);
            if (this.currentIndex > 0) {
                this.currentIndex--;
            } else {
                this.currentIndex = maxIndex; // Loop to end
            }
            this.updateCarousel();
            
            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                if (this.totalSlides > this.itemsToShow) {
                    this.startAutoplay();
                }
            }
        }
        
        startAutoplay() {
            if (this.totalSlides <= this.itemsToShow) return;
            
            this.stopAutoplay();
            this.autoplayTimer = setInterval(() => {
                this.nextSlide();
            }, this.autoplaySpeed);
        }
        
        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
    }
    
    // Initialize all image carousels
    $(document).ready(function() {
        $('.gremaza-image-carousel').each(function() {
            new GremazaImageCarousel(this);
        });
    });
    
    // Support for WPBakery frontend editor
    if (window.vc) {
        window.vc.events.on('shortcodeView:ready', function() {
            setTimeout(function() {
                $('.gremaza-image-carousel').each(function() {
                    if (!$(this).data('carousel-initialized')) {
                        $(this).data('carousel-initialized', true);
                        new GremazaImageCarousel(this);
                    }
                });
            }, 100);
        });
    }
    
})(jQuery);
