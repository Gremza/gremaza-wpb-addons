<?php
if (!defined('ABSPATH')) {
    exit;
}

class GremazaArticleBox {
    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_article_box', array($this, 'render_shortcode'));
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
            'name' => __('Article Box', 'gremaza-wpb-addons'),
            'base' => 'gremaza_article_box',
            'category' => __('By Gremaza', 'gremaza-wpb-addons'),
            'description' => __('Article box with media on top, title, description, and button.'),
            'icon' => 'icon-wpb-ui-separator',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'as_parent' => array('except' => ''),
            'params' => array(
                // General Tab (no group)
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'title',
                    'value' => '',
                    'description' => __('Enter the article title', 'gremaza-wpb-addons'),
                    'admin_label' => true,
                ),
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
                    'std' => 'h2',
                    'description' => __('Select HTML tag for the title', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'description',
                    'value' => '',
                    'description' => __('Enter the article description', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'button_text',
                    'value' => '',
                    'description' => __('Enter the call-to-action button text', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'button_link',
                    'description' => __('Add link to the button', 'gremaza-wpb-addons'),
                ),
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
                array(
                    'type' => 'attach_image',
                    'heading' => __('Hero Image', 'gremaza-wpb-addons'),
                    'param_name' => 'hero_image',
                    'value' => '',
                    'description' => __('Select article image', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'media_type',
                        'value' => 'image',
                    ),
                ),
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
                // Design Tab (grouped)
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Box Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'box_bg_color',
                    'value' => '#ffffff',
                    'description' => __('Set the background color for the article box', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
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
                    'value' => '32px',
                    'description' => __('Enter font size (e.g., 32px, 2rem, 2em)', 'gremaza-wpb-addons'),
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
                    'value' => '16px',
                    'description' => __('Enter font size (e.g., 16px, 1.2rem, 1.1em)', 'gremaza-wpb-addons'),
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
            'title' => '',
            'title_tag' => 'h2',
            'description' => '',
            'button_text' => '',
            'button_link' => '',
            'media_type' => 'image',
            'youtube_url' => '',
            'hero_image' => '',
            'image_size' => 'large',
            'title_color' => '',
            'title_font_size' => '32px',
            'description_color' => '',
            'description_font_size' => '16px',
            'button_bg_color' => '',
            'button_text_color' => '',
            'button_font_size' => '16px',
            'extra_class' => '',
            'box_bg_color' => '#ffffff',
        ), $atts, 'gremaza_article_box');

        $style_title = '';
        $title_styles = array();
        if (!empty($atts['title_color'])) {
            $title_styles[] = 'color: ' . esc_attr($atts['title_color']);
        }
        if (!empty($atts['title_font_size'])) {
            $title_styles[] = 'font-size: ' . esc_attr($atts['title_font_size']);
        }
        if (!empty($title_styles)) {
            $style_title = 'style="' . implode('; ', $title_styles) . ';"';
        }

        $style_desc = '';
        $desc_styles = array();
        if (!empty($atts['description_color'])) {
            $desc_styles[] = 'color: ' . esc_attr($atts['description_color']);
        }
        if (!empty($atts['description_font_size'])) {
            $desc_styles[] = 'font-size: ' . esc_attr($atts['description_font_size']);
        }
        if (!empty($desc_styles)) {
            $style_desc = 'style="' . implode('; ', $desc_styles) . ';"';
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

        $button_link_data = function_exists('vc_build_link') ? vc_build_link($atts['button_link']) : array();
        $button_url = isset($button_link_data['url']) ? $button_link_data['url'] : '#';
        $button_target = isset($button_link_data['target']) ? $button_link_data['target'] : '_self';
        $button_title = trim($button_link_data['title']);
        if ($button_title === '') {
            $button_title = $atts['button_text'];
        }

        $media_html = '';
        if ($atts['media_type'] === 'youtube' && !empty($atts['youtube_url'])) {
            $video_id = $this->extract_youtube_id($atts['youtube_url']);
            if ($video_id) {
                $media_html = '<div class="gremaza-article-media gremaza-article-video"><iframe src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?autoplay=0&mute=0&loop=0&controls=1&showinfo=0&rel=0" style="width:100%;height:220px;border:none;" frameborder="0" allowfullscreen></iframe></div>';
            }
        } elseif ($atts['media_type'] === 'image' && !empty($atts['hero_image'])) {
            $media_html = '<div class="gremaza-article-media gremaza-article-image">' . wp_get_attachment_image($atts['hero_image'], $atts['image_size'], false, array('class' => 'gremaza-article-img')) . '</div>';
        }

        $css_class = 'gremaza-article-box';
        if (!empty($atts['extra_class'])) {
            $css_class .= ' ' . esc_attr($atts['extra_class']);
        }

        $box_style = '';
        if (!empty($atts['box_bg_color'])) {
            $box_style = ' style="background:' . esc_attr($atts['box_bg_color']) . ';"';
        }

        ob_start();
        ?>
        <div class="<?php echo esc_attr($css_class); ?>"<?php echo $box_style; ?>>
            <?php echo $media_html; ?>
            <div class="gremaza-article-content">
                <?php if (!empty($atts['title'])): ?>
                    <<?php echo esc_attr($atts['title_tag']); ?> class="gremaza-article-title" <?php echo $style_title; ?>>
                        <?php echo esc_html($atts['title']); ?>
                    </<?php echo esc_attr($atts['title_tag']); ?>>
                <?php endif; ?>
                <?php if (!empty($atts['description'])): ?>
                    <div class="gremaza-article-description" <?php echo $style_desc; ?>>
                        <?php echo wp_kses_post(wpautop($atts['description'])); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($atts['button_text'])): ?>
                    <div class="gremaza-article-button-wrapper">
                        <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>" class="gremaza-article-button" <?php echo $button_style; ?>>
                            <?php echo esc_html($button_title); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function extract_youtube_id($url) {
        $pattern = '#(?:https?://)?(?:www\.)?(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})#i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return false;
    }
}

new GremazaArticleBox();
