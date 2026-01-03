/**
 * Gremaza Post Carousel
 * Handles carousel navigation and filtering
 */

(function($) {
    'use strict';
    
    class GremazaPostCarousel {
        constructor(element) {
            this.$element = $(element);
            this.$track = this.$element.find('.gremaza-carousel-track');
            this.$items = this.$element.find('.gremaza-carousel-item');
            this.$prevBtn = this.$element.find('.gremaza-carousel-prev');
            this.$nextBtn = this.$element.find('.gremaza-carousel-next');
            this.$filterBtns = this.$element.find('.gremaza-filter-btn');
            
            this.currentIndex = 0;
            this.itemsToShow = 3;
            this.itemWidth = 0;
            this.totalItems = this.$items.length;
            this.activeFilter = '*';
            this.visibleItems = this.$items;
            
            if (this.totalItems > 0) {
                this.init();
            }
        }
        
        init() {
            this.calculateDimensions();
            this.updateCarousel();
            this.attachEvents();
            this.updateArrowsVisibility();
            
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
            } else {
                this.itemsToShow = 3;
            }
            
            // Calculate item width based on visible items
            const containerWidth = this.$element.find('.gremaza-carousel-container').width();
            this.itemWidth = containerWidth / this.itemsToShow;
            
            // Set item widths
            this.$items.css('width', this.itemWidth + 'px');
        }
        
        attachEvents() {
            this.$prevBtn.on('click', () => this.prevSlide());
            this.$nextBtn.on('click', () => this.nextSlide());
            this.$filterBtns.on('click', (e) => this.filterItems($(e.currentTarget)));
        }
        
        updateCarousel() {
            // Calculate max index
            const maxIndex = Math.max(0, this.visibleItems.length - this.itemsToShow);
            
            // Constrain current index
            if (this.currentIndex > maxIndex) {
                this.currentIndex = maxIndex;
            }
            
            // Check if we need scrolling (more items than can be displayed)
            const needsScrolling = this.visibleItems.length > this.itemsToShow;
            
            // Add/remove class for centering
            if (needsScrolling) {
                this.$track.addClass('has-many-items');
            } else {
                this.$track.removeClass('has-many-items');
            }
            
            // Calculate transform
            const translateX = -(this.currentIndex * this.itemWidth);
            this.$track.css('transform', `translateX(${translateX}px)`);
            
            // Update button states
            this.$prevBtn.prop('disabled', this.currentIndex === 0);
            this.$nextBtn.prop('disabled', this.currentIndex >= maxIndex);
        }
        
        updateArrowsVisibility() {
            // Hide arrows if not enough items to scroll
            const needsScrolling = this.visibleItems.length > this.itemsToShow;
            
            if (needsScrolling) {
                this.$prevBtn.removeClass('hidden');
                this.$nextBtn.removeClass('hidden');
            } else {
                this.$prevBtn.addClass('hidden');
                this.$nextBtn.addClass('hidden');
            }
        }
        
        nextSlide() {
            const maxIndex = Math.max(0, this.visibleItems.length - this.itemsToShow);
            if (this.currentIndex < maxIndex) {
                this.currentIndex++;
                this.updateCarousel();
            }
        }
        
        prevSlide() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.updateCarousel();
            }
        }
        
        filterItems($btn) {
            const category = $btn.data('category');
            
            // Update active button
            this.$filterBtns.removeClass('active');
            $btn.addClass('active');
            
            // Reset current index
            this.currentIndex = 0;
            
            // Show/hide items based on filter
            if (category === '*') {
                this.$items.show();
                this.visibleItems = this.$items;
            } else {
                this.$items.each((index, item) => {
                    const $item = $(item);
                    const categories = $item.data('categories');
                    
                    if (categories && categories.toString().split(',').includes(category.toString())) {
                        $item.show();
                    } else {
                        $item.hide();
                    }
                });
                this.visibleItems = this.$items.filter(':visible');
            }
            
            this.activeFilter = category;
            this.updateCarousel();
            this.updateArrowsVisibility();
        }
        
        destroy() {
            this.$prevBtn.off('click');
            this.$nextBtn.off('click');
            this.$filterBtns.off('click');
            $(window).off('resize');
        }
    }
    
    // Initialize carousels when document is ready
    $(document).ready(function() {
        $('.gremaza-post-carousel').each(function() {
            new GremazaPostCarousel(this);
        });
    });
    
    // Reinitialize on AJAX complete (for dynamic content)
    $(document).on('ajaxComplete', function() {
        $('.gremaza-post-carousel').each(function() {
            if (!$(this).data('carousel-initialized')) {
                new GremazaPostCarousel(this);
                $(this).data('carousel-initialized', true);
            }
        });
    });
    
})(jQuery);
