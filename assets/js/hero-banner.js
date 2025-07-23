/**
 * Gremaza WPB Addons - Hero Banner JavaScript
 * Author: Marsel Preci
 */

document.addEventListener("DOMContentLoaded", function() {
    // Initialize video cover functionality
    initVideoCovers();
});

function initVideoCovers() {
    var covers = document.querySelectorAll(".gremaza-video-cover");
    
    covers.forEach(function(cover) {
        // Remove existing event listeners to prevent duplicates
        cover.removeEventListener("click", playVideo);
        cover.removeEventListener("touchstart", playVideo);
        
        // Add click and touch events for mobile compatibility
        cover.addEventListener("click", playVideo);
        cover.addEventListener("touchstart", playVideo);
        
        function playVideo(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var container = this.parentElement;
            var iframe = container.querySelector(".gremaza-hero-video");
            
            if (!iframe) return;
            
            var src = iframe.src;
            
            // Add autoplay parameter and show iframe
            if (src.indexOf('autoplay=0') !== -1) {
                iframe.src = src.replace("autoplay=0", "autoplay=1");
            } else if (src.indexOf('autoplay=1') === -1) {
                iframe.src = src + (src.indexOf('?') !== -1 ? '&' : '?') + "autoplay=1";
            }
            
            // Force display with important styles
            iframe.style.setProperty('display', 'block', 'important');
            iframe.style.setProperty('visibility', 'visible', 'important');
            iframe.style.setProperty('opacity', '1', 'important');
            
            // Hide cover
            this.style.setProperty('display', 'none', 'important');
            
            // Focus on iframe for better mobile experience
            setTimeout(function() {
                iframe.focus();
            }, 100);
        }
    });
}

// Reinitialize when new content is added (for AJAX-loaded content)
if (typeof jQuery !== 'undefined') {
    jQuery(document).ajaxComplete(function() {
        initVideoCovers();
    });
}

// Also reinitialize on window resize for responsive behavior
window.addEventListener('resize', function() {
    initVideoCovers();
});
