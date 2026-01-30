<?php
/**
 * Slider with Image Cover Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Parent Container
class GremazaImageCarousel {
    
    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_image_carousel', array($this, 'render_shortcode'));
    }
    
    public function map_shortcode() {
        if (!function_exists('vc_map')) {
            return;
        }
        
        static $registered = false;
        if ($registered) {
            return;
        }
        $registered = true;
        
        vc_map(array(
            'name' => 'Slider with Image Cover',
            'base' => 'gremaza_image_carousel',
            'category' => 'By Gremaza',
            'description' => 'Slider with full-cover background images - add slides repeatedly',
            'icon' => 'icon-wpb-images-carousel',
            'as_parent' => array('only' => 'gremaza_image_carousel_slide'),
            'content_element' => true,
            'show_settings_on_create' => true,
            'js_view' => 'VcColumnView',
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __('Carousel Style', 'gremaza-wpb-addons'),
                    'param_name' => 'carousel_style',
                    'value' => array(
                        __('Card Carousel Style', 'gremaza-wpb-addons') => 'card',
                        __('Logo Carousel Style', 'gremaza-wpb-addons') => 'logo',
                    ),
                    'std' => 'card',
                    'description' => __('Card style shows title, description and hover effects. Logo style shows only images with links.', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Autoplay', 'gremaza-wpb-addons'),
                    'param_name' => 'autoplay',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'description' => __('Enable autoplay', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Autoplay Speed (ms)', 'gremaza-wpb-addons'),
                    'param_name' => 'autoplay_speed',
                    'value' => '5000',
                    'description' => __('Autoplay speed in milliseconds', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Show Arrows', 'gremaza-wpb-addons'),
                    'param_name' => 'show_arrows',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'description' => __('Show navigation arrows', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Show Dots', 'gremaza-wpb-addons'),
                    'param_name' => 'show_dots',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'description' => __('Show dot indicators', 'gremaza-wpb-addons'),
                ),
            )
        ));
    }
    
    public function render_shortcode($atts, $content = null) {
        global $gremaza_carousel_style;

        $atts = shortcode_atts(array(
            'carousel_style' => 'card',
            'autoplay' => 'yes',
            'autoplay_speed' => '5000',
            'show_arrows' => 'yes',
            'show_dots' => 'yes',
        ), $atts);

        // Store style globally for child slides to access
        $gremaza_carousel_style = $atts['carousel_style'];

        $carousel_id = 'gremaza-image-carousel-' . uniqid();
        $style_class = $atts['carousel_style'] === 'logo' ? ' gremaza-logo-carousel' : ' gremaza-card-carousel';

        $output = '<div class="gremaza-image-carousel' . $style_class . '" id="' . esc_attr($carousel_id) . '"
                        data-autoplay="' . esc_attr($atts['autoplay']) . '"
                        data-autoplay-speed="' . esc_attr($atts['autoplay_speed']) . '"
                        data-style="' . esc_attr($atts['carousel_style']) . '">';
        
        $output .= '<div class="gremaza-image-carousel-slides">';
        $output .= do_shortcode($content);
        $output .= '</div>';
        
        // Count slides
        $slide_count = substr_count($content, '[gremaza_image_carousel_slide');
        
        if ($atts['show_arrows'] === 'yes' && $slide_count > 1) {
            $output .= '<button class="gremaza-image-carousel-prev" aria-label="Previous">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                        <button class="gremaza-image-carousel-next" aria-label="Next">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>';
        }
        
        if ($atts['show_dots'] === 'yes' && $slide_count > 1) {
            $output .= '<div class="gremaza-image-carousel-dots">';
            for ($i = 0; $i < $slide_count; $i++) {
                $active_class = $i === 0 ? ' active' : '';
                $output .= '<button class="gremaza-image-carousel-dot' . $active_class . '" 
                                    data-slide="' . $i . '"
                                    aria-label="Go to slide ' . ($i + 1) . '">
                            </button>';
            }
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
}

// Child Slide Element
class GremazaImageCarouselSlide {
    
    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_image_carousel_slide', array($this, 'render_shortcode'));
    }
    
    public function map_shortcode() {
        if (!function_exists('vc_map')) {
            return;
        }
        
        static $registered = false;
        if ($registered) {
            return;
        }
        $registered = true;
        
        vc_map(array(
            'name' => 'Slide',
            'base' => 'gremaza_image_carousel_slide',
            'content_element' => true,
            'as_child' => array('only' => 'gremaza_image_carousel'),
            'icon' => 'icon-wpb-single-image',
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'image',
                    'description' => __('Select background image', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'title',
                    'value' => '',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'description',
                    'value' => '',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'button_text',
                    'value' => '',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'button_link',
                ),
            )
        ));
    }
    
    public function render_shortcode($atts) {
        global $gremaza_carousel_style;
        static $slide_index = 0;

        $atts = shortcode_atts(array(
            'image' => '',
            'title' => '',
            'description' => '',
            'button_text' => '',
            'button_link' => '',
        ), $atts);

        if (empty($atts['image'])) {
            return '';
        }

        $image_url = wp_get_attachment_image_url($atts['image'], 'full');
        $carousel_style = isset($gremaza_carousel_style) ? $gremaza_carousel_style : 'card';

        $button_link = '';
        $button_target = '_self';
        if (!empty($atts['button_link'])) {
            $link = vc_build_link($atts['button_link']);
            $button_link = $link['url'];
            $button_target = !empty($link['target']) ? $link['target'] : '_self';
        }

        $active_class = $slide_index === 0 ? ' active' : '';
        $slide_index++;

        // Logo style: simple image with link, no overlay/content
        if ($carousel_style === 'logo') {
            $output = '<div class="gremaza-image-slide gremaza-logo-slide' . $active_class . '">';

            if (!empty($button_link)) {
                $output .= '<a href="' . esc_url($button_link) . '" target="' . esc_attr($button_target) . '" class="gremaza-logo-slide-link">';
            }

            $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($atts['title']) . '" class="gremaza-logo-slide-image">';

            if (!empty($button_link)) {
                $output .= '</a>';
            }

            $output .= '</div>';

            return $output;
        }

        // Card style: full content with overlay
        $output = '<div class="gremaza-image-slide' . $active_class . '"
                         style="background-image: url(\'' . esc_url($image_url) . '\');">
                        <div class="gremaza-image-slide-overlay"></div>
                        <div class="gremaza-image-slide-content">';

        if (!empty($atts['title'])) {
            $output .= '<h2 class="gremaza-image-slide-title">' . esc_html($atts['title']) . '</h2>';
        }

        if (!empty($atts['description'])) {
            $output .= '<p class="gremaza-image-slide-description">' . esc_html($atts['description']) . '</p>';
        }

        if (!empty($atts['button_text']) && !empty($button_link)) {
            $output .= '<a href="' . esc_url($button_link) . '"
                           target="' . esc_attr($button_target) . '"
                           class="gremaza-image-slide-button">' . esc_html($atts['button_text']) . '</a>';
        }

        $output .= '</div></div>';

        return $output;
    }
}

// Initialize the elements
new GremazaImageCarousel();
new GremazaImageCarouselSlide();

// Register container for WPBakery
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Gremaza_Image_Carousel extends WPBakeryShortCodesContainer {}
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Gremaza_Image_Carousel_Slide extends WPBakeryShortCode {}
}
