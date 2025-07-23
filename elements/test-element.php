<?php
/**
 * Simple Test Element for debugging
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Try multiple hooks to ensure registration
add_action('vc_before_init', 'gremaza_test_element');
add_action('init', 'gremaza_test_element', 20);
add_action('wp_loaded', 'gremaza_test_element');

function gremaza_test_element() {
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
        'name' => 'Test Element',
        'base' => 'gremaza_test',
        'category' => 'By Gremaza',
        'description' => 'Simple test element',
        'icon' => 'icon-wpb-ui-separator',
        'show_settings_on_create' => true,
        'is_container' => false,
        'content_element' => true,
        'as_parent' => array('except' => ''),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => 'Test Text',
                'param_name' => 'test_text',
                'value' => 'Hello World',
                'description' => 'Enter test text',
                'admin_label' => true,
            ),
        ),
    ));
}

// Register shortcode
add_shortcode('gremaza_test', 'gremaza_test_shortcode');

function gremaza_test_shortcode($atts) {
    $atts = shortcode_atts(array(
        'test_text' => 'Hello World',
    ), $atts);
    
    return '<div class="gremaza-test">' . esc_html($atts['test_text']) . '</div>';
}
