<?php
/**
 * Plugin Name: Gremaza WPB Addons
 * Plugin URI: https://github.com/marselpreci/gremaza-wpb-addons
 * Description: Additional elements for WPBakery Page Builder with custom styles and functionality.
 * Version: 1.4.0
 * Author: Marsel Preci
 * Author URI: https://marselpreci.com
 * License: GPL v2 or later
 * Text Domain: gremaza-wpb-addons
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GREMAZA_WPB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GREMAZA_WPB_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('GREMAZA_WPB_PLUGIN_VERSION', '1.4.0');

class GremazaWPBAddons {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
    }
    
    public function init() {
        // Check and show notice if WPBakery is not active
        add_action('admin_notices', array($this, 'wpbakery_missing_notice'));
        
        // Initialize the plugin with multiple hooks to ensure it works
        add_action('vc_before_init', array($this, 'load_elements'));
        add_action('init', array($this, 'load_elements'), 20);
        add_action('wp_loaded', array($this, 'load_elements'));
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('init', array($this, 'load_textdomain'), 20);
    }
    
    public function wpbakery_missing_notice() {
        if (!function_exists('vc_map')) {
            ?>
            <div class="notice notice-error">
                <p><?php _e('Gremaza WPB Addons requires WPBakery Page Builder to be installed and activated.', 'gremaza-wpb-addons'); ?></p>
            </div>
            <?php
        }
    }
    
    public function load_elements() {
        // Prevent multiple loads
        static $loaded = false;
        if ($loaded) {
            return;
        }
        
        // Check if vc_map exists
        if (!function_exists('vc_map')) {
            return;
        }
        
        $loaded = true;
        
    // Load hero banner element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/hero-banner.php';
    // Load reviews element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/reviews.php';
    // Load citation element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/citation.php';
    // Load article box element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/article-box.php';
    // Load read more overlay element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/read-more.php';
    // Load image cover link element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/image-cover-link.php';
    // Load separator element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/separator.php';
    // Load animated divider element
    require_once GREMAZA_WPB_PLUGIN_PATH . 'elements/animated-divider.php';
        
    // Initialize elements
    new GremazaHeroBanner();
    new GremazaReviews();
    new GremazaCitation();
    new GremazaReadMore();
    new GremazaImageCoverLink();
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style(
            'gremaza-wpb-addons-style',
            GREMAZA_WPB_PLUGIN_URL . 'assets/css/style.css',
            array(),
            GREMAZA_WPB_PLUGIN_VERSION
        );
        
        // Main frontend scripts (includes reviews slider)
        wp_enqueue_script(
            'gremaza-wpb-addons-script',
            GREMAZA_WPB_PLUGIN_URL . 'assets/js/script.js',
            array('jquery'),
            GREMAZA_WPB_PLUGIN_VERSION,
            true
        );

        wp_enqueue_script(
            'gremaza-wpb-addons-hero-banner',
            GREMAZA_WPB_PLUGIN_URL . 'assets/js/hero-banner.js',
            array(),
            GREMAZA_WPB_PLUGIN_VERSION,
            true
        );
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('gremaza-wpb-addons', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}

// Initialize the plugin
new GremazaWPBAddons();

// Activation hook
register_activation_hook(__FILE__, 'gremaza_wpb_addons_activate');

function gremaza_wpb_addons_activate() {
    // Include the plugin functions if not already included
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    // Check if WPBakery Page Builder is active
    if (!is_plugin_active('js_composer/js_composer.php')) {
        // Store notice to show after activation
        set_transient('gremaza_wpb_addons_activation_notice', true, 30);
    }
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'gremaza_wpb_addons_deactivate');

function gremaza_wpb_addons_deactivate() {
    // Clean up any temporary data if needed
    delete_transient('gremaza_wpb_addons_activation_notice');
}

// Show activation notice if WPBakery is not active
add_action('admin_notices', 'gremaza_wpb_addons_activation_notice');

function gremaza_wpb_addons_activation_notice() {
    // Include the plugin functions if not already included
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    
    if (get_transient('gremaza_wpb_addons_activation_notice') && !is_plugin_active('js_composer/js_composer.php')) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e('<strong>Gremaza WPB Addons</strong> has been activated, but requires <strong>WPBakery Page Builder</strong> to function properly. Please install and activate WPBakery Page Builder.', 'gremaza-wpb-addons'); ?></p>
        </div>
        <?php
        delete_transient('gremaza_wpb_addons_activation_notice');
    }
}
