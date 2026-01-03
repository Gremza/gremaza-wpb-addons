<?php
/**
 * Fullscreen Slideshow Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaFullscreenSlideshow {
    
    public function __construct() {
        // Hook into multiple actions to ensure registration
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        // Register the shortcode
        add_shortcode('gremaza_fullscreen_slideshow', array($this, 'render_shortcode'));
    }
    
    public function map_shortcode() {
        // Check if vc_map function exists
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
            'name' => 'Fullscreen Slideshow',
            'base' => 'gremaza_fullscreen_slideshow',
            'category' => 'By Gremaza',
            'description' => 'Create a fullscreen slideshow with customizable slides',
            'icon' => 'icon-wpb-ui-carousel',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                // General Settings Group
                array(
                    'type' => 'textfield',
                    'heading' => __('Slideshow Height', 'gremaza-wpb-addons'),
                    'param_name' => 'slideshow_height',
                    'value' => '100vh',
                    'description' => __('Enter height (e.g., 100vh, 600px, 80vh)', 'gremaza-wpb-addons'),
                    'group' => __('General', 'gremaza-wpb-addons'),
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
                    'description' => __('Enable autoplay for slides', 'gremaza-wpb-addons'),
                    'group' => __('General', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Autoplay Speed (ms)', 'gremaza-wpb-addons'),
                    'param_name' => 'autoplay_speed',
                    'value' => '5000',
                    'description' => __('Time between slides in milliseconds (e.g., 5000 = 5 seconds)', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'autoplay',
                        'value' => 'yes',
                    ),
                    'group' => __('General', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'dropdown',
                    'heading' => __('Show Navigation Arrows', 'gremaza-wpb-addons'),
                    'param_name' => 'show_arrows',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'description' => __('Show previous/next navigation arrows', 'gremaza-wpb-addons'),
                    'group' => __('General', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'dropdown',
                    'heading' => __('Show Dots Navigation', 'gremaza-wpb-addons'),
                    'param_name' => 'show_dots',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'description' => __('Show dot indicators for slides', 'gremaza-wpb-addons'),
                    'group' => __('General', 'gremaza-wpb-addons'),
                ),
                
                // Slide 1
                array(
                    'type' => 'checkbox',
                    'heading' => __('Enable Slide 1', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_enable',
                    'value' => array(__('Yes', 'gremaza-wpb-addons') => 'yes'),
                    'std' => 'yes',
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_image',
                    'value' => '',
                    'description' => __('Upload background image for slide 1', 'gremaza-wpb-addons'),
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide1_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_title',
                    'value' => '',
                    'description' => __('Enter slide title', 'gremaza-wpb-addons'),
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide1_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_description',
                    'value' => '',
                    'description' => __('Enter slide description', 'gremaza-wpb-addons'),
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide1_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_button_text',
                    'value' => '',
                    'description' => __('Enter button text', 'gremaza-wpb-addons'),
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide1_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide1_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'slide1_button_bg',
                    'value' => '#007bff',
                    'description' => __('Select button background color', 'gremaza-wpb-addons'),
                    'group' => __('Slide 1', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide1_enable',
                        'not_empty' => true,
                    ),
                ),
                
                // Slide 2
                array(
                    'type' => 'checkbox',
                    'heading' => __('Enable Slide 2', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_enable',
                    'value' => array(__('Yes', 'gremaza-wpb-addons') => 'yes'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_image',
                    'value' => '',
                    'description' => __('Upload background image for slide 2', 'gremaza-wpb-addons'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide2_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_title',
                    'value' => '',
                    'description' => __('Enter slide title', 'gremaza-wpb-addons'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide2_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_description',
                    'value' => '',
                    'description' => __('Enter slide description', 'gremaza-wpb-addons'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide2_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_button_text',
                    'value' => '',
                    'description' => __('Enter button text', 'gremaza-wpb-addons'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide2_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide2_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'slide2_button_bg',
                    'value' => '#007bff',
                    'description' => __('Select button background color', 'gremaza-wpb-addons'),
                    'group' => __('Slide 2', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide2_enable',
                        'not_empty' => true,
                    ),
                ),
                
                // Slide 3
                array(
                    'type' => 'checkbox',
                    'heading' => __('Enable Slide 3', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_enable',
                    'value' => array(__('Yes', 'gremaza-wpb-addons') => 'yes'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_image',
                    'value' => '',
                    'description' => __('Upload background image for slide 3', 'gremaza-wpb-addons'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide3_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_title',
                    'value' => '',
                    'description' => __('Enter slide title', 'gremaza-wpb-addons'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide3_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_description',
                    'value' => '',
                    'description' => __('Enter slide description', 'gremaza-wpb-addons'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide3_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_button_text',
                    'value' => '',
                    'description' => __('Enter button text', 'gremaza-wpb-addons'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide3_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide3_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'slide3_button_bg',
                    'value' => '#007bff',
                    'description' => __('Select button background color', 'gremaza-wpb-addons'),
                    'group' => __('Slide 3', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide3_enable',
                        'not_empty' => true,
                    ),
                ),
                
                // Slide 4
                array(
                    'type' => 'checkbox',
                    'heading' => __('Enable Slide 4', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_enable',
                    'value' => array(__('Yes', 'gremaza-wpb-addons') => 'yes'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_image',
                    'value' => '',
                    'description' => __('Upload background image for slide 4', 'gremaza-wpb-addons'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide4_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_title',
                    'value' => '',
                    'description' => __('Enter slide title', 'gremaza-wpb-addons'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide4_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_description',
                    'value' => '',
                    'description' => __('Enter slide description', 'gremaza-wpb-addons'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide4_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_button_text',
                    'value' => '',
                    'description' => __('Enter button text', 'gremaza-wpb-addons'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide4_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide4_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'slide4_button_bg',
                    'value' => '#007bff',
                    'description' => __('Select button background color', 'gremaza-wpb-addons'),
                    'group' => __('Slide 4', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide4_enable',
                        'not_empty' => true,
                    ),
                ),
                
                // Slide 5
                array(
                    'type' => 'checkbox',
                    'heading' => __('Enable Slide 5', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_enable',
                    'value' => array(__('Yes', 'gremaza-wpb-addons') => 'yes'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_image',
                    'value' => '',
                    'description' => __('Upload background image for slide 5', 'gremaza-wpb-addons'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide5_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_title',
                    'value' => '',
                    'description' => __('Enter slide title', 'gremaza-wpb-addons'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide5_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_description',
                    'value' => '',
                    'description' => __('Enter slide description', 'gremaza-wpb-addons'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide5_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_button_text',
                    'value' => '',
                    'description' => __('Enter button text', 'gremaza-wpb-addons'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide5_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide5_enable',
                        'not_empty' => true,
                    ),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'slide5_button_bg',
                    'value' => '#007bff',
                    'description' => __('Select button background color', 'gremaza-wpb-addons'),
                    'group' => __('Slide 5', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'slide5_enable',
                        'not_empty' => true,
                    ),
                ),
                
                // Extra CSS Class
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                    'value' => '',
                    'description' => __('Add extra CSS class for custom styling', 'gremaza-wpb-addons'),
                    'group' => __('General', 'gremaza-wpb-addons'),
                ),
            )
        ));
    }
    
    public function render_shortcode($atts) {
        // Extract shortcode attributes
        $atts = shortcode_atts(array(
            'slideshow_height' => '100vh',
            'autoplay' => 'yes',
            'autoplay_speed' => '5000',
            'show_arrows' => 'yes',
            'show_dots' => 'yes',
            'extra_class' => '',
            // Slide 1
            'slide1_enable' => 'yes',
            'slide1_image' => '',
            'slide1_title' => '',
            'slide1_description' => '',
            'slide1_button_text' => '',
            'slide1_button_link' => '',
            'slide1_button_bg' => '#007bff',
            // Slide 2
            'slide2_enable' => '',
            'slide2_image' => '',
            'slide2_title' => '',
            'slide2_description' => '',
            'slide2_button_text' => '',
            'slide2_button_link' => '',
            'slide2_button_bg' => '#007bff',
            // Slide 3
            'slide3_enable' => '',
            'slide3_image' => '',
            'slide3_title' => '',
            'slide3_description' => '',
            'slide3_button_text' => '',
            'slide3_button_link' => '',
            'slide3_button_bg' => '#007bff',
            // Slide 4
            'slide4_enable' => '',
            'slide4_image' => '',
            'slide4_title' => '',
            'slide4_description' => '',
            'slide4_button_text' => '',
            'slide4_button_link' => '',
            'slide4_button_bg' => '#007bff',
            // Slide 5
            'slide5_enable' => '',
            'slide5_image' => '',
            'slide5_title' => '',
            'slide5_description' => '',
            'slide5_button_text' => '',
            'slide5_button_link' => '',
            'slide5_button_bg' => '#007bff',
        ), $atts);
        
        // Generate unique ID for this slideshow
        $slideshow_id = 'gremaza-slideshow-' . uniqid();
        
        // Build CSS classes
        $css_class = 'gremaza-fullscreen-slideshow';
        if (!empty($atts['extra_class'])) {
            $css_class .= ' ' . esc_attr($atts['extra_class']);
        }
        
        // Collect enabled slides
        $slides = array();
        for ($i = 1; $i <= 5; $i++) {
            $enable_key = "slide{$i}_enable";
            if ($atts[$enable_key] === 'yes') {
                $slides[] = array(
                    'image' => $atts["slide{$i}_image"],
                    'title' => $atts["slide{$i}_title"],
                    'description' => $atts["slide{$i}_description"],
                    'button_text' => $atts["slide{$i}_button_text"],
                    'button_link' => $atts["slide{$i}_button_link"],
                    'button_bg' => $atts["slide{$i}_button_bg"],
                );
            }
        }
        
        // If no slides are enabled, return empty
        if (empty($slides)) {
            return '<div class="gremaza-slideshow-notice" style="padding: 20px; background: #f0f0f0; text-align: center;">Please enable at least one slide.</div>';
        }
        
        // Start output buffering
        ob_start();
        ?>
        <div class="<?php echo esc_attr($css_class); ?>" 
             id="<?php echo esc_attr($slideshow_id); ?>"
             data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
             data-autoplay-speed="<?php echo esc_attr($atts['autoplay_speed']); ?>"
             style="height: <?php echo esc_attr($atts['slideshow_height']); ?>;">
            
            <div class="gremaza-slideshow-container">
                <?php foreach ($slides as $index => $slide): 
                    $image_url = '';
                    if (!empty($slide['image'])) {
                        $image_url = wp_get_attachment_image_url($slide['image'], 'full');
                    }
                    
                    // Parse button link
                    $button_url = '#';
                    $button_target = '_self';
                    if (!empty($slide['button_link'])) {
                        $button_link_data = vc_build_link($slide['button_link']);
                        $button_url = isset($button_link_data['url']) ? $button_link_data['url'] : '#';
                        $button_target = isset($button_link_data['target']) ? $button_link_data['target'] : '_self';
                    }
                ?>
                    <div class="gremaza-slide <?php echo $index === 0 ? 'active' : ''; ?>"
                         style="background-image: url('<?php echo esc_url($image_url); ?>');">
                        <div class="gremaza-slide-overlay"></div>
                        <div class="gremaza-slide-content">
                            <?php if (!empty($slide['title'])): ?>
                                <h1 class="gremaza-slide-title"><?php echo esc_html($slide['title']); ?></h1>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['description'])): ?>
                                <div class="gremaza-slide-description">
                                    <?php echo wp_kses_post(wpautop($slide['description'])); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['button_text'])): ?>
                                <div class="gremaza-slide-button-wrapper">
                                    <a href="<?php echo esc_url($button_url); ?>" 
                                       target="<?php echo esc_attr($button_target); ?>"
                                       class="gremaza-slide-button"
                                       style="background-color: <?php echo esc_attr($slide['button_bg']); ?>;">
                                        <?php echo esc_html($slide['button_text']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($atts['show_arrows'] === 'yes' && count($slides) > 1): ?>
                <button class="gremaza-slideshow-prev" aria-label="Previous slide">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button class="gremaza-slideshow-next" aria-label="Next slide">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            <?php endif; ?>
            
            <?php if ($atts['show_dots'] === 'yes' && count($slides) > 1): ?>
                <div class="gremaza-slideshow-dots">
                    <?php foreach ($slides as $index => $slide): ?>
                        <button class="gremaza-slideshow-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                data-slide="<?php echo $index; ?>"
                                aria-label="Go to slide <?php echo $index + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
}

// Don't initialize here - let the main plugin do it
