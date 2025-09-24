<?php
/**
 * Read More Overlay Element for WPBakery Page Builder
 */

if (!defined('ABSPATH')) { exit; }

class GremazaReadMore {
    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_read_more', array($this, 'render_shortcode'));
    }

    public function map_shortcode() {
        if (!function_exists('vc_map')) {
            return;
        }
        static $registered = false;
        if ($registered) { return; }
        $registered = true;

        vc_map(array(
            'name' => __('Read More Overlay', 'gremaza-wpb-addons'),
            'base' => 'gremaza_read_more',
            'category' => 'By Gremaza',
            'description' => __('Full-width overlay that fades content and shows a centered button/link', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-ui-button',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __('Overlay Height', 'gremaza-wpb-addons'),
                    'param_name' => 'height',
                    'description' => __('Height of the fading overlay, e.g. 120px', 'gremaza-wpb-addons'),
                    'std' => '120px',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Overlap Amount (negative top margin)', 'gremaza-wpb-addons'),
                    'param_name' => 'overlap',
                    'description' => __('Amount to overlap previous content, e.g. 80px (used as negative margin-top).', 'gremaza-wpb-addons'),
                    'std' => '80px',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Fade To Color', 'gremaza-wpb-addons'),
                    'param_name' => 'fade_color',
                    'description' => __('Bottom color of fade (usually the page background).', 'gremaza-wpb-addons'),
                    'std' => '#ffffff',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Fade Start Opacity (0-1)', 'gremaza-wpb-addons'),
                    'param_name' => 'fade_start_opacity',
                    'description' => __('Transparency at the top of the overlay. 0 = fully transparent.', 'gremaza-wpb-addons'),
                    'std' => '0',
                ),
                // Button options
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Text', 'gremaza-wpb-addons'),
                    'param_name' => 'button_text',
                    'std' => __('Read More', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __('Button Link', 'gremaza-wpb-addons'),
                    'param_name' => 'button_link',
                    'description' => __('Choose where the Read More button should go.', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Background', 'gremaza-wpb-addons'),
                    'param_name' => 'button_bg',
                    'std' => '#007cba',
                    'group' => __('Button', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Button Text Color', 'gremaza-wpb-addons'),
                    'param_name' => 'button_color',
                    'std' => '#ffffff',
                    'group' => __('Button', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Font Size', 'gremaza-wpb-addons'),
                    'param_name' => 'button_font_size',
                    'description' => __('e.g. 16px', 'gremaza-wpb-addons'),
                    'group' => __('Button', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Padding', 'gremaza-wpb-addons'),
                    'param_name' => 'button_padding',
                    'description' => __('e.g. 12px 24px', 'gremaza-wpb-addons'),
                    'group' => __('Button', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Button Border Radius', 'gremaza-wpb-addons'),
                    'param_name' => 'button_radius',
                    'description' => __('e.g. 6px', 'gremaza-wpb-addons'),
                    'group' => __('Button', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                ),
            ),
        ));
    }

    private function sanitize_size($v) {
        $v = trim((string)$v);
        $v = preg_replace('/\s+/', '', $v);
        if ($v === '') return '';
        if (preg_match('/^\d+(?:\.\d+)?$/', $v)) { $v .= 'px'; }
        return preg_match('/^\d+(?:\.\d+)?(px|em|rem|%|vh|vw)$/', $v) ? $v : '';
    }

    private function hex_to_rgb($hex) {
        $hex = trim((string)$hex);
        if (substr($hex, 0, 1) === '#') { $hex = substr($hex, 1); }
        if (strlen($hex) === 3) {
            $r = hexdec(str_repeat(substr($hex,0,1),2));
            $g = hexdec(str_repeat(substr($hex,1,1),2));
            $b = hexdec(str_repeat(substr($hex,2,1),2));
            return array($r,$g,$b);
        }
        if (strlen($hex) === 6) {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
            return array($r,$g,$b);
        }
        return array(255,255,255);
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'height' => '120px',
            'overlap' => '80px',
            'fade_color' => '#ffffff',
            'fade_start_opacity' => '0',
            'button_text' => __('Read More', 'gremaza-wpb-addons'),
            'button_link' => '',
            'button_bg' => '#007cba',
            'button_color' => '#ffffff',
            'button_font_size' => '',
            'button_padding' => '',
            'button_radius' => '',
            'extra_class' => '',
        ), $atts);

        $height = $this->sanitize_size($atts['height']);
        if (!$height) { $height = '120px'; }
        $overlap = $this->sanitize_size($atts['overlap']);
        if (!$overlap) { $overlap = '80px'; }
        $fade_color = $atts['fade_color'] ?: '#ffffff';
        list($fr,$fg,$fb) = $this->hex_to_rgb($fade_color);
        $fade_opacity = is_numeric($atts['fade_start_opacity']) ? max(0,min(1, floatval($atts['fade_start_opacity']))) : 0;

        // Button link parsing
        $url = '';$target='';$rel='';
        if (!empty($atts['button_link'])) {
            if (function_exists('vc_build_link')) {
                $link = vc_build_link($atts['button_link']);
                if (is_array($link)) {
                    $url = isset($link['url']) ? $link['url'] : '';
                    $target = isset($link['target']) ? $link['target'] : '';
                    $rel = isset($link['rel']) ? $link['rel'] : '';
                }
            } else {
                $url = $atts['button_link'];
            }
        }

        $button_styles = array();
        if (!empty($atts['button_bg'])) { $button_styles[] = 'background-color: ' . esc_attr($atts['button_bg']); }
        if (!empty($atts['button_color'])) { $button_styles[] = 'color: ' . esc_attr($atts['button_color']); }
        $fs = $this->sanitize_size($atts['button_font_size']); if ($fs) { $button_styles[] = 'font-size: ' . esc_attr($fs); }
        if (!empty($atts['button_padding'])) { $button_styles[] = 'padding: ' . esc_attr($atts['button_padding']); }
        if (!empty($atts['button_radius'])) { $button_styles[] = 'border-radius: ' . esc_attr($atts['button_radius']); }
        $button_style_attr = !empty($button_styles) ? ' style="' . implode('; ', $button_styles) . '"' : '';

        $classes = array('gremaza-readmore-overlay');
        if (!empty($atts['extra_class'])) {
            $extra_parts = preg_split('/\s+/', $atts['extra_class']);
            foreach ($extra_parts as $part) {
                $san = sanitize_html_class($part); if ($san) { $classes[] = $san; }
            }
        }
        $class_attr = implode(' ', array_map('esc_attr', $classes));

        // Inline styles for overlay wrapper
        $wrapper_styles = array();
        $wrapper_styles[] = 'height: ' . esc_attr($height);
        $wrapper_styles[] = 'margin-top: -' . esc_attr($overlap);
        $wrapper_styles[] = 'position: relative';
        $wrapper_styles[] = 'width: 100%';
        $wrapper_styles[] = 'display: flex';
        $wrapper_styles[] = 'align-items: center';
        $wrapper_styles[] = 'justify-content: center';
        // gradient fading overlay background
        $wrapper_styles[] = 'background: linear-gradient(to bottom, rgba(' . intval($fr) . ',' . intval($fg) . ',' . intval($fb) . ',' . $fade_opacity . ') 0%, rgba(' . intval($fr) . ',' . intval($fg) . ',' . intval($fb) . ',1) 100%)';
        $wrapper_style_attr = ' style="' . implode('; ', $wrapper_styles) . '"';

        ob_start();
        ?>
        <div class="<?php echo $class_attr; ?>"<?php echo $wrapper_style_attr; ?>>
            <?php if (!empty($url)): ?>
                <a class="gremaza-readmore-button" href="<?php echo esc_url($url); ?>" <?php echo $target ? 'target="' . esc_attr($target) . '"' : ''; ?> <?php echo $rel ? 'rel="' . esc_attr($rel) . '"' : ''; ?><?php echo $button_style_attr; ?>><?php echo esc_html($atts['button_text']); ?></a>
            <?php else: ?>
                <span class="gremaza-readmore-button"<?php echo $button_style_attr; ?>><?php echo esc_html($atts['button_text']); ?></span>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
