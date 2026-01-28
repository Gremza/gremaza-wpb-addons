<?php
/**
 * Contact Widget for WordPress Sidebars
 * Compact contact info with optional form for widget areas
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Gremaza_Contact_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'gremaza_contact_widget',
            __('Gremaza Contact', 'gremaza-wpb-addons'),
            array(
                'description' => __('Display contact information, social links, and optional contact form in sidebar', 'gremaza-wpb-addons'),
                'classname' => 'gremaza-contact-widget',
            )
        );
    }

    /**
     * Front-end display of widget
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $facebook_url = !empty($instance['facebook_url']) ? $instance['facebook_url'] : '';
        $instagram_url = !empty($instance['instagram_url']) ? $instance['instagram_url'] : '';
        $linkedin_url = !empty($instance['linkedin_url']) ? $instance['linkedin_url'] : '';
        $youtube_url = !empty($instance['youtube_url']) ? $instance['youtube_url'] : '';
        $show_form = !empty($instance['show_form']) ? $instance['show_form'] : 'no';
        $recipient_email = !empty($instance['recipient_email']) ? $instance['recipient_email'] : '';
        $primary_color = !empty($instance['primary_color']) ? $instance['primary_color'] : '#007bff';

        // Generate unique ID
        $widget_id = 'gremaza-contact-widget-' . uniqid();
        $nonce = wp_create_nonce('gremaza_contact_nonce');

        echo $args['before_widget'];

        if (!empty($title)) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }

        $has_info = !empty($phone) || !empty($email) || !empty($address) || !empty($facebook_url) || !empty($instagram_url) || !empty($linkedin_url) || !empty($youtube_url);
        $has_form = $show_form === 'yes';
        $layout_class = ($has_info && $has_form) ? 'gremaza-widget-two-column' : 'gremaza-widget-single-column';
        ?>
        <div class="gremaza-contact-widget-content <?php echo esc_attr($layout_class); ?>" id="<?php echo esc_attr($widget_id); ?>">

            <?php if ($has_info): ?>
            <!-- Left Column: Contact Info -->
            <div class="gremaza-widget-left-column">
            <?php if (!empty($phone) || !empty($email) || !empty($address)): ?>
            <div class="gremaza-widget-contact-info">
                <?php if (!empty($phone)): ?>
                <div class="gremaza-widget-contact-item">
                    <span class="gremaza-widget-icon" style="color: <?php echo esc_attr($primary_color); ?>;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </span>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (!empty($email)): ?>
                <div class="gremaza-widget-contact-item">
                    <span class="gremaza-widget-icon" style="color: <?php echo esc_attr($primary_color); ?>;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </span>
                    <a href="mailto:<?php echo esc_attr($email); ?>">
                        <?php echo esc_html($email); ?>
                    </a>
                </div>
                <?php endif; ?>

                <?php if (!empty($address)): ?>
                <div class="gremaza-widget-contact-item gremaza-widget-address">
                    <span class="gremaza-widget-icon" style="color: <?php echo esc_attr($primary_color); ?>;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </span>
                    <span><?php echo wp_kses_post(nl2br(esc_html($address))); ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php
            $has_social = !empty($facebook_url) || !empty($instagram_url) || !empty($linkedin_url) || !empty($youtube_url);
            if ($has_social):
            ?>
            <div class="gremaza-widget-social">
                <?php if (!empty($facebook_url)): ?>
                <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener noreferrer"
                   class="gremaza-widget-social-icon" style="color: <?php echo esc_attr($primary_color); ?>;" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if (!empty($instagram_url)): ?>
                <a href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener noreferrer"
                   class="gremaza-widget-social-icon" style="color: <?php echo esc_attr($primary_color); ?>;" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if (!empty($linkedin_url)): ?>
                <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener noreferrer"
                   class="gremaza-widget-social-icon" style="color: <?php echo esc_attr($primary_color); ?>;" aria-label="LinkedIn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                        <rect x="2" y="9" width="4" height="12"></rect>
                        <circle cx="4" cy="4" r="2"></circle>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if (!empty($youtube_url)): ?>
                <a href="<?php echo esc_url($youtube_url); ?>" target="_blank" rel="noopener noreferrer"
                   class="gremaza-widget-social-icon" style="color: <?php echo esc_attr($primary_color); ?>;" aria-label="YouTube">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                        <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"></polygon>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            </div><!-- /.gremaza-widget-left-column -->
            <?php endif; ?>

            <?php if ($show_form === 'yes'): ?>
            <!-- Right Column: Contact Form -->
            <div class="gremaza-widget-right-column">
            <div class="gremaza-widget-form-wrapper">
                <form class="gremaza-contact-form gremaza-widget-form"
                      data-nonce="<?php echo esc_attr($nonce); ?>"
                      data-success="<?php echo esc_attr__('Message sent successfully!', 'gremaza-wpb-addons'); ?>"
                      data-error="<?php echo esc_attr__('Error sending message. Please try again.', 'gremaza-wpb-addons'); ?>"
                      data-recipient="<?php echo esc_attr($recipient_email); ?>">

                    <div class="gremaza-widget-form-field">
                        <input type="text"
                               name="contact_name"
                               placeholder="<?php esc_attr_e('Your Name *', 'gremaza-wpb-addons'); ?>"
                               required
                               autocomplete="name">
                    </div>

                    <div class="gremaza-widget-form-field">
                        <input type="email"
                               name="contact_email"
                               placeholder="<?php esc_attr_e('Your Email *', 'gremaza-wpb-addons'); ?>"
                               required
                               autocomplete="email">
                    </div>

                    <div class="gremaza-widget-form-field">
                        <textarea name="contact_message"
                                  rows="3"
                                  placeholder="<?php esc_attr_e('Your Message *', 'gremaza-wpb-addons'); ?>"
                                  required></textarea>
                    </div>

                    <!-- Honeypot field for spam protection -->
                    <div class="gremaza-hp-field" aria-hidden="true">
                        <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="gremaza-widget-form-submit">
                        <button type="submit"
                                class="gremaza-widget-submit-btn"
                                style="background-color: <?php echo esc_attr($primary_color); ?>;">
                            <?php esc_html_e('Send', 'gremaza-wpb-addons'); ?>
                        </button>
                    </div>

                    <div class="gremaza-form-message" style="display: none;"></div>
                </form>
            </div>
            </div><!-- /.gremaza-widget-right-column -->
            <?php endif; ?>

        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Contact Us', 'gremaza-wpb-addons');
        $phone = !empty($instance['phone']) ? $instance['phone'] : '';
        $email = !empty($instance['email']) ? $instance['email'] : '';
        $address = !empty($instance['address']) ? $instance['address'] : '';
        $facebook_url = !empty($instance['facebook_url']) ? $instance['facebook_url'] : '';
        $instagram_url = !empty($instance['instagram_url']) ? $instance['instagram_url'] : '';
        $linkedin_url = !empty($instance['linkedin_url']) ? $instance['linkedin_url'] : '';
        $youtube_url = !empty($instance['youtube_url']) ? $instance['youtube_url'] : '';
        $show_form = !empty($instance['show_form']) ? $instance['show_form'] : 'no';
        $recipient_email = !empty($instance['recipient_email']) ? $instance['recipient_email'] : '';
        $primary_color = !empty($instance['primary_color']) ? $instance['primary_color'] : '#007bff';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p><strong><?php esc_html_e('Contact Information', 'gremaza-wpb-addons'); ?></strong></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="email" value="<?php echo esc_attr($email); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:', 'gremaza-wpb-addons'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" rows="2"><?php echo esc_textarea($address); ?></textarea>
        </p>

        <p><strong><?php esc_html_e('Social Media Links', 'gremaza-wpb-addons'); ?></strong></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook_url')); ?>"><?php esc_html_e('Facebook URL:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook_url')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook_url')); ?>" type="url" value="<?php echo esc_attr($facebook_url); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('instagram_url')); ?>"><?php esc_html_e('Instagram URL:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram_url')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram_url')); ?>" type="url" value="<?php echo esc_attr($instagram_url); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('linkedin_url')); ?>"><?php esc_html_e('LinkedIn URL:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin_url')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin_url')); ?>" type="url" value="<?php echo esc_attr($linkedin_url); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('youtube_url')); ?>"><?php esc_html_e('YouTube URL:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube_url')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube_url')); ?>" type="url" value="<?php echo esc_attr($youtube_url); ?>">
        </p>

        <p><strong><?php esc_html_e('Contact Form', 'gremaza-wpb-addons'); ?></strong></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_form')); ?>"><?php esc_html_e('Show Contact Form:', 'gremaza-wpb-addons'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('show_form')); ?>" name="<?php echo esc_attr($this->get_field_name('show_form')); ?>">
                <option value="no" <?php selected($show_form, 'no'); ?>><?php esc_html_e('No', 'gremaza-wpb-addons'); ?></option>
                <option value="yes" <?php selected($show_form, 'yes'); ?>><?php esc_html_e('Yes', 'gremaza-wpb-addons'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('recipient_email')); ?>"><?php esc_html_e('Recipient Email:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('recipient_email')); ?>" name="<?php echo esc_attr($this->get_field_name('recipient_email')); ?>" type="email" value="<?php echo esc_attr($recipient_email); ?>">
            <small><?php esc_html_e('Leave empty to use site admin email', 'gremaza-wpb-addons'); ?></small>
        </p>

        <p><strong><?php esc_html_e('Design', 'gremaza-wpb-addons'); ?></strong></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('primary_color')); ?>"><?php esc_html_e('Primary Color:', 'gremaza-wpb-addons'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('primary_color')); ?>" name="<?php echo esc_attr($this->get_field_name('primary_color')); ?>" type="text" value="<?php echo esc_attr($primary_color); ?>">
            <small><?php esc_html_e('Enter a hex color (e.g., #007bff)', 'gremaza-wpb-addons'); ?></small>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['phone'] = !empty($new_instance['phone']) ? sanitize_text_field($new_instance['phone']) : '';
        $instance['email'] = !empty($new_instance['email']) ? sanitize_email($new_instance['email']) : '';
        $instance['address'] = !empty($new_instance['address']) ? sanitize_textarea_field($new_instance['address']) : '';
        $instance['facebook_url'] = !empty($new_instance['facebook_url']) ? esc_url_raw($new_instance['facebook_url']) : '';
        $instance['instagram_url'] = !empty($new_instance['instagram_url']) ? esc_url_raw($new_instance['instagram_url']) : '';
        $instance['linkedin_url'] = !empty($new_instance['linkedin_url']) ? esc_url_raw($new_instance['linkedin_url']) : '';
        $instance['youtube_url'] = !empty($new_instance['youtube_url']) ? esc_url_raw($new_instance['youtube_url']) : '';
        $instance['show_form'] = !empty($new_instance['show_form']) && $new_instance['show_form'] === 'yes' ? 'yes' : 'no';
        $instance['recipient_email'] = !empty($new_instance['recipient_email']) ? sanitize_email($new_instance['recipient_email']) : '';
        $instance['primary_color'] = !empty($new_instance['primary_color']) ? sanitize_hex_color($new_instance['primary_color']) : '#007bff';

        return $instance;
    }
}

/**
 * Register the widget
 */
function gremaza_register_contact_widget() {
    register_widget('Gremaza_Contact_Widget');
}
add_action('widgets_init', 'gremaza_register_contact_widget');
