<?php
/**
 * Hero Banner Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaHeroBanner {
    
    public function __construct() {
        // Hook into multiple actions to ensure registration
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        // Register the shortcode
        add_shortcode('gremaza_hero_banner', array($this, 'render_shortcode'));
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
            'name' => 'Hero Banner',
            'base' => 'gremaza_hero_banner',
            'category' => 'By Gremaza',
            'description' => 'Create attractive hero banners with title, description, button and image',
            'icon' => 'icon-wpb-ui-separator',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'as_parent' => array('except' => ''),
            'params' => array(
                // Layout Style
                array(
                    'type' => 'dropdown',
                    'heading' => __('Layout Style', 'gremaza-wpb-addons'),
                    'param_name' => 'layout_style',
                    'value' => array(
                        __('Style 1: Text Left, Image Right', 'gremaza-wpb-addons') => 'style1',
                        __('Style 2: Image Left, Text Right', 'gremaza-wpb-addons') => 'style2',
                    ),
                    'std' => 'style1',
                    'description' => __('Choose the layout style for desktop view', 'gremaza-wpb-addons'),
                ),
                
                // Title
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'title',
                    'value' => '',
                    'description' => __('Enter the hero banner title', 'gremaza-wpb-addons'),
                    'admin_label' => true,
                ),
                
                // Title HTML Tag
                array(
                    'type' => 'dropdown',
                    'heading' => __('Title HTML Tag', 'gremaza-wpb-addons'),
                    'param_name' => 'title_tag',
                    'value' => array(
                        'H1' => 'h1',
                        'H2' => 'h2',
                        'H3' => 'h3',
                        'H4' => 'h4',
                        'H5' => 'h5',
                        'H6' => 'h6',
                    ),
                    'std' => 'h1',
                    'description' => __('Select HTML tag for the title', 'gremaza-wpb-addons'),
                ),
                
                // Description
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'description',
                    'value' => '',
                    'description' => __('Enter the hero banner description', 'gremaza-wpb-addons'),
                ),
                
                // Button Text
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'button_text',
                    'value' => '',
                    'description' => __('Enter the call-to-action button text', 'gremaza-wpb-addons'),
                ),
                
                // Button Link
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                ),
                
                // Media Type Selection
                array(
                    'type' => 'dropdown',
                    'heading' => __('Media Type', 'gremaza-wpb-addons'),
                    'param_name' => 'media_type',
                    'value' => array(
                        __('Image', 'gremaza-wpb-addons') => 'image',
                        __('YouTube Video', 'gremaza-wpb-addons') => 'youtube',
                    ),
                    'std' => 'image',
                    'description' => __('Choose between image or YouTube video', 'gremaza-wpb-addons'),
                ),
                
                // YouTube Video URL
                array(
                    'type' => 'textfield',
                    'heading' => __('YouTube Video URL', 'gremaza-wpb-addons'),
                    'param_name' => 'youtube_url',
                    'value' => '',
                    'description' => __('Enter YouTube video URL (e.g., https://www.youtube.com/watch?v=VIDEO_ID)', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'media_type',
                        'value' => 'youtube',
                    ),
                ),
                
                // Video Cover Image
                array(
                    'type' => 'attach_image',
                    'heading' => __('Video Cover Image', 'gremaza-wpb-addons'),
                    'param_name' => 'video_cover_image',
                    'value' => '',
                    'description' => __('Upload a cover image that will show before the video plays', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'media_type',
                        'value' => 'youtube',
                    ),
                ),
                
                // Image
                array(
                    'type' => 'attach_image',
                    'heading' => __('Hero Image', 'gremaza-wpb-addons'),
                    'param_name' => 'hero_image',
                    'value' => '',
                    'description' => __('Select hero banner image', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'media_type',
                        'value' => 'image',
                    ),
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
                        __('Thumbnail', 'gremaza-wpb-addons') => 'thumbnail',
                    ),
                    'std' => 'large',
                    'description' => __('Select image size', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'media_type',
                        'value' => 'image',
                    ),
                ),
                
                // Image Cover Option
                array(
                    'type' => 'checkbox',
                    'heading' => __('Full Column Cover Image', 'gremaza-wpb-addons'),
                    'param_name' => 'image_cover',
                    'value' => array(__('Yes', 'gremaza-wpb-addons') => 'yes'),
                    'description' => __('Make image cover the entire column without spacing', 'gremaza-wpb-addons'),
                ),
                
                // Hero Height Control
                array(
                    'type' => 'textfield',
                    'heading' => __('Hero Height', 'gremaza-wpb-addons'),
                    'param_name' => 'hero_height',
                    'value' => '500px',
                    'description' => __('Enter height (e.g., 500px, 60vh, 400px)', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'image_cover',
                        'value' => 'yes',
                    ),
                ),
                
                // Design Tab
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Title Color', 'gremaza-wpb-addons'),
                    'param_name' => 'title_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Title Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'title_font_size',
                    'value' => '48px',
                    'description' => __('Enter font size (e.g., 48px, 3rem, 2.5em)', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Description Color', 'gremaza-wpb-addons'),
                    'param_name' => 'description_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Description Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'description_font_size',
                    'value' => '18px',
                    'description' => __('Enter font size (e.g., 18px, 1.2rem, 1.1em)', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'button_bg_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Text Color', 'gremaza-wpb-addons'),
                    'param_name' => 'button_text_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'button_font_size',
                    'value' => '16px',
                    'description' => __('Enter font size (e.g., 16px, 1rem, 0.9em)', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                    'description' => __('Add extra CSS class for additional styling', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
            ),
        ));
    }
    
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'layout_style' => 'style1',
            'title' => '',
            'title_tag' => 'h1',
            'description' => '',
            'button_text' => '',
            'button_link' => '',
            'media_type' => 'image',
            'youtube_url' => '',
            'video_cover_image' => '',
            'hero_image' => '',
            'image_size' => 'large',
            'image_cover' => '',
            'hero_height' => '500px',
            'title_color' => '',
            'title_font_size' => '48px',
            'description_color' => '',
            'description_font_size' => '18px',
            'button_bg_color' => '',
            'button_text_color' => '',
            'button_font_size' => '16px',
            'extra_class' => '',
        ), $atts);
        
        // Parse button link
        $button_link_data = vc_build_link($atts['button_link']);
        $button_url = isset($button_link_data['url']) ? $button_link_data['url'] : '#';
        $button_target = isset($button_link_data['target']) ? $button_link_data['target'] : '_self';
        $button_title = isset($button_link_data['title']) ? $button_link_data['title'] : $atts['button_text'];
        
        // Get media content based on type
        $media_html = '';
        if ($atts['media_type'] === 'youtube' && !empty($atts['youtube_url'])) {
            // Extract YouTube video ID
            $video_id = $this->extract_youtube_id($atts['youtube_url']);
            if ($video_id) {
                $media_html = $this->generate_youtube_embed($video_id, $atts);
            }
        } elseif ($atts['media_type'] === 'image' && !empty($atts['hero_image'])) {
            // Get image
            $image_classes = array('class' => 'gremaza-hero-image');
            
            // Apply custom height for cover images
            if ($atts['image_cover'] === 'yes' && !empty($atts['hero_height'])) {
                $image_classes['style'] = 'height: ' . esc_attr($atts['hero_height']) . ' !important;';
            }
            
            $media_html = wp_get_attachment_image($atts['hero_image'], $atts['image_size'], false, $image_classes);
            
            // Debug: If no image HTML is generated, try to get the image URL directly
            if (empty($media_html)) {
                $image_url = wp_get_attachment_image_url($atts['hero_image'], $atts['image_size']);
                if ($image_url) {
                    $style_attr = '';
                    if ($atts['image_cover'] === 'yes' && !empty($atts['hero_height'])) {
                        $style_attr = ' style="height: ' . esc_attr($atts['hero_height']) . ' !important;"';
                    }
                    $media_html = '<img src="' . esc_url($image_url) . '" alt="" class="gremaza-hero-image"' . $style_attr . ' />';
                }
            }
        }
        
        // Build inline styles
        $title_style = '';
        $title_styles = array();
        if (!empty($atts['title_color'])) {
            $title_styles[] = 'color: ' . esc_attr($atts['title_color']);
        }
        if (!empty($atts['title_font_size'])) {
            $title_styles[] = 'font-size: ' . esc_attr($atts['title_font_size']);
        }
        if (!empty($title_styles)) {
            $title_style = 'style="' . implode('; ', $title_styles) . ';"';
        }
        
        $description_style = '';
        $description_styles = array();
        if (!empty($atts['description_color'])) {
            $description_styles[] = 'color: ' . esc_attr($atts['description_color']);
        }
        
        // Add font size if provided
        if (!empty($atts['description_font_size'])) {
            $description_styles[] = 'font-size: ' . esc_attr($atts['description_font_size']);
        }
        
        if (!empty($description_styles)) {
            $description_style = 'style="' . implode('; ', $description_styles) . ';"';
        }
        
        $button_style = '';
        $button_styles = array();
        if (!empty($atts['button_bg_color'])) {
            $button_styles[] = 'background-color: ' . esc_attr($atts['button_bg_color']);
        }
        if (!empty($atts['button_text_color'])) {
            $button_styles[] = 'color: ' . esc_attr($atts['button_text_color']);
        }
        if (!empty($atts['button_font_size'])) {
            $button_styles[] = 'font-size: ' . esc_attr($atts['button_font_size']);
        }
        if (!empty($button_styles)) {
            $button_style = 'style="' . implode('; ', $button_styles) . ';"';
        }
        
        // CSS classes
        $css_class = 'gremaza-hero-banner gremaza-hero-' . esc_attr($atts['layout_style']);
        if (!empty($atts['extra_class'])) {
            $css_class .= ' ' . esc_attr($atts['extra_class']);
        }
        if ($atts['image_cover'] === 'yes') {
            $css_class .= ' gremaza-hero-cover-image';
        }
        
        // Build container styles for cover image height
        $container_style = '';
        if ($atts['image_cover'] === 'yes' && !empty($atts['hero_height'])) {
            $container_style = 'style="min-height: ' . esc_attr($atts['hero_height']) . ';"';
        }
        
        // Build output
        ob_start();
        ?>
        <div class="<?php echo esc_attr($css_class); ?>">
            <div class="gremaza-hero-container" <?php echo $container_style; ?>>
                <div class="gremaza-hero-content">
                    <?php if (!empty($atts['title'])): ?>
                        <<?php echo esc_attr($atts['title_tag']); ?> class="gremaza-hero-title" <?php echo $title_style; ?>>
                            <?php echo esc_html($atts['title']); ?>
                        </<?php echo esc_attr($atts['title_tag']); ?>>
                    <?php endif; ?>
                    
                    <?php if (!empty($atts['description'])): ?>
                        <div class="gremaza-hero-description" <?php echo $description_style; ?>>
                            <?php echo wp_kses_post(wpautop($atts['description'])); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($atts['button_text'])): ?>
                        <div class="gremaza-hero-button-wrapper">
                            <a href="<?php echo esc_url($button_url); ?>" 
                               target="<?php echo esc_attr($button_target); ?>" 
                               class="gremaza-hero-button" 
                               <?php echo $button_style; ?>>
                                <?php echo esc_html($button_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($media_html)): ?>
                    <div class="gremaza-hero-media-wrapper <?php echo $atts['media_type'] === 'youtube' ? 'gremaza-hero-video-wrapper' : 'gremaza-hero-image-wrapper'; ?>">
                        <?php echo $media_html; ?>
                    </div>
                <?php else: ?>
                    <!-- Debug: No media found. Media Type: <?php echo esc_html($atts['media_type']); ?> -->
                    <?php if ($atts['media_type'] === 'image' && !empty($atts['hero_image'])): ?>
                        <div class="gremaza-hero-image-wrapper">
                            <div style="background: #f0f0f0; padding: 20px; text-align: center; color: #666;">
                                Image ID: <?php echo esc_html($atts['hero_image']); ?> - Image not found or failed to load
                            </div>
                        </div>
                    <?php elseif ($atts['media_type'] === 'youtube' && !empty($atts['youtube_url'])): ?>
                        <div class="gremaza-hero-video-wrapper">
                            <div style="background: #f0f0f0; padding: 20px; text-align: center; color: #666;">
                                YouTube URL: <?php echo esc_html($atts['youtube_url']); ?> - Invalid URL or failed to load
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Extract YouTube video ID from various YouTube URL formats
     */
    private function extract_youtube_id($url) {
        $pattern = '#(?:https?://)?(?:www\.)?(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})#i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return false;
    }
    
    /**
     * Generate YouTube embed HTML
     */
    private function generate_youtube_embed($video_id, $atts) {
        $height = $atts['image_cover'] === 'yes' && !empty($atts['hero_height']) ? $atts['hero_height'] : '500px';
        
        // Check if we have a cover image
        $cover_image_url = '';
        if (!empty($atts['video_cover_image'])) {
            $cover_image_url = wp_get_attachment_image_url($atts['video_cover_image'], 'large');
        }
        
        $embed_html = '<div class="gremaza-hero-video-container" style="position: relative; height: ' . esc_attr($height) . ';">';
        
        if ($cover_image_url) {
            // Add cover image with play button
            $embed_html .= '<div class="gremaza-video-cover" style="background-image: url(' . esc_url($cover_image_url) . ');">';
            $embed_html .= '<div class="gremaza-play-button"></div>';
            $embed_html .= '</div>';
        }
        
        $embed_html .= '<iframe class="gremaza-hero-video" ';
        $embed_html .= 'src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?autoplay=0&mute=0&loop=0&controls=1&showinfo=0&rel=0" ';
        $embed_html .= 'style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;' . ($cover_image_url ? ' display: none;' : '') . '" ';
        $embed_html .= 'frameborder="0" ';
        $embed_html .= 'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ';
        $embed_html .= 'allowfullscreen>';
        $embed_html .= '</iframe>';
        $embed_html .= '</div>';
        
        return $embed_html;
    }
}

// Don't initialize here - let the main plugin do it