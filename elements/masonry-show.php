<?php
/**
 * Masonry Show Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaMasonryShow {
    
    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_masonry_show', array($this, 'render_shortcode'));
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
            'name' => 'Masonry Show',
            'base' => 'gremaza_masonry_show',
            'category' => 'By Gremaza',
            'description' => 'Masonry layout with 8 items',
            'icon' => 'icon-wpb-grid-layout',
            'params' => array(
                // Item 1
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 1 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item1_image',
                    'description' => __('50% width, 550px height', 'gremaza-wpb-addons'),
                    'group' => 'Item 1',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 1 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item1_title',
                    'value' => 'Item 1',
                    'group' => 'Item 1',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 1 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item1_link',
                    'group' => 'Item 1',
                ),
                
                // Item 2
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 2 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item2_image',
                    'description' => __('25% width, 550px height (tall)', 'gremaza-wpb-addons'),
                    'group' => 'Item 2',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 2 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item2_title',
                    'value' => 'Item 2',
                    'group' => 'Item 2',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 2 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item2_link',
                    'group' => 'Item 2',
                ),
                
                // Item 3
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 3 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item3_image',
                    'description' => __('25% width, 350px height', 'gremaza-wpb-addons'),
                    'group' => 'Item 3',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 3 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item3_title',
                    'value' => 'Item 3',
                    'group' => 'Item 3',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 3 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item3_link',
                    'group' => 'Item 3',
                ),
                
                // Item 4
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 4 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item4_image',
                    'description' => __('25% width, 350px height', 'gremaza-wpb-addons'),
                    'group' => 'Item 4',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 4 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item4_title',
                    'value' => 'Item 4',
                    'group' => 'Item 4',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 4 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item4_link',
                    'group' => 'Item 4',
                ),
                
                // Item 5
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 5 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item5_image',
                    'description' => __('25% width, 350px height', 'gremaza-wpb-addons'),
                    'group' => 'Item 5',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 5 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item5_title',
                    'value' => 'Item 5',
                    'group' => 'Item 5',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 5 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item5_link',
                    'group' => 'Item 5',
                ),
                
                // Item 6
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 6 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item6_image',
                    'description' => __('25% width, 350px height', 'gremaza-wpb-addons'),
                    'group' => 'Item 6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 6 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item6_title',
                    'value' => 'Item 6',
                    'group' => 'Item 6',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 6 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item6_link',
                    'group' => 'Item 6',
                ),
                
                // Item 7
                array(
                    'type' => 'attach_image',
                    'heading' => __('Item 7 - Image', 'gremaza-wpb-addons'),
                    'param_name' => 'item7_image',
                    'description' => __('25% width, 550px height (tall, under Item 3)', 'gremaza-wpb-addons'),
                    'group' => 'Item 7',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Item 7 - Title', 'gremaza-wpb-addons'),
                    'param_name' => 'item7_title',
                    'value' => 'Item 7',
                    'group' => 'Item 7',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Item 7 - Link', 'gremaza-wpb-addons'),
                    'param_name' => 'item7_link',
                    'group' => 'Item 7',
                ),
            )
        ));
    }
    
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'item1_image' => '',
            'item1_title' => 'Item 1',
            'item1_link' => '',
            'item2_image' => '',
            'item2_title' => 'Item 2',
            'item2_link' => '',
            'item3_image' => '',
            'item3_title' => 'Item 3',
            'item3_link' => '',
            'item4_image' => '',
            'item4_title' => 'Item 4',
            'item4_link' => '',
            'item5_image' => '',
            'item5_title' => 'Item 5',
            'item5_link' => '',
            'item6_image' => '',
            'item6_title' => 'Item 6',
            'item6_link' => '',
            'item7_image' => '',
            'item7_title' => 'Item 7',
            'item7_link' => '',
        ), $atts);
        
        $masonry_id = 'gremaza-masonry-show-' . uniqid();
        
        $output = '<div class="gremaza-masonry-show" id="' . esc_attr($masonry_id) . '">';
        
        // Items in display order for CSS Grid positioning
        $items_config = array(1, 2, 3, 4, 5, 6, 7);
        
        foreach ($items_config as $num) {
            $image_id = $atts['item' . $num . '_image'];
            $title = $atts['item' . $num . '_title'];
            $link = $atts['item' . $num . '_link'];
            
            if (empty($image_id)) {
                continue;
            }
            
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            
            // Parse link
            $link_url = '';
            $link_target = '_self';
            if (!empty($link)) {
                $parsed_link = vc_build_link($link);
                $link_url = $parsed_link['url'];
                $link_target = !empty($parsed_link['target']) ? $parsed_link['target'] : '_self';
            }
            
            $output .= '<div class="gremaza-masonry-item gremaza-masonry-item-' . $num . '" style="background-image: url(' . esc_url($image_url) . ');">'; 
            
            if (!empty($link_url)) {
                $output .= '<a href="' . esc_url($link_url) . '" target="' . esc_attr($link_target) . '" class="gremaza-masonry-link">';
            }
            
            $output .= '<div class="gremaza-masonry-overlay"></div>';
            $output .= '<div class="gremaza-masonry-content">';
            
            if (!empty($title)) {
                $output .= '<h3 class="gremaza-masonry-title">' . esc_html($title) . '</h3>';
            }
            
            $output .= '</div>';
            
            if (!empty($link_url)) {
                $output .= '</a>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
}

// Initialize the element
new GremazaMasonryShow();

// Register for WPBakery
if (class_exists('WPBakeryShortCode')) {
    class WPBakeryShortCode_Gremaza_Masonry_Show extends WPBakeryShortCode {}
}
