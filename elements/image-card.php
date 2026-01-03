<?php
/**
 * Image Card Element for WPBakery Page Builder
 * Background image with title, description, and full clickable area
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaImageCard {

    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_image_card', array($this, 'render_shortcode'));
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
            'name' => __('Image Card', 'gremaza-wpb-addons'),
            'base' => 'gremaza_image_card',
            'category' => 'By Gremaza',
            'description' => __('Image background with title, description and clickable link', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-images-stack',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                // Content
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'image_id',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Title', 'gremaza-wpb-addons'),
                    'param_name' => 'title',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __('Description', 'gremaza-wpb-addons'),
                    'param_name' => 'description',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Link', 'gremaza-wpb-addons'),
                    'param_name' => 'link',
                    'description' => __('Clicking anywhere on the card will open this link', 'gremaza-wpb-addons'),
                ),

                // Sizing
                array(
                    'type' => 'textfield',
                    'heading' => __('Height', 'gremaza-wpb-addons'),
                    'param_name' => 'height',
                    'description' => __('e.g. 400px, 50vh', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Content Position', 'gremaza-wpb-addons'),
                    'param_name' => 'content_position',
                    'value' => array(
                        __('Bottom Left', 'gremaza-wpb-addons') => 'bottom-left',
                        __('Bottom Center', 'gremaza-wpb-addons') => 'bottom-center',
                        __('Bottom Right', 'gremaza-wpb-addons') => 'bottom-right',
                        __('Center', 'gremaza-wpb-addons') => 'center',
                        __('Top Left', 'gremaza-wpb-addons') => 'top-left',
                        __('Top Center', 'gremaza-wpb-addons') => 'top-center',
                        __('Top Right', 'gremaza-wpb-addons') => 'top-right',
                    ),
                    'std' => 'bottom-left',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Content Padding', 'gremaza-wpb-addons'),
                    'param_name' => 'content_padding',
                    'description' => __('e.g. 30px, 20px 30px', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),

                // Title styles
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Title Color', 'gremaza-wpb-addons'),
                    'param_name' => 'title_color',
                    'std' => '#ffffff',
                    'group' => __('Title', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Title Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'title_font_size',
                    'description' => __('e.g. 28px, 2rem', 'gremaza-wpb-addons'),
                    'group' => __('Title', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Title Font Weight', 'gremaza-wpb-addons'),
                    'param_name' => 'title_font_weight',
                    'value' => array(
                        __('Default', 'gremaza-wpb-addons') => '',
                        '300' => '300',
                        '400' => '400',
                        '500' => '500',
                        '600' => '600',
                        '700' => '700',
                        '800' => '800',
                    ),
                    'group' => __('Title', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Title Font Family', 'gremaza-wpb-addons'),
                    'param_name' => 'title_font_family',
                    'description' => __('e.g. "Poppins", Arial, sans-serif', 'gremaza-wpb-addons'),
                    'group' => __('Title', 'gremaza-wpb-addons'),
                ),

                // Description styles
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Description Color', 'gremaza-wpb-addons'),
                    'param_name' => 'desc_color',
                    'std' => '#ffffff',
                    'group' => __('Description', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Description Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'desc_font_size',
                    'description' => __('e.g. 16px, 1rem', 'gremaza-wpb-addons'),
                    'group' => __('Description', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Description Font Family', 'gremaza-wpb-addons'),
                    'param_name' => 'desc_font_family',
                    'description' => __('e.g. "Poppins", Arial, sans-serif', 'gremaza-wpb-addons'),
                    'group' => __('Description', 'gremaza-wpb-addons'),
                ),

                // Misc
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                ),
            ),
        ));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'image_id' => '',
            'title' => '',
            'description' => '',
            'link' => '',
            'height' => '400px',
            'content_position' => 'bottom-left',
            'content_padding' => '30px',
            'title_color' => '#ffffff',
            'title_font_size' => '',
            'title_font_weight' => '',
            'title_font_family' => '',
            'desc_color' => '#ffffff',
            'desc_font_size' => '',
            'desc_font_family' => '',
            'extra_class' => '',
        ), $atts);

        // Get image URL
        $img_url = '';
        if (!empty($atts['image_id'])) {
            $img = wp_get_attachment_image_src((int)$atts['image_id'], 'full');
            if ($img) {
                $img_url = $img[0];
            }
        }

        // Parse link
        $link_atts = vc_build_link($atts['link']);
        $href = !empty($link_atts['url']) ? esc_url($link_atts['url']) : '';
        $link_title = !empty($link_atts['title']) ? esc_attr($link_atts['title']) : '';
        $target = !empty($link_atts['target']) ? esc_attr($link_atts['target']) : '';
        $rel = !empty($link_atts['rel']) ? esc_attr($link_atts['rel']) : '';

        // Sanitize size values
        $sanitize_size = function($v) {
            $v = trim((string)$v);
            if ($v === '') return '';
            if (preg_match('/^\\d+(?:\\.\\d+)?$/', $v)) { $v .= 'px'; }
            return preg_match('/^[\\d.]+(?:px|em|rem|%|vh|vw)(?:\\s+[\\d.]+(?:px|em|rem|%|vh|vw))*$/', $v) ? $v : '';
        };

        $sanitize_font = function($v) {
            $v = trim((string)$v);
            if ($v && !preg_match('/^[A-Za-z0-9 ,"\'\'\-]+$/', $v)) {
                return '';
            }
            return $v;
        };

        $height = $sanitize_size($atts['height']);
        if (!$height) { $height = '400px'; }

        $content_padding = $sanitize_size($atts['content_padding']);
        if (!$content_padding) { $content_padding = '30px'; }

        // Build wrapper styles
        $wrapper_styles = array();
        if ($img_url) {
            $wrapper_styles[] = '--gremaza-card-bg:url(' . esc_url($img_url) . ')';
        }
        $wrapper_styles[] = '--gremaza-card-height:' . esc_attr($height);
        $wrapper_style_attr = ' style="' . implode(';', $wrapper_styles) . '"';

        // Build content styles
        $content_styles = array();
        $content_styles[] = 'padding:' . esc_attr($content_padding);
        $content_style_attr = ' style="' . implode(';', $content_styles) . '"';

        // Build title styles
        $title_styles = array();
        $title_styles[] = 'color:' . esc_attr($atts['title_color']);
        if ($atts['title_font_size']) {
            $fs = $sanitize_size($atts['title_font_size']);
            if ($fs) $title_styles[] = 'font-size:' . esc_attr($fs);
        }
        if ($atts['title_font_weight'] && in_array($atts['title_font_weight'], array('300','400','500','600','700','800'), true)) {
            $title_styles[] = 'font-weight:' . esc_attr($atts['title_font_weight']);
        }
        if ($atts['title_font_family']) {
            $ff = $sanitize_font($atts['title_font_family']);
            if ($ff) $title_styles[] = 'font-family:' . esc_attr($ff);
        }
        $title_style_attr = ' style="' . implode(';', $title_styles) . '"';

        // Build description styles
        $desc_styles = array();
        $desc_styles[] = 'color:' . esc_attr($atts['desc_color']);
        if ($atts['desc_font_size']) {
            $fs = $sanitize_size($atts['desc_font_size']);
            if ($fs) $desc_styles[] = 'font-size:' . esc_attr($fs);
        }
        if ($atts['desc_font_family']) {
            $ff = $sanitize_font($atts['desc_font_family']);
            if ($ff) $desc_styles[] = 'font-family:' . esc_attr($ff);
        }
        $desc_style_attr = ' style="' . implode(';', $desc_styles) . '"';

        // Build classes
        $classes = array('gremaza-image-card');
        $classes[] = 'gremaza-image-card--' . sanitize_html_class($atts['content_position']);
        if (!empty($atts['extra_class'])) {
            foreach (preg_split('/\s+/', $atts['extra_class']) as $part) {
                $san = sanitize_html_class($part);
                if ($san) { $classes[] = $san; }
            }
        }
        $class_attr = implode(' ', array_map('esc_attr', $classes));

        ob_start();
        ?>
        <div class="<?php echo $class_attr; ?>"<?php echo $wrapper_style_attr; ?>>
            <?php if ($href) : ?>
            <a class="gremaza-image-card__link" href="<?php echo $href; ?>"<?php echo $link_title ? ' title="' . $link_title . '"' : ''; ?><?php echo $target ? ' target="' . $target . '"' : ''; ?><?php echo $rel ? ' rel="' . $rel . '"' : ''; ?>>
            <?php endif; ?>
                <div class="gremaza-image-card__content"<?php echo $content_style_attr; ?>>
                    <?php if (!empty($atts['title'])) : ?>
                    <h2 class="gremaza-image-card__title"<?php echo $title_style_attr; ?>><?php echo esc_html($atts['title']); ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($atts['description'])) : ?>
                    <p class="gremaza-image-card__desc"<?php echo $desc_style_attr; ?>><?php echo esc_html($atts['description']); ?></p>
                    <?php endif; ?>
                </div>
            <?php if ($href) : ?>
            </a>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
