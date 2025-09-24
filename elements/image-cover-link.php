<?php
/**
 * Image Cover Link Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaImageCoverLink {

    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_image_cover_link', array($this, 'render_shortcode'));
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
            'name' => __('Image Cover Link', 'gremaza-wpb-addons'),
            'base' => 'gremaza_image_cover_link',
            'category' => 'By Gremaza',
            'description' => __('Background image with centered text link and hover zoom/darken', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-images-stack',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                array(
                    'type' => 'attach_image',
                    'heading' => __('Background Image', 'gremaza-wpb-addons'),
                    'param_name' => 'image_id',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Link Text', 'gremaza-wpb-addons'),
                    'param_name' => 'link_text',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Link', 'gremaza-wpb-addons'),
                    'param_name' => 'link',
                ),

                // Sizing
                array(
                    'type' => 'textfield',
                    'heading' => __('Height', 'gremaza-wpb-addons'),
                    'param_name' => 'height',
                    'description' => __('e.g. 300px, 50vh', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Border Radius', 'gremaza-wpb-addons'),
                    'param_name' => 'border_radius',
                    'value' => array(
                        __('None', 'gremaza-wpb-addons') => '',
                        '4px' => '4px',
                        '6px' => '6px',
                        '8px' => '8px',
                        '12px' => '12px',
                        '20px' => '20px',
                    ),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),

                // Text styles
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Text Color', 'gremaza-wpb-addons'),
                    'param_name' => 'text_color',
                    'std' => '#ffffff',
                    'group' => __('Text', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Font Family', 'gremaza-wpb-addons'),
                    'param_name' => 'font_family',
                    'description' => __('e.g. "Poppins", Arial, sans-serif', 'gremaza-wpb-addons'),
                    'group' => __('Text', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'font_size',
                    'description' => __('e.g. 18px, 1.2rem', 'gremaza-wpb-addons'),
                    'group' => __('Text', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Font Weight', 'gremaza-wpb-addons'),
                    'param_name' => 'font_weight',
                    'value' => array(
                        __('Default', 'gremaza-wpb-addons') => '',
                        '300' => '300',
                        '400' => '400',
                        '500' => '500',
                        '600' => '600',
                        '700' => '700',
                        '800' => '800',
                    ),
                    'group' => __('Text', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Text Transform', 'gremaza-wpb-addons'),
                    'param_name' => 'text_transform',
                    'value' => array(
                        __('None', 'gremaza-wpb-addons') => '',
                        __('Uppercase', 'gremaza-wpb-addons') => 'uppercase',
                        __('Lowercase', 'gremaza-wpb-addons') => 'lowercase',
                        __('Capitalize', 'gremaza-wpb-addons') => 'capitalize',
                    ),
                    'group' => __('Text', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Letter Spacing', 'gremaza-wpb-addons'),
                    'param_name' => 'letter_spacing',
                    'description' => __('e.g. 0.5px', 'gremaza-wpb-addons'),
                    'group' => __('Text', 'gremaza-wpb-addons'),
                ),

                // Hover effect options
                array(
                    'type' => 'textfield',
                    'heading' => __('Hover Zoom Scale', 'gremaza-wpb-addons'),
                    'param_name' => 'zoom_scale',
                    'description' => __('e.g. 1.1 for 10% zoom (default 1.08)', 'gremaza-wpb-addons'),
                    'group' => __('Hover', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Overlay Darkness (0-1)', 'gremaza-wpb-addons'),
                    'param_name' => 'overlay_darkness',
                    'description' => __('0 = transparent, 1 = black. Default 0.35, hover 0.55', 'gremaza-wpb-addons'),
                    'group' => __('Hover', 'gremaza-wpb-addons'),
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
            'link_text' => '',
            'link' => '',
            'height' => '360px',
            'border_radius' => '',
            'text_color' => '#ffffff',
            'font_family' => '',
            'font_size' => '22px',
            'font_weight' => '',
            'text_transform' => '',
            'letter_spacing' => '',
            'zoom_scale' => '1.08',
            'overlay_darkness' => '0.35',
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
        $href = !empty($link_atts['url']) ? esc_url($link_atts['url']) : '#';
        $title = !empty($link_atts['title']) ? esc_attr($link_atts['title']) : esc_attr($atts['link_text']);
        $target = !empty($link_atts['target']) ? esc_attr($link_atts['target']) : '';
        $rel = !empty($link_atts['rel']) ? esc_attr($link_atts['rel']) : '';

        // Sanitize numeric/unit values
        $sanitize_size = function($v){
            $v = trim((string)$v);
            if ($v === '') return '';
            if (preg_match('/^\\d+(?:\\.\\d+)?$/', $v)) { $v .= 'px'; }
            return preg_match('/^\\d+(?:\\.\\d+)?(px|em|rem|%|vh|vw)$/', $v) ? $v : '';
        };

        $height = $sanitize_size($atts['height']);
        if (!$height) { $height = '360px'; }

        $font_size = $sanitize_size($atts['font_size']);
        $font_weight = in_array($atts['font_weight'], array('','300','400','500','600','700','800'), true) ? $atts['font_weight'] : '';
        $text_transform = in_array($atts['text_transform'], array('','uppercase','lowercase','capitalize'), true) ? $atts['text_transform'] : '';
        $letter_spacing = $sanitize_size($atts['letter_spacing']);
        $font_family = trim((string)$atts['font_family']);
        // Light sanitization: allow letters, digits, spaces, commas, quotes, dashes
        if ($font_family && !preg_match('/^[A-Za-z0-9 ,"\'\'\-]+$/', $font_family)) {
            $font_family = '';
        }

        $zoom_scale = is_numeric($atts['zoom_scale']) ? $atts['zoom_scale'] : '1.08';
        $overlay_darkness = (is_numeric($atts['overlay_darkness']) && $atts['overlay_darkness'] >= 0 && $atts['overlay_darkness'] <= 1) ? $atts['overlay_darkness'] : '0.35';

        // Build style attribute via CSS variables for flexibility
        $wrapper_styles = array();
        if ($img_url) {
            $wrapper_styles[] = '--gremaza-bg:url(' . esc_url($img_url) . ')';
        }
        $wrapper_styles[] = '--gremaza-height:' . esc_attr($height);
        $wrapper_styles[] = '--gremaza-zoom:' . esc_attr($zoom_scale);
        $wrapper_styles[] = '--gremaza-overlay:' . esc_attr($overlay_darkness);
        if (!empty($atts['border_radius'])) {
            $wrapper_styles[] = 'border-radius:' . esc_attr($atts['border_radius']);
        }
        $wrapper_style_attr = ' style="' . implode(';', $wrapper_styles) . '"';

    $text_styles = array('color:' . esc_attr($atts['text_color']));
    if ($font_family) { $text_styles[] = 'font-family:' . esc_attr($font_family); }
        if ($font_size) { $text_styles[] = 'font-size:' . esc_attr($font_size); }
        if ($font_weight) { $text_styles[] = 'font-weight:' . esc_attr($font_weight); }
        if ($text_transform) { $text_styles[] = 'text-transform:' . esc_attr($text_transform); }
        if ($letter_spacing) { $text_styles[] = 'letter-spacing:' . esc_attr($letter_spacing); }
        $text_style_attr = ' style="' . implode(';', $text_styles) . '"';

        $classes = array('gremaza-image-cover-link');
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
            <a class="gremaza-image-cover-link__anchor" href="<?php echo $href; ?>"<?php echo $title ? ' title="' . $title . '"' : ''; ?><?php echo $target ? ' target="' . $target . '"' : ''; ?><?php echo $rel ? ' rel="' . $rel . '"' : ''; ?>>
                <span class="gremaza-image-cover-link__label"<?php echo $text_style_attr; ?>><?php echo esc_html($atts['link_text']); ?></span>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Let main plugin initialize the class
