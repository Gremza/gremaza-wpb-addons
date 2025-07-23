/**
 * Gremaza WPB Addons - JavaScript functionality
 * Author: Marsel Preci
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Initialize hero banner functionality
        initHeroBanner();
        
        // Re-initialize when WPBakery adds new elements
        $(document).on('vc-full-content-reload', function() {
            initHeroBanner();
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
    
})(jQuery);
