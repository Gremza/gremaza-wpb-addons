<?php
/**
 * Contact Page Element for WPBakery Page Builder
 * Full contact page with map, contact info, and form
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaContactPage {

    public function __construct() {
        // Triple-hook registration pattern for reliability
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));

        // Register the shortcode
        add_shortcode('gremaza_contact_page', array($this, 'render_shortcode'));

        // Register AJAX handlers for form submission
        add_action('wp_ajax_gremaza_contact_submit', array($this, 'handle_form_submission'));
        add_action('wp_ajax_nopriv_gremaza_contact_submit', array($this, 'handle_form_submission'));
    }

    public function map_shortcode() {
        if (!function_exists('vc_map')) {
            return;
        }

        // Prevent duplicate registration
        static $registered = false;
        if ($registered) {
            return;
        }
        $registered = true;

        vc_map(array(
            'name' => __('Contact Page', 'gremaza-wpb-addons'),
            'base' => 'gremaza_contact_page',
            'category' => 'By Gremaza',
            'description' => __('Contact page with map, info, and form', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-ui-button',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                // Map Settings Group
                array(
                    'type' => 'dropdown',
                    'heading' => __('Show Map', 'gremaza-wpb-addons'),
                    'param_name' => 'show_map',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'group' => __('Map Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Map Height', 'gremaza-wpb-addons'),
                    'param_name' => 'map_height',
                    'value' => '400px',
                    'description' => __('Enter height (e.g., 400px, 50vh)', 'gremaza-wpb-addons'),
                    'dependency' => array('element' => 'show_map', 'value' => 'yes'),
                    'group' => __('Map Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Latitude', 'gremaza-wpb-addons'),
                    'param_name' => 'map_lat',
                    'value' => '41.3275',
                    'description' => __('Enter latitude coordinate (e.g., 41.3275)', 'gremaza-wpb-addons'),
                    'dependency' => array('element' => 'show_map', 'value' => 'yes'),
                    'group' => __('Map Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Longitude', 'gremaza-wpb-addons'),
                    'param_name' => 'map_lng',
                    'value' => '19.8187',
                    'description' => __('Enter longitude coordinate (e.g., 19.8187)', 'gremaza-wpb-addons'),
                    'dependency' => array('element' => 'show_map', 'value' => 'yes'),
                    'group' => __('Map Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Map Zoom Level', 'gremaza-wpb-addons'),
                    'param_name' => 'map_zoom',
                    'value' => '15',
                    'description' => __('Enter zoom level (1-18, higher = more zoomed in)', 'gremaza-wpb-addons'),
                    'dependency' => array('element' => 'show_map', 'value' => 'yes'),
                    'group' => __('Map Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Marker Popup Text', 'gremaza-wpb-addons'),
                    'param_name' => 'marker_popup',
                    'value' => '',
                    'description' => __('Text shown when marker is clicked (optional)', 'gremaza-wpb-addons'),
                    'dependency' => array('element' => 'show_map', 'value' => 'yes'),
                    'group' => __('Map Settings', 'gremaza-wpb-addons'),
                ),

                // Contact Info Group
                array(
                    'type' => 'textfield',
                    'heading' => __('Section Title', 'gremaza-wpb-addons'),
                    'param_name' => 'info_title',
                    'value' => 'Contact Us',
                    'admin_label' => true,
                    'group' => __('Contact Info', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Phone Number', 'gremaza-wpb-addons'),
                    'param_name' => 'phone',
                    'value' => '',
                    'description' => __('Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Contact Info', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Email Address', 'gremaza-wpb-addons'),
                    'param_name' => 'email_display',
                    'value' => '',
                    'description' => __('Email to display on the page. Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Contact Info', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __('Address', 'gremaza-wpb-addons'),
                    'param_name' => 'address',
                    'value' => '',
                    'description' => __('Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Contact Info', 'gremaza-wpb-addons'),
                ),

                // Social Media Group
                array(
                    'type' => 'textfield',
                    'heading' => __('Facebook URL', 'gremaza-wpb-addons'),
                    'param_name' => 'facebook_url',
                    'value' => '',
                    'description' => __('Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Social Media', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Instagram URL', 'gremaza-wpb-addons'),
                    'param_name' => 'instagram_url',
                    'value' => '',
                    'description' => __('Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Social Media', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('LinkedIn URL', 'gremaza-wpb-addons'),
                    'param_name' => 'linkedin_url',
                    'value' => '',
                    'description' => __('Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Social Media', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('YouTube URL', 'gremaza-wpb-addons'),
                    'param_name' => 'youtube_url',
                    'value' => '',
                    'description' => __('Leave empty to hide', 'gremaza-wpb-addons'),
                    'group' => __('Social Media', 'gremaza-wpb-addons'),
                ),

                // Form Settings Group
                array(
                    'type' => 'textfield',
                    'heading' => __('Form Title', 'gremaza-wpb-addons'),
                    'param_name' => 'form_title',
                    'value' => 'Send us a Message',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Recipient Email', 'gremaza-wpb-addons'),
                    'param_name' => 'recipient_email',
                    'value' => '',
                    'description' => __('Email address to receive form submissions. Leave empty to use site admin email.', 'gremaza-wpb-addons'),
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Name Label', 'gremaza-wpb-addons'),
                    'param_name' => 'label_name',
                    'value' => 'Name',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Email Label', 'gremaza-wpb-addons'),
                    'param_name' => 'label_email',
                    'value' => 'Email',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Subject Label', 'gremaza-wpb-addons'),
                    'param_name' => 'label_subject',
                    'value' => 'Subject',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Message Label', 'gremaza-wpb-addons'),
                    'param_name' => 'label_message',
                    'value' => 'Message',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Submit Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'submit_text',
                    'value' => 'Send Message',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Success Message', 'gremaza-wpb-addons'),
                    'param_name' => 'success_message',
                    'value' => 'Thank you! Your message has been sent successfully.',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Error Message', 'gremaza-wpb-addons'),
                    'param_name' => 'error_message',
                    'value' => 'Sorry, there was an error sending your message. Please try again.',
                    'group' => __('Form Settings', 'gremaza-wpb-addons'),
                ),

                // Design Group
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Primary Color', 'gremaza-wpb-addons'),
                    'param_name' => 'primary_color',
                    'value' => '#007bff',
                    'description' => __('Used for icons, buttons, and accents', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Title Color', 'gremaza-wpb-addons'),
                    'param_name' => 'title_color',
                    'value' => '#333333',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Text Color', 'gremaza-wpb-addons'),
                    'param_name' => 'text_color',
                    'value' => '#666666',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'background_color',
                    'value' => '#ffffff',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                    'value' => '',
                    'description' => __('Add custom CSS class for styling', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
            ),
        ));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            // Map Settings
            'show_map' => 'yes',
            'map_height' => '400px',
            'map_lat' => '41.3275',
            'map_lng' => '19.8187',
            'map_zoom' => '15',
            'marker_popup' => '',
            // Contact Info
            'info_title' => 'Contact Us',
            'phone' => '',
            'email_display' => '',
            'address' => '',
            // Social Media
            'facebook_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'youtube_url' => '',
            // Form Settings
            'form_title' => 'Send us a Message',
            'recipient_email' => '',
            'label_name' => 'Name',
            'label_email' => 'Email',
            'label_subject' => 'Subject',
            'label_message' => 'Message',
            'submit_text' => 'Send Message',
            'success_message' => 'Thank you! Your message has been sent successfully.',
            'error_message' => 'Sorry, there was an error sending your message. Please try again.',
            // Design
            'primary_color' => '#007bff',
            'title_color' => '#333333',
            'text_color' => '#666666',
            'background_color' => '#ffffff',
            'extra_class' => '',
        ), $atts, 'gremaza_contact_page');

        // Generate unique ID for this instance
        $element_id = 'gremaza-contact-' . uniqid();

        // Generate nonce for form security
        $nonce = wp_create_nonce('gremaza_contact_nonce');

        // Sanitize height
        $map_height = $this->sanitize_css_size($atts['map_height']);
        if (empty($map_height)) {
            $map_height = '400px';
        }

        ob_start();
        ?>
        <div class="gremaza-contact-page <?php echo esc_attr($atts['extra_class']); ?>"
             id="<?php echo esc_attr($element_id); ?>"
             style="background-color: <?php echo esc_attr($atts['background_color']); ?>;">

            <?php if ($atts['show_map'] === 'yes'): ?>
            <!-- ROW 1: Map Section -->
            <div class="gremaza-contact-map-section"
                 style="height: <?php echo esc_attr($map_height); ?>;"
                 data-lat="<?php echo esc_attr($atts['map_lat']); ?>"
                 data-lng="<?php echo esc_attr($atts['map_lng']); ?>"
                 data-zoom="<?php echo esc_attr($atts['map_zoom']); ?>"
                 data-popup="<?php echo esc_attr($atts['marker_popup']); ?>">
                <div class="gremaza-contact-map" id="<?php echo esc_attr($element_id); ?>-map"></div>
            </div>
            <?php endif; ?>

            <!-- ROW 2: Contact Info & Form -->
            <div class="gremaza-contact-content-section">
                <div class="gremaza-contact-container">
                    <!-- Left Column: Contact Information -->
                    <div class="gremaza-contact-info">
                        <?php if (!empty($atts['info_title'])): ?>
                        <h2 class="gremaza-contact-title" style="color: <?php echo esc_attr($atts['title_color']); ?>;">
                            <?php echo esc_html($atts['info_title']); ?>
                        </h2>
                        <?php endif; ?>

                        <div class="gremaza-contact-details" style="color: <?php echo esc_attr($atts['text_color']); ?>;">
                            <?php if (!empty($atts['phone'])): ?>
                            <div class="gremaza-contact-item">
                                <span class="gremaza-contact-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $atts['phone'])); ?>">
                                    <?php echo esc_html($atts['phone']); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($atts['email_display'])): ?>
                            <div class="gremaza-contact-item">
                                <span class="gremaza-contact-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <a href="mailto:<?php echo esc_attr($atts['email_display']); ?>">
                                    <?php echo esc_html($atts['email_display']); ?>
                                </a>
                            </div>
                            <?php endif; ?>

                            <?php if (!empty($atts['address'])): ?>
                            <div class="gremaza-contact-item gremaza-contact-address">
                                <span class="gremaza-contact-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </span>
                                <span><?php echo wp_kses_post(nl2br(esc_html($atts['address']))); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php
                        $has_social = !empty($atts['facebook_url']) || !empty($atts['instagram_url']) ||
                                      !empty($atts['linkedin_url']) || !empty($atts['youtube_url']);
                        if ($has_social):
                        ?>
                        <div class="gremaza-contact-social">
                            <?php if (!empty($atts['facebook_url'])): ?>
                            <a href="<?php echo esc_url($atts['facebook_url']); ?>" target="_blank" rel="noopener noreferrer"
                               class="gremaza-social-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($atts['instagram_url'])): ?>
                            <a href="<?php echo esc_url($atts['instagram_url']); ?>" target="_blank" rel="noopener noreferrer"
                               class="gremaza-social-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;" aria-label="Instagram">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($atts['linkedin_url'])): ?>
                            <a href="<?php echo esc_url($atts['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer"
                               class="gremaza-social-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;" aria-label="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                    <rect x="2" y="9" width="4" height="12"></rect>
                                    <circle cx="4" cy="4" r="2"></circle>
                                </svg>
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($atts['youtube_url'])): ?>
                            <a href="<?php echo esc_url($atts['youtube_url']); ?>" target="_blank" rel="noopener noreferrer"
                               class="gremaza-social-icon" style="color: <?php echo esc_attr($atts['primary_color']); ?>;" aria-label="YouTube">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                    <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"></polygon>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right Column: Contact Form -->
                    <div class="gremaza-contact-form-wrapper">
                        <?php if (!empty($atts['form_title'])): ?>
                        <h2 class="gremaza-contact-title" style="color: <?php echo esc_attr($atts['title_color']); ?>;">
                            <?php echo esc_html($atts['form_title']); ?>
                        </h2>
                        <?php endif; ?>

                        <form class="gremaza-contact-form"
                              data-nonce="<?php echo esc_attr($nonce); ?>"
                              data-success="<?php echo esc_attr($atts['success_message']); ?>"
                              data-error="<?php echo esc_attr($atts['error_message']); ?>"
                              data-recipient="<?php echo esc_attr($atts['recipient_email']); ?>">

                            <div class="gremaza-form-field">
                                <label for="<?php echo esc_attr($element_id); ?>-name">
                                    <?php echo esc_html($atts['label_name']); ?> <span class="required">*</span>
                                </label>
                                <input type="text"
                                       id="<?php echo esc_attr($element_id); ?>-name"
                                       name="contact_name"
                                       required
                                       autocomplete="name">
                            </div>

                            <div class="gremaza-form-field">
                                <label for="<?php echo esc_attr($element_id); ?>-email">
                                    <?php echo esc_html($atts['label_email']); ?> <span class="required">*</span>
                                </label>
                                <input type="email"
                                       id="<?php echo esc_attr($element_id); ?>-email"
                                       name="contact_email"
                                       required
                                       autocomplete="email">
                            </div>

                            <div class="gremaza-form-field">
                                <label for="<?php echo esc_attr($element_id); ?>-subject">
                                    <?php echo esc_html($atts['label_subject']); ?>
                                </label>
                                <input type="text"
                                       id="<?php echo esc_attr($element_id); ?>-subject"
                                       name="contact_subject">
                            </div>

                            <div class="gremaza-form-field">
                                <label for="<?php echo esc_attr($element_id); ?>-message">
                                    <?php echo esc_html($atts['label_message']); ?> <span class="required">*</span>
                                </label>
                                <textarea id="<?php echo esc_attr($element_id); ?>-message"
                                          name="contact_message"
                                          rows="5"
                                          required></textarea>
                            </div>

                            <!-- Honeypot field for spam protection -->
                            <div class="gremaza-hp-field" aria-hidden="true">
                                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="gremaza-form-submit">
                                <button type="submit"
                                        class="gremaza-submit-btn"
                                        style="background-color: <?php echo esc_attr($atts['primary_color']); ?>;">
                                    <?php echo esc_html($atts['submit_text']); ?>
                                </button>
                            </div>

                            <div class="gremaza-form-message" style="display: none;"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Handle AJAX form submission
     */
    public function handle_form_submission() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'gremaza_contact_nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'gremaza-wpb-addons')));
        }

        // Check honeypot field (should be empty)
        if (!empty($_POST['website_url'])) {
            // Silently reject spam but return success to confuse bots
            wp_send_json_success(array('message' => __('Message sent successfully.', 'gremaza-wpb-addons')));
        }

        // Sanitize and validate input
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : '';
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        $recipient = isset($_POST['recipient']) ? sanitize_email($_POST['recipient']) : '';

        // Validation
        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error(array('message' => __('Please fill in all required fields.', 'gremaza-wpb-addons')));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Please enter a valid email address.', 'gremaza-wpb-addons')));
        }

        // Determine recipient
        $to = !empty($recipient) ? $recipient : get_option('admin_email');

        // Build email
        $site_name = get_bloginfo('name');
        $email_subject = !empty($subject) ? $subject : sprintf(__('Contact Form Submission from %s', 'gremaza-wpb-addons'), $site_name);

        $email_body = sprintf(
            __("You have received a new message from the contact form on %s.\n\n", 'gremaza-wpb-addons'),
            $site_name
        );
        $email_body .= sprintf(__("Name: %s\n", 'gremaza-wpb-addons'), $name);
        $email_body .= sprintf(__("Email: %s\n", 'gremaza-wpb-addons'), $email);
        if (!empty($subject)) {
            $email_body .= sprintf(__("Subject: %s\n", 'gremaza-wpb-addons'), $subject);
        }
        $email_body .= sprintf(__("\nMessage:\n%s\n", 'gremaza-wpb-addons'), $message);

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . $site_name . ' <' . get_option('admin_email') . '>',
            'Reply-To: ' . $name . ' <' . $email . '>',
        );

        // Send email using wp_mail()
        $sent = wp_mail($to, $email_subject, $email_body, $headers);

        if ($sent) {
            wp_send_json_success(array('message' => __('Message sent successfully!', 'gremaza-wpb-addons')));
        } else {
            wp_send_json_error(array('message' => __('Failed to send message. Please try again later.', 'gremaza-wpb-addons')));
        }
    }

    /**
     * Sanitize CSS size value
     */
    private function sanitize_css_size($value) {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        // Allow valid CSS units
        if (preg_match('/^[\d.]+(?:px|em|rem|%|vh|vw)$/', $value)) {
            return $value;
        }
        // If just a number, assume pixels
        if (is_numeric($value)) {
            return $value . 'px';
        }
        return '';
    }
}
