<?php
/**
 * Image Card Carousel Element for WPBakery Page Builder
 * Similar to pikat carousel with image cards containing title and description
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Parent Container
class GremazaImageCardCarousel {

    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_image_card_carousel', array($this, 'render_shortcode'));
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
            'name' => 'Image Card Carousel',
            'base' => 'gremaza_image_card_carousel',
            'category' => 'By Gremaza',
            'description' => 'Carousel with image cards containing title and description',
            'icon' => 'icon-wpb-images-carousel',
            'as_parent' => array('only' => 'gremaza_image_card_carousel_item'),
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
                        __('No', 'gremaza-wpb-addons') => 'no',
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                    ),
                    'std' => 'no',
                    'description' => __('Enable autoplay', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Autoplay Speed (ms)', 'gremaza-wpb-addons'),
                    'param_name' => 'autoplay_speed',
                    'value' => '5000',
                    'description' => __('Autoplay speed in milliseconds', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'autoplay',
                        'value' => 'yes',
                    ),
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
                    'heading' => __('Items to Show (Desktop)', 'gremaza-wpb-addons'),
                    'param_name' => 'items_desktop',
                    'value' => array(
                        '8' => '8',
                        '7' => '7',
                        '6' => '6',
                        '5' => '5',
                        '4' => '4',
                        '3' => '3',
                        '2' => '2',
                        '1' => '1',
                    ),
                    'std' => '3',
                    'description' => __('Number of items to show on desktop', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Items to Show (Tablet)', 'gremaza-wpb-addons'),
                    'param_name' => 'items_tablet',
                    'value' => array(
                        '3' => '3',
                        '2' => '2',
                        '1' => '1',
                    ),
                    'std' => '2',
                    'description' => __('Number of items to show on tablet', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Items to Show (Mobile)', 'gremaza-wpb-addons'),
                    'param_name' => 'items_mobile',
                    'value' => array(
                        '2' => '2',
                        '1' => '1',
                    ),
                    'std' => '1',
                    'description' => __('Number of items to show on mobile', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Card Height (px)', 'gremaza-wpb-addons'),
                    'param_name' => 'card_height',
                    'value' => '400',
                    'description' => __('Height of each card in pixels', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Gap Between Items (px)', 'gremaza-wpb-addons'),
                    'param_name' => 'gap',
                    'value' => '20',
                    'description' => __('Gap between carousel items in pixels', 'gremaza-wpb-addons'),
                ),
            )
        ));
    }

    public function render_shortcode($atts, $content = null) {
        global $gremaza_card_carousel_style;

        $atts = shortcode_atts(array(
            'carousel_style' => 'card',
            'autoplay' => 'no',
            'autoplay_speed' => '5000',
            'show_arrows' => 'yes',
            'items_desktop' => '3',
            'items_tablet' => '2',
            'items_mobile' => '1',
            'card_height' => '400',
            'gap' => '20',
        ), $atts);

        // Store style globally for child items to access
        $gremaza_card_carousel_style = $atts['carousel_style'];

        $carousel_id = 'gremaza-card-carousel-' . uniqid();
        $style_class = $atts['carousel_style'] === 'logo' ? ' gremaza-logo-carousel-style' : ' gremaza-card-carousel-style';

        $output = '<div class="gremaza-card-carousel-wrapper' . $style_class . '">';
        $output .= '<div class="gremaza-card-carousel" id="' . esc_attr($carousel_id) . '"
                        data-autoplay="' . esc_attr($atts['autoplay']) . '"
                        data-autoplay-speed="' . esc_attr($atts['autoplay_speed']) . '"
                        data-items-desktop="' . esc_attr($atts['items_desktop']) . '"
                        data-items-tablet="' . esc_attr($atts['items_tablet']) . '"
                        data-items-mobile="' . esc_attr($atts['items_mobile']) . '"
                        data-gap="' . esc_attr($atts['gap']) . '"
                        data-style="' . esc_attr($atts['carousel_style']) . '"
                        style="--card-height: ' . esc_attr($atts['card_height']) . 'px; --card-gap: ' . esc_attr($atts['gap']) . 'px;">';

        // Count items
        $item_count = substr_count($content, '[gremaza_image_card_carousel_item');

        if ($atts['show_arrows'] === 'yes' && $item_count > 1) {
            $output .= '<button class="gremaza-card-carousel-arrow gremaza-card-carousel-prev" aria-label="Previous">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>';
        }

        $output .= '<div class="gremaza-card-carousel-track">';
        $output .= do_shortcode($content);
        $output .= '</div>';

        if ($atts['show_arrows'] === 'yes' && $item_count > 1) {
            $output .= '<button class="gremaza-card-carousel-arrow gremaza-card-carousel-next" aria-label="Next">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>';
        }

        $output .= '</div>';
        $output .= '</div>';

        return $output;
    }
}

// Child Card Item Element
class GremazaImageCardCarouselItem {

    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_image_card_carousel_item', array($this, 'render_shortcode'));
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
            'name' => 'Card Item',
            'base' => 'gremaza_image_card_carousel_item',
            'content_element' => true,
            'as_child' => array('only' => 'gremaza_image_card_carousel'),
            'icon' => 'icon-wpb-single-image',
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => __('Image', 'gremaza-wpb-addons'),
                    'param_name' => 'image',
                    'description' => __('Select card image', 'gremaza-wpb-addons'),
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
                    'type' => 'vc_link',
                    'heading' => __('Link', 'gremaza-wpb-addons'),
                    'param_name' => 'link',
                    'description' => __('Optional link for the card', 'gremaza-wpb-addons'),
                ),
            )
        ));
    }

    public function render_shortcode($atts) {
        global $gremaza_card_carousel_style;
        static $item_index = 0;

        $atts = shortcode_atts(array(
            'image' => '',
            'title' => '',
            'description' => '',
            'link' => '',
        ), $atts);

        $carousel_style = isset($gremaza_card_carousel_style) ? $gremaza_card_carousel_style : 'card';

        $image_url = '';
        if (!empty($atts['image'])) {
            $image_data = wp_get_attachment_image_src($atts['image'], 'large');
            if ($image_data) {
                $image_url = $image_data[0];
            }
        }

        $link_url = '';
        $link_target = '_self';
        if (!empty($atts['link'])) {
            $link = vc_build_link($atts['link']);
            $link_url = $link['url'];
            $link_target = !empty($link['target']) ? $link['target'] : '_self';
        }

        $item_class = $carousel_style === 'logo' ? 'gremaza-card-item gremaza-logo-item' : 'gremaza-card-item';
        $output = '<div class="' . esc_attr($item_class) . '" data-index="' . esc_attr($item_index) . '">';

        if (!empty($link_url)) {
            $output .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" class="gremaza-card-link">';
        }

        $output .= '<div class="gremaza-card-image-wrap">';
        if ($image_url) {
            $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($atts['title']) . '" />';
        } else {
            $output .= '<div class="gremaza-card-no-image"></div>';
        }
        $output .= '</div>';

        // Only show content for card style
        if ($carousel_style === 'card') {
            $output .= '<div class="gremaza-card-content">';
            if (!empty($atts['title'])) {
                $output .= '<h3 class="gremaza-card-title">' . esc_html($atts['title']) . '</h3>';
            }
            if (!empty($atts['description'])) {
                $output .= '<p class="gremaza-card-description">' . esc_html($atts['description']) . '</p>';
            }
            $output .= '</div>';
        }

        if (!empty($link_url)) {
            $output .= '</a>';
        }

        $output .= '</div>';

        $item_index++;

        return $output;
    }
}

// Initialize the elements
new GremazaImageCardCarousel();
new GremazaImageCardCarouselItem();

// Register container for WPBakery
if (class_exists('WPBakeryShortCodesContainer')) {
    class WPBakeryShortCode_Gremaza_Image_Card_Carousel extends WPBakeryShortCodesContainer {}
}
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Gremaza_Image_Card_Carousel_Item extends WPBakeryShortCode {}
}
