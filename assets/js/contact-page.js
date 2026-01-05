/**
 * Gremaza Contact Page
 * Handles Leaflet map initialization and contact form AJAX submission
 */

(function($) {
    'use strict';

    // Store map instances for potential cleanup
    var mapInstances = {};

    /**
     * Initialize all contact page elements
     */
    function initContactPages() {
        $('.gremaza-contact-page').each(function() {
            var $element = $(this);
            var elementId = $element.attr('id');

            // Skip if already initialized
            if ($element.data('gremaza-initialized')) {
                return;
            }

            // Initialize map if present
            var $mapSection = $element.find('.gremaza-contact-map-section');
            if ($mapSection.length) {
                initMap($mapSection, elementId);
            }

            // Initialize form
            var $form = $element.find('.gremaza-contact-form');
            if ($form.length) {
                initForm($form);
            }

            $element.data('gremaza-initialized', true);
        });
    }

    /**
     * Initialize Leaflet map
     */
    function initMap($mapSection, elementId) {
        var $mapContainer = $mapSection.find('.gremaza-contact-map');
        if (!$mapContainer.length) {
            return;
        }

        var mapId = $mapContainer.attr('id');
        var lat = parseFloat($mapSection.data('lat')) || 41.3275;
        var lng = parseFloat($mapSection.data('lng')) || 19.8187;
        var zoom = parseInt($mapSection.data('zoom'), 10) || 15;
        var popupText = $mapSection.data('popup') || '';

        // Check if Leaflet is loaded
        if (typeof L === 'undefined') {
            console.warn('Gremaza Contact Page: Leaflet library not loaded');
            return;
        }

        // Check if map container exists in DOM
        var mapElement = document.getElementById(mapId);
        if (!mapElement) {
            return;
        }

        // Destroy existing map instance if any
        if (mapInstances[mapId]) {
            mapInstances[mapId].remove();
            delete mapInstances[mapId];
        }

        // Create map
        var map = L.map(mapId, {
            scrollWheelZoom: false // Disable scroll zoom for better UX
        }).setView([lat, lng], zoom);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);

        // Add marker
        var marker = L.marker([lat, lng]).addTo(map);

        // Add popup if text provided
        if (popupText && popupText.length > 0) {
            marker.bindPopup(popupText);
        }

        // Store map instance
        mapInstances[mapId] = map;

        // Fix map sizing issues after container becomes visible
        setTimeout(function() {
            map.invalidateSize();
        }, 100);

        // Additional resize handler for dynamic containers
        $(window).on('resize.gremazaMap', debounce(function() {
            if (mapInstances[mapId]) {
                mapInstances[mapId].invalidateSize();
            }
        }, 250));
    }

    /**
     * Initialize contact form with AJAX submission
     */
    function initForm($form) {
        // Skip if already has submit handler
        if ($form.data('form-initialized')) {
            return;
        }

        $form.on('submit', function(e) {
            e.preventDefault();

            var $submitBtn = $form.find('.gremaza-submit-btn');
            var $messageDiv = $form.find('.gremaza-form-message');
            var originalBtnText = $submitBtn.text();

            // Disable button and show loading state
            $submitBtn.prop('disabled', true).text('Sending...');
            $messageDiv.hide().removeClass('success error');

            // Check if AJAX URL is available
            if (typeof gremazaContactAjax === 'undefined' || !gremazaContactAjax.ajaxurl) {
                $messageDiv
                    .addClass('error')
                    .text('Configuration error. Please refresh the page and try again.')
                    .fadeIn();
                $submitBtn.prop('disabled', false).text(originalBtnText);
                return;
            }

            // Collect form data
            var formData = {
                action: 'gremaza_contact_submit',
                nonce: $form.data('nonce'),
                name: $form.find('input[name="contact_name"]').val(),
                email: $form.find('input[name="contact_email"]').val(),
                subject: $form.find('input[name="contact_subject"]').val(),
                message: $form.find('textarea[name="contact_message"]').val(),
                website_url: $form.find('input[name="website_url"]').val(), // Honeypot
                recipient: $form.data('recipient')
            };

            // Send AJAX request
            $.ajax({
                url: gremazaContactAjax.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $messageDiv
                            .addClass('success')
                            .text($form.data('success'))
                            .fadeIn();

                        // Reset form
                        $form[0].reset();
                    } else {
                        var errorMsg = response.data && response.data.message
                            ? response.data.message
                            : $form.data('error');
                        $messageDiv
                            .addClass('error')
                            .text(errorMsg)
                            .fadeIn();
                    }
                },
                error: function() {
                    $messageDiv
                        .addClass('error')
                        .text($form.data('error'))
                        .fadeIn();
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text(originalBtnText);
                }
            });
        });

        $form.data('form-initialized', true);
    }

    /**
     * Debounce utility function
     */
    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this;
            var args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    /**
     * Reinitialize maps (useful for dynamic content)
     */
    function reinitMaps() {
        $('.gremaza-contact-page').each(function() {
            var $element = $(this);
            var elementId = $element.attr('id');
            var $mapSection = $element.find('.gremaza-contact-map-section');

            if ($mapSection.length) {
                // Remove initialized flag to allow reinit
                $element.removeData('gremaza-initialized');

                // Destroy old map
                var mapId = $mapSection.find('.gremaza-contact-map').attr('id');
                if (mapInstances[mapId]) {
                    mapInstances[mapId].remove();
                    delete mapInstances[mapId];
                }

                // Reinitialize
                initMap($mapSection, elementId);
                $element.data('gremaza-initialized', true);
            }
        });
    }

    // Initialize on document ready
    $(document).ready(function() {
        initContactPages();
    });

    // Reinitialize on WPBakery frontend editor reload
    $(document).on('vc-full-content-reload', function() {
        // Small delay to ensure DOM is ready
        setTimeout(function() {
            // Reset initialized flags
            $('.gremaza-contact-page').removeData('gremaza-initialized');
            $('.gremaza-contact-form').removeData('form-initialized');
            initContactPages();
        }, 200);
    });

    // Support for WPBakery backend editor preview
    if (typeof window.vc !== 'undefined') {
        $(window).on('vc_reload', function() {
            setTimeout(function() {
                $('.gremaza-contact-page').removeData('gremaza-initialized');
                $('.gremaza-contact-form').removeData('form-initialized');
                initContactPages();
            }, 200);
        });
    }

    // Reinitialize on AJAX complete (for dynamic content loading)
    $(document).on('ajaxComplete', function(event, xhr, settings) {
        // Only reinit if it's not our own contact form AJAX
        if (settings.data && settings.data.indexOf('gremaza_contact_submit') === -1) {
            setTimeout(function() {
                initContactPages();
            }, 100);
        }
    });

    // Expose reinit function globally for manual triggering if needed
    window.gremazaContactPageReinit = reinitMaps;

})(jQuery);
