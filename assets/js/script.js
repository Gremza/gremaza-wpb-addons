/**
 * Gremaza WPB Addons - JavaScript functionality
 * Author: Marsel Preci
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Initialize hero banner functionality
        initHeroBanner();
        // Initialize reviews slider
        initReviewsSlider();
        
        // Re-initialize when WPBakery adds new elements
        $(document).on('vc-full-content-reload', function() {
            initHeroBanner();
            initReviewsSlider();
        });
        
    });
    
    /**
     * Initialize Hero Banner functionality
     */
    function initHeroBanner() {
        $('.gremaza-hero-banner').each(function() {
            var $heroBanner = $(this);
            
            // Add fade-in animation class
            $heroBanner.addClass('gremaza-fade-in');
            
            // Handle button hover effects
            $heroBanner.find('.gremaza-hero-button').hover(
                function() {
                    $(this).addClass('hover-effect');
                },
                function() {
                    $(this).removeClass('hover-effect');
                }
            );
            
            // Smooth scroll for anchor links
            $heroBanner.find('.gremaza-hero-button[href^="#"]').on('click', function(e) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            });
            
        });
    }
    
    /**
     * Responsive image handling
     */
    function handleResponsiveImages() {
        $('.gremaza-hero-image').each(function() {
            var $img = $(this);
            
            // Add loaded class when image is fully loaded
            $img.on('load', function() {
                $(this).addClass('loaded');
            });
            
            // If image is already cached/loaded
            if (this.complete) {
                $img.addClass('loaded');
            }
        });
    }
    
    // Initialize responsive images
    $(window).on('load', function() {
        handleResponsiveImages();
    });
    
    // Reinitialize on window resize
    $(window).on('resize', debounce(function() {
        handleResponsiveImages();
    }, 250));
    
    /**
     * Debounce function to limit function calls
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
    
    /**
     * Initialize Reviews slider (horizontal mode)
     */
    function initReviewsSlider() {
        $('.gremaza-reviews.gremaza-reviews-horizontal').each(function() {
            var $wrap = $(this);
            var $list = $wrap.find('.gremaza-reviews-list');
            var $cards = $list.find('.gremaza-review-card');
            var $pagination = $wrap.find('.gremaza-reviews-pagination');

            // Clean previous dots and handlers
            $pagination.empty();
            $list.off('.gremazaSlider');

            // Ensure scroll snapping
            $list.css({ 'scroll-snap-type': 'x mandatory' });
            $cards.css({ 'scroll-snap-align': 'start' });

            if ($cards.length === 0) return;

            // Compute how many cards fit per page based on first card width
            var listW = $list.innerWidth();
            var cardW = $cards.eq(0).outerWidth(true);
            if (!cardW || cardW <= 0) {
                // fallback: assume one per page
                cardW = listW;
            }
            var perPage = Math.max(1, Math.floor(listW / cardW));
            var pages = Math.max(1, Math.ceil($cards.length / perPage));

            // Create dots
            for (var i = 0; i < pages; i++) {
                var $dot = $('<button/>', {
                    'class': 'gremaza-dot',
                    'type': 'button',
                    'role': 'tab',
                    'aria-label': 'Go to slide ' + (i + 1),
                    'aria-selected': i === 0 ? 'true' : 'false'
                });
                (function(index){
                    $dot.on('click.gremazaSlider', function(){
                        var x = index * listW;
                        $list.stop();
                        $list.animate({ scrollLeft: x }, 400);
                    });
                })(i);
                $pagination.append($dot);
            }

            // Color dots based on border color from wrapper
            var dotColor = $wrap.data('dot-color');
            if (dotColor) {
                $pagination.find('.gremaza-dot').css({ background: '#d0d0d0' });
                // active will be set via aria-selected attr; updateActive applies class via attr
                var setActiveColor = function() {
                    $pagination.find('.gremaza-dot[aria-selected="true"]').css({ background: dotColor });
                };
                setActiveColor();
                $list.on('scroll.gremazaSlider', debounce(setActiveColor, 50));
            }

            // Update active dot on scroll
            var updateActive = debounce(function(){
                var sl = $list.scrollLeft();
                var page = Math.round(sl / listW);
                page = Math.max(0, Math.min(page, pages - 1));
                $pagination.find('.gremaza-dot').attr('aria-selected','false').eq(page).attr('aria-selected','true');
            }, 50);

            $list.on('scroll.gremazaSlider', updateActive);

            // Recompute on resize
            $(window).off('resize.gremazaSlider');
            $(window).on('resize.gremazaSlider', debounce(function(){
                initReviewsSlider(); // simple re-init
            }, 200));

            // Auto-scroll from right to left (RTL)
            // Start at the end and move backwards page by page
            var autoplayInterval = 4000; // ms
            var animDuration = 500; // ms
            var timerId;
            function startAuto() {
                stopAuto();
                // jump to last page initially
                var current = Math.round($list.scrollLeft() / listW);
                if (current < pages - 1) {
                    $list.scrollLeft((pages - 1) * listW);
                }
                timerId = setInterval(function(){
                    var sl = $list.scrollLeft();
                    var page = Math.round(sl / listW);
                    var nextPage = page - 1;
                    if (nextPage < 0) {
                        nextPage = pages - 1; // loop to last
                    }
                    $list.stop().animate({ scrollLeft: nextPage * listW }, animDuration);
                }, autoplayInterval);
            }
            function stopAuto() {
                if (timerId) { clearInterval(timerId); timerId = null; }
            }
            // pause on hover/focus for accessibility
            $wrap.on('mouseenter.gremazaSlider focusin.gremazaSlider', stopAuto);
            $wrap.on('mouseleave.gremazaSlider focusout.gremazaSlider', startAuto);
            startAuto();
        });
    }
    
})(jQuery);
