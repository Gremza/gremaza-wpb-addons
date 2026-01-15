/**
 * Gremaza Image Card Carousel
 * Handles carousel navigation with multiple visible cards
 */

(function($) {
    'use strict';

    class GremazaCardCarousel {
        constructor(element) {
            this.$element = $(element);
            this.$track = this.$element.find('.gremaza-card-carousel-track');
            this.$items = this.$element.find('.gremaza-card-item');
            this.$prevBtn = this.$element.find('.gremaza-card-carousel-prev');
            this.$nextBtn = this.$element.find('.gremaza-card-carousel-next');

            this.currentIndex = 0;
            this.totalItems = this.$items.length;
            this.itemsToShow = 3;
            this.itemWidth = 0;
            this.autoplayTimer = null;

            // Get settings from data attributes
            this.autoplay = this.$element.data('autoplay') === 'yes';
            this.autoplaySpeed = parseInt(this.$element.data('autoplay-speed')) || 5000;
            this.itemsDesktop = parseInt(this.$element.data('items-desktop')) || 3;
            this.itemsTablet = parseInt(this.$element.data('items-tablet')) || 2;
            this.itemsMobile = parseInt(this.$element.data('items-mobile')) || 1;
            this.gap = parseInt(this.$element.data('gap')) || 20;

            if (this.totalItems > 0) {
                this.init();
            }
        }

        init() {
            this.calculateDimensions();
            this.updateCarousel();
            this.attachEvents();
            this.updateArrowsVisibility();

            if (this.autoplay && this.totalItems > this.itemsToShow) {
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
            const windowWidth = $(window).width();

            // Responsive items to show based on settings
            if (windowWidth < 768) {
                this.itemsToShow = this.itemsMobile;
            } else if (windowWidth < 1024) {
                this.itemsToShow = this.itemsTablet;
            } else {
                this.itemsToShow = this.itemsDesktop;
            }

            // Calculate item width
            const containerWidth = this.$element.width();
            const totalGaps = (this.itemsToShow - 1) * this.gap;
            this.itemWidth = (containerWidth - totalGaps) / this.itemsToShow;

            // Set item widths
            this.$items.css({
                'width': this.itemWidth + 'px',
                'flex-shrink': '0'
            });

            // Update track gap
            this.$track.css({
                'gap': this.gap + 'px'
            });
        }

        attachEvents() {
            // Navigation buttons
            this.$prevBtn.on('click', () => this.prevSlide());
            this.$nextBtn.on('click', () => this.nextSlide());

            // Touch/swipe support
            let touchStartX = 0;
            let touchEndX = 0;

            this.$element.on('touchstart', (e) => {
                touchStartX = e.originalEvent.touches[0].clientX;
            });

            this.$element.on('touchend', (e) => {
                touchEndX = e.originalEvent.changedTouches[0].clientX;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        this.nextSlide();
                    } else {
                        this.prevSlide();
                    }
                }
            });

            // Pause autoplay on hover
            if (this.autoplay) {
                this.$element.on('mouseenter', () => this.stopAutoplay());
                this.$element.on('mouseleave', () => {
                    if (this.totalItems > this.itemsToShow) {
                        this.startAutoplay();
                    }
                });
            }
        }

        goToSlide(index) {
            const maxIndex = Math.max(0, this.totalItems - this.itemsToShow);

            // Constrain index to valid range
            if (index < 0) {
                index = 0;
            } else if (index > maxIndex) {
                index = maxIndex;
            }

            this.currentIndex = index;
            this.updateCarousel();

            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                if (this.totalItems > this.itemsToShow) {
                    this.startAutoplay();
                }
            }
        }

        updateCarousel() {
            // Calculate max index
            const maxIndex = Math.max(0, this.totalItems - this.itemsToShow);

            // Constrain current index
            if (this.currentIndex > maxIndex) {
                this.currentIndex = maxIndex;
            }

            // Calculate offset
            const offset = -this.currentIndex * (this.itemWidth + this.gap);

            // Update track position
            this.$track.css('transform', 'translateX(' + offset + 'px)');

            // Update active states
            this.$items.removeClass('active');
            for (let i = this.currentIndex; i < this.currentIndex + this.itemsToShow && i < this.totalItems; i++) {
                this.$items.eq(i).addClass('active');
            }

            // Update arrow visibility
            this.updateArrowsState();
        }

        updateArrowsState() {
            const maxIndex = Math.max(0, this.totalItems - this.itemsToShow);

            // Show/hide prev arrow
            if (this.currentIndex <= 0) {
                this.$prevBtn.addClass('hidden');
            } else {
                this.$prevBtn.removeClass('hidden');
            }

            // Show/hide next arrow
            if (this.currentIndex >= maxIndex) {
                this.$nextBtn.addClass('hidden');
            } else {
                this.$nextBtn.removeClass('hidden');
            }
        }

        updateArrowsVisibility() {
            if (this.totalItems <= this.itemsToShow) {
                this.$prevBtn.addClass('hidden');
                this.$nextBtn.addClass('hidden');
            } else {
                this.updateArrowsState();
            }
        }

        nextSlide() {
            const maxIndex = Math.max(0, this.totalItems - this.itemsToShow);
            if (this.currentIndex < maxIndex) {
                this.currentIndex++;
            }
            this.updateCarousel();

            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                if (this.totalItems > this.itemsToShow) {
                    this.startAutoplay();
                }
            }
        }

        prevSlide() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
            this.updateCarousel();

            // Reset autoplay timer
            if (this.autoplay) {
                this.stopAutoplay();
                if (this.totalItems > this.itemsToShow) {
                    this.startAutoplay();
                }
            }
        }

        startAutoplay() {
            if (this.totalItems <= this.itemsToShow) return;

            this.stopAutoplay();
            this.autoplayTimer = setInterval(() => {
                const maxIndex = Math.max(0, this.totalItems - this.itemsToShow);
                if (this.currentIndex < maxIndex) {
                    this.currentIndex++;
                } else {
                    this.currentIndex = 0;
                }
                this.updateCarousel();
            }, this.autoplaySpeed);
        }

        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
    }

    // Initialize all card carousels
    $(document).ready(function() {
        $('.gremaza-card-carousel').each(function() {
            new GremazaCardCarousel(this);
        });
    });

    // Support for WPBakery frontend editor
    if (window.vc) {
        window.vc.events.on('shortcodeView:ready', function() {
            setTimeout(function() {
                $('.gremaza-card-carousel').each(function() {
                    if (!$(this).data('card-carousel-initialized')) {
                        $(this).data('card-carousel-initialized', true);
                        new GremazaCardCarousel(this);
                    }
                });
            }, 100);
        });
    }

})(jQuery);
