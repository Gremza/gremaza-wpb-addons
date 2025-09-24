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
            'description' => __('Article box with cover image on top, then title, description, and button.'),
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
                    'type' => 'attach_image',
                    'heading' => __('Hero Image', 'gremaza-wpb-addons'),
                    'param_name' => 'hero_image',
                    'value' => '',
                    'description' => __('Select article image', 'gremaza-wpb-addons'),
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
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Image Height', 'gremaza-wpb-addons'),
                    'param_name' => 'cover_height',
                    'value' => '360px',
                    'description' => __('Height for the image (e.g., 360px, 50vh)', 'gremaza-wpb-addons'),
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
                    'type' => 'dropdown',
                    'heading' => __('Text Align', 'gremaza-wpb-addons'),
                    'param_name' => 'text_align',
                    'value' => array(
                        __('Left', 'gremaza-wpb-addons') => 'left',
                        __('Center', 'gremaza-wpb-addons') => 'center',
                        __('Right', 'gremaza-wpb-addons') => 'right',
                    ),
                    'std' => 'left',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Button Align', 'gremaza-wpb-addons'),
                    'param_name' => 'button_align',
                    'value' => array(
                        __('Left', 'gremaza-wpb-addons') => 'left',
                        __('Center', 'gremaza-wpb-addons') => 'center',
                        __('Right', 'gremaza-wpb-addons') => 'right',
                    ),
                    'std' => 'center',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Top Margin', 'gremaza-wpb-addons'),
                    'param_name' => 'button_margin_top',
                    'value' => '30px',
                    'description' => __('Space above the button (e.g., 30px)', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Border Radius', 'gremaza-wpb-addons'),
                    'param_name' => 'button_border_radius',
                    'description' => __('e.g., 6px, 9999px', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Width', 'gremaza-wpb-addons'),
                    'param_name' => 'button_width',
                    'description' => __('e.g., auto, 100%, 220px', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Padding', 'gremaza-wpb-addons'),
                    'param_name' => 'button_padding',
                    'description' => __('e.g., 12px 24px', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Hover Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'button_hover_bg_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Hover Text Color', 'gremaza-wpb-addons'),
                    'param_name' => 'button_hover_text_color',
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
            'hero_image' => '',
            'image_size' => 'large',
            'cover_height' => '360px',
            'title_color' => '',
            'title_font_size' => '32px',
            'description_color' => '',
            'description_font_size' => '16px',
            'button_bg_color' => '',
            'button_text_color' => '',
            'text_align' => 'left',
            'button_align' => 'center',
            'button_margin_top' => '30px',
            'button_border_radius' => '',
            'button_width' => '',
            'button_padding' => '',
            'button_hover_bg_color' => '',
            'button_hover_text_color' => '',
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
        if (!empty($atts['button_border_radius'])) {
            $button_styles[] = 'border-radius: ' . esc_attr($atts['button_border_radius']);
        }
        if (!empty($atts['button_width'])) {
            $button_styles[] = 'width: ' . esc_attr($atts['button_width']);
            $button_styles[] = 'display: inline-flex';
        }
        if (!empty($atts['button_padding'])) {
            $button_styles[] = 'padding: ' . esc_attr($atts['button_padding']);
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
        if (!empty($atts['hero_image'])) {
            // Sanitize height
            $img_h = trim((string)$atts['cover_height']);
            if (preg_match('/^\d+(?:\.\d+)?$/', $img_h)) { $img_h .= 'px'; }
            if (!preg_match('/^\d+(?:\.\d+)?(px|vh|vw|em|rem|%)$/', $img_h)) { $img_h = '360px'; }
            $img_tag = wp_get_attachment_image($atts['hero_image'], $atts['image_size'], false, array('class' => 'gremaza-article-cover-img'));
            $media_html = '<div class="gremaza-article-media gremaza-article-cover" style="height:' . esc_attr($img_h) . ';">' . $img_tag . '</div>';
        }

    $css_class = 'gremaza-article-box';
        if (!empty($atts['extra_class'])) {
            $css_class .= ' ' . esc_attr($atts['extra_class']);
        }

        // Build content styles (merge background + text align)
        $content_styles = array();
        if (!empty($atts['box_bg_color'])) {
            $content_styles[] = 'background:' . esc_attr($atts['box_bg_color']);
        }
        if (in_array($atts['text_align'], array('left','center','right'), true)) {
            $content_styles[] = 'text-align:' . esc_attr($atts['text_align']);
        }
        $content_style_attr = !empty($content_styles) ? ' style="' . implode('; ', $content_styles) . ';"' : '';

        // Unique class for per-instance hover styles
        $unique_class = 'gza-article-' . wp_rand(1000, 999999);
        $hover_css = '';
        if (!empty($atts['button_hover_bg_color']) || !empty($atts['button_hover_text_color'])) {
            $hover_css .= '.' . $unique_class . ' .gremaza-article-button:hover{';
            if (!empty($atts['button_hover_bg_color'])) { $hover_css .= 'background-color:' . esc_attr($atts['button_hover_bg_color']) . ';'; }
            if (!empty($atts['button_hover_text_color'])) { $hover_css .= 'color:' . esc_attr($atts['button_hover_text_color']) . ';'; }
            $hover_css .= 'transform: translateY(-2px);box-shadow: 0 6px 18px rgba(0,0,0,0.15);}';
        } else {
            // Default subtle hover
            $hover_css .= '.' . $unique_class . ' .gremaza-article-button:hover{transform: translateY(-2px);box-shadow: 0 6px 18px rgba(0,0,0,0.15);}';
        }

        ob_start();
        ?>
        <div class="<?php echo esc_attr($css_class . ' ' . $unique_class); ?>">
            <style type="text/css">.<?php echo $unique_class; ?> .gremaza-article-button{transition: background-color .2s ease,color .2s ease,transform .2s ease,box-shadow .2s ease;}<?php echo $hover_css; ?></style>
            <?php echo $media_html; ?>
            <div class="gremaza-article-content"<?php echo $content_style_attr; ?>>
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
                    <?php
                        // Button wrapper alignment and spacing
                        $btn_mt = trim((string)$atts['button_margin_top']);
                        if (preg_match('/^\d+(?:\.\d+)?$/', $btn_mt)) { $btn_mt .= 'px'; }
                        $btn_mt = preg_match('/^\d+(?:\.\d+)?(px|em|rem|%)$/', $btn_mt) ? $btn_mt : '30px';
                        $justify = 'center';
                        if ($atts['button_align'] === 'left') { $justify = 'flex-start'; }
                        elseif ($atts['button_align'] === 'right') { $justify = 'flex-end'; }
                        $button_wrapper_style = 'display:flex;justify-content:' . $justify . ';margin-top:' . esc_attr($btn_mt) . ';width:100%;';
                    ?>
                    <div class="gremaza-article-button-wrapper" style="<?php echo esc_attr($button_wrapper_style); ?>">
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
