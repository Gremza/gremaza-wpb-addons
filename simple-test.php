<?php
/**
 * Simple Direct Test
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Direct registration
function gremaza_register_simple_element() {
    if (!function_exists('vc_map')) {
        return;
    }
    
    // Prevent multiple registrations
    static $registered = false;
    if ($registered) {
        return;
    }
    $registered = true;
    
    vc_map(array(
        'name' => 'Simple Hero',
        'base' => 'simple_hero',
        'category' => 'By Gremaza',
        'description' => 'A simple hero element',
        'icon' => 'icon-wpb-ui-separator',
        'show_settings_on_create' => true,
        'is_container' => false,
        'as_parent' => array('except' => ''),
        'content_element' => true,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => 'Title',
                'param_name' => 'title',
                'value' => 'Sample Title',
                'admin_label' => true,
            ),
        ),
    ));
}

// Hook to multiple actions to ensure registration
add_action('vc_before_init', 'gremaza_register_simple_element');
add_action('init', 'gremaza_register_simple_element', 20);
add_action('wp_loaded', 'gremaza_register_simple_element');

// Simple shortcode
function simple_hero_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Default Title',
    ), $atts);
    
    return '<h2>' . esc_html($atts['title']) . '</h2>';
}
add_shortcode('simple_hero', 'simple_hero_shortcode');
