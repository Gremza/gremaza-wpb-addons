<?php
/**
 * Blog Content Element for WPBakery Page Builder
 * Centered content with flexible image positioning
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaBlogContent {
    
    public function __construct() {
        // Multiple hooks to ensure registration
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        
        // Register shortcode
        add_shortcode('gremaza_blog_content', array($this, 'render_shortcode'));
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
            'name' => __('Blog Content', 'gremaza-wpb-addons'),
            'base' => 'gremaza_blog_content',
            'category' => 'By Gremaza',
            'description' => __('Centered blog content with flexible image positioning', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-ui-separator',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                // Content
                array(
                    'type' => 'textarea_html',
                    'heading' => __('Content', 'gremaza-wpb-addons'),
                    'param_name' => 'content',
                    'value' => '',
                    'description' => __('Enter your blog content text', 'gremaza-wpb-addons'),
                ),
                
                // Image
                array(
                    'type' => 'attach_image',
                    'heading' => __('Image', 'gremaza-wpb-addons'),
                    'param_name' => 'image_id',
                    'value' => '',
                    'description' => __('Select an image', 'gremaza-wpb-addons'),
                    'admin_label' => true,
                ),
                
                // Image Position
                array(
                    'type' => 'dropdown',
                    'heading' => __('Image Position', 'gremaza-wpb-addons'),
                    'param_name' => 'image_position',
                    'value' => array(
                        __('No Image', 'gremaza-wpb-addons') => 'none',
                        __('Left (50% inside, 50% outside)', 'gremaza-wpb-addons') => 'left',
                        __('Right (50% inside, 50% outside)', 'gremaza-wpb-addons') => 'right',
                        __('Full Width Background', 'gremaza-wpb-addons') => 'full',
                    ),
                    'std' => 'none',
                    'description' => __('Choose how to position the image', 'gremaza-wpb-addons'),
                    'admin_label' => true,
                ),
                
                // Image Size
                array(
                    'type' => 'dropdown',
                    'heading' => __('Image Size', 'gremaza-wpb-addons'),
                    'param_name' => 'image_size',
                    'value' => array(
                        __('Large', 'gremaza-wpb-addons') => 'large',
                        __('Medium', 'gremaza-wpb-addons') => 'medium',
                        __('Full', 'gremaza-wpb-addons') => 'full',
                        __('Medium Large', 'gremaza-wpb-addons') => 'medium_large',
                    ),
                    'std' => 'large',
                    'description' => __('Select image size', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'image_position',
                        'value' => array('left', 'right', 'full'),
                    ),
                ),
                
                // Gap Size
                array(
                    'type' => 'textfield',
                    'heading' => __('Gap Between Text and Image', 'gremaza-wpb-addons'),
                    'param_name' => 'gap_size',
                    'value' => '15px',
                    'description' => __('e.g., 15px, 1rem', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'image_position',
                        'value' => array('left', 'right'),
                    ),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                // Container Max Width
                array(
                    'type' => 'textfield',
                    'heading' => __('Container Max Width', 'gremaza-wpb-addons'),
                    'param_name' => 'container_width',
                    'value' => '800px',
                    'description' => __('Maximum width of the content container', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                // Full Width Image Height
                array(
                    'type' => 'textfield',
                    'heading' => __('Full Width Image Height', 'gremaza-wpb-addons'),
                    'param_name' => 'full_image_height',
                    'value' => '550px',
                    'description' => __('Height for full-width background image', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'image_position',
                        'value' => 'full',
                    ),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                // Extra CSS Class
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
    
    public function render_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'image_id' => '',
            'image_position' => 'none',
            'image_size' => 'large',
            'gap_size' => '15px',
            'container_width' => '800px',
            'full_image_height' => '550px',
            'extra_class' => '',
        ), $atts);
        
        // Get content
        $content = wpb_js_remove_wpautop($content, true);
        
        // Get image URL
        $image_url = '';
        if (!empty($atts['image_id']) && $atts['image_position'] !== 'none') {
            $image_url = wp_get_attachment_image_url((int)$atts['image_id'], $atts['image_size']);
        }
        
        // Build classes
        $wrapper_classes = array('gremaza-blog-content');
        if (!empty($atts['extra_class'])) {
            $wrapper_classes[] = esc_attr($atts['extra_class']);
        }
        $wrapper_classes[] = 'gremaza-blog-content--' . esc_attr($atts['image_position']);
        
        // Build container style
        $container_style = 'max-width:' . esc_attr($atts['container_width']) . ';';
        
        ob_start();
        ?>
        <div class="<?php echo implode(' ', $wrapper_classes); ?>">
            <div class="blogcontair" style="<?php echo $container_style; ?>">
                
                <?php if ($atts['image_position'] === 'left' && $image_url): ?>
                    <div class="gremaza-blog-content__image gremaza-blog-content__image--left">
                        <img src="<?php echo esc_url($image_url); ?>" alt="" />
                    </div>
                <?php endif; ?>
                
                <?php if ($atts['image_position'] === 'right' && $image_url): ?>
                    <div class="gremaza-blog-content__image gremaza-blog-content__image--right">
                        <img src="<?php echo esc_url($image_url); ?>" alt="" />
                    </div>
                <?php endif; ?>
                
                <?php if ($atts['image_position'] === 'full' && $image_url): ?>
                    <div class="gremaza-blog-content__image gremaza-blog-content__image--full" style="background-image:url(<?php echo esc_url($image_url); ?>);height:<?php echo esc_attr($atts['full_image_height']); ?>;"></div>
                <?php endif; ?>
                
                <div class="gremaza-blog-content__text">
                    <?php echo $content; ?>
                </div>
                
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the element
new GremazaBlogContent();
