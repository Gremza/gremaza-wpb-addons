<?php
/**
 * Reviews Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaReviews {

    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_reviews', array($this, 'render_shortcode'));
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
            'name' => __('Reviews', 'gremaza-wpb-addons'),
            'base' => 'gremaza_reviews',
            'category' => 'By Gremaza',
            'description' => __('List of reviews with horizontal scroll or vertical layout', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-quote',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __('Layout', 'gremaza-wpb-addons'),
                    'param_name' => 'layout',
                    'value' => array(
                        __('Horizontal Scroll', 'gremaza-wpb-addons') => 'horizontal',
                        __('Vertical (1 per row)', 'gremaza-wpb-addons') => 'vertical',
                    ),
                    'std' => 'horizontal',
                    'description' => __('Choose how reviews are displayed', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Columns (Horizontal)', 'gremaza-wpb-addons'),
                    'param_name' => 'horizontal_columns',
                    'value' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                    ),
                    'std' => '3',
                    'description' => __('How many review cards should be visible per row in Horizontal layout', 'gremaza-wpb-addons'),
                    'dependency' => array(
                        'element' => 'layout',
                        'value' => array('horizontal'),
                    ),
                ),
                array(
                    'type' => 'param_group',
                    'heading' => __('Reviews', 'gremaza-wpb-addons'),
                    'param_name' => 'reviews',
                    'description' => __('Add as many review items as you need', 'gremaza-wpb-addons'),
                    'params' => array(
                        array(
                            'type' => 'textarea',
                            'heading' => __('Review Text', 'gremaza-wpb-addons'),
                            'param_name' => 'text',
                            'admin_label' => false,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Reviewer Name', 'gremaza-wpb-addons'),
                            'param_name' => 'name',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Reviewer Position', 'gremaza-wpb-addons'),
                            'param_name' => 'position',
                        ),
                        // Per-item design options
                        array(
                            'type' => 'colorpicker',
                            'heading' => __('Card Background', 'gremaza-wpb-addons'),
                            'param_name' => 'item_background_color',
                            'description' => __('Background color for this single review card', 'gremaza-wpb-addons'),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Text Font Size', 'gremaza-wpb-addons'),
                            'param_name' => 'text_size',
                            'description' => __('e.g. 16px, 1rem (leave empty for default)', 'gremaza-wpb-addons'),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Text Weight', 'gremaza-wpb-addons'),
                            'param_name' => 'text_weight',
                            'value' => array(
                                __('Default', 'gremaza-wpb-addons') => '',
                                __('Normal', 'gremaza-wpb-addons') => '400',
                                __('Medium', 'gremaza-wpb-addons') => '500',
                                __('Semi Bold', 'gremaza-wpb-addons') => '600',
                                __('Bold', 'gremaza-wpb-addons') => '700',
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Text Style', 'gremaza-wpb-addons'),
                            'param_name' => 'text_style',
                            'value' => array(
                                __('Default', 'gremaza-wpb-addons') => '',
                                __('Normal', 'gremaza-wpb-addons') => 'normal',
                                __('Italic', 'gremaza-wpb-addons') => 'italic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Name Font Size', 'gremaza-wpb-addons'),
                            'param_name' => 'name_size',
                            'description' => __('e.g. 15px (leave empty for default)', 'gremaza-wpb-addons'),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Name Weight', 'gremaza-wpb-addons'),
                            'param_name' => 'name_weight',
                            'value' => array(
                                __('Default', 'gremaza-wpb-addons') => '',
                                __('Normal', 'gremaza-wpb-addons') => '400',
                                __('Medium', 'gremaza-wpb-addons') => '500',
                                __('Semi Bold', 'gremaza-wpb-addons') => '600',
                                __('Bold', 'gremaza-wpb-addons') => '700',
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Name Style', 'gremaza-wpb-addons'),
                            'param_name' => 'name_style',
                            'value' => array(
                                __('Default', 'gremaza-wpb-addons') => '',
                                __('Normal', 'gremaza-wpb-addons') => 'normal',
                                __('Italic', 'gremaza-wpb-addons') => 'italic',
                            ),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Position Font Size', 'gremaza-wpb-addons'),
                            'param_name' => 'position_size',
                            'description' => __('e.g. 13px (leave empty for default)', 'gremaza-wpb-addons'),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Position Weight', 'gremaza-wpb-addons'),
                            'param_name' => 'position_weight',
                            'value' => array(
                                __('Default', 'gremaza-wpb-addons') => '',
                                __('Normal', 'gremaza-wpb-addons') => '400',
                                __('Medium', 'gremaza-wpb-addons') => '500',
                                __('Semi Bold', 'gremaza-wpb-addons') => '600',
                                __('Bold', 'gremaza-wpb-addons') => '700',
                            ),
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __('Position Style', 'gremaza-wpb-addons'),
                            'param_name' => 'position_style_type',
                            'value' => array(
                                __('Default', 'gremaza-wpb-addons') => '',
                                __('Normal', 'gremaza-wpb-addons') => 'normal',
                                __('Italic', 'gremaza-wpb-addons') => 'italic',
                            ),
                        ),
                    ),
                ),

                // Style options
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Background Color', 'gremaza-wpb-addons'),
                    'param_name' => 'background_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Text Color', 'gremaza-wpb-addons'),
                    'param_name' => 'text_color',
                    'std' => '#000000',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Name Color', 'gremaza-wpb-addons'),
                    'param_name' => 'name_color',
                    'std' => '#F5D183',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Position Color', 'gremaza-wpb-addons'),
                    'param_name' => 'position_color',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Quote Accent Color', 'gremaza-wpb-addons'),
                    'param_name' => 'quote_color',
                    'description' => __('Color for the decorative opening quote', 'gremaza-wpb-addons'),
                    'std' => '#F5D183',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Quote Size', 'gremaza-wpb-addons'),
                    'param_name' => 'quote_scale',
                    'value' => array(
                        __('Default', 'gremaza-wpb-addons') => '',
                        __('1.5×', 'gremaza-wpb-addons') => '1.5',
                        __('2×', 'gremaza-wpb-addons') => '2',
                        __('3×', 'gremaza-wpb-addons') => '3',
                    ),
                    'description' => __('Make the opening quote larger', 'gremaza-wpb-addons'),
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Left Border Color', 'gremaza-wpb-addons'),
                    'param_name' => 'border_color',
                    'std' => '#F5D183',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                    'group' => __('Design', 'gremaza-wpb-addons'),
                ),
            ),
        ));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'layout' => 'horizontal',
            'reviews' => '',
            'horizontal_columns' => '3',
            'background_color' => '',
            'text_color' => '#000000',
            'name_color' => '#F5D183',
            'position_color' => '',
            'quote_color' => '#F5D183',
            'quote_scale' => '',
            'border_color' => '#F5D183',
            'extra_class' => '',
        ), $atts);

        $items = array();
        if (!empty($atts['reviews'])) {
            if (function_exists('vc_param_group_parse_atts')) {
                $items = vc_param_group_parse_atts($atts['reviews']);
            } else {
                // Fallback: try to decode as JSON-like string or skip
                $maybe = json_decode($atts['reviews'], true);
                $items = is_array($maybe) ? $maybe : array();
            }
            if (!is_array($items)) {
                $items = array();
            }
        }

        $wrapper_styles = array();
        if (!empty($atts['background_color'])) {
            $wrapper_styles[] = 'background-color: ' . esc_attr($atts['background_color']);
        }
        if (!empty($atts['text_color'])) {
            $wrapper_styles[] = 'color: ' . esc_attr($atts['text_color']);
        }
        $wrapper_style_attr = !empty($wrapper_styles) ? ' style="' . implode('; ', $wrapper_styles) . '"' : '';

        $global_card_styles = array();
        if (!empty($atts['border_color'])) {
            $global_card_styles[] = 'border-left: 4px solid ' . esc_attr($atts['border_color']);
        }

        $name_style_color = !empty($atts['name_color']) ? 'color: ' . esc_attr($atts['name_color']) : '';
        $position_style_color = !empty($atts['position_color']) ? 'color: ' . esc_attr($atts['position_color']) : '';
        $quote_inline = array();
        if (!empty($atts['quote_color'])) { $quote_inline[] = 'color: ' . esc_attr($atts['quote_color']); }
        if (!empty($atts['quote_scale'])) {
            $scale = floatval($atts['quote_scale']);
            if ($scale > 0) {
                // Base CSS is 48px; scale it
                $quote_inline[] = 'font-size: ' . (48 * $scale) . 'px';
                $quote_inline[] = 'line-height: 1';
            }
        }
        $quote_style = !empty($quote_inline) ? ' style="' . implode('; ', $quote_inline) . '"' : '';

        $classes = array('gremaza-reviews', 'gremaza-reviews-' . esc_attr($atts['layout']));
        if (!empty($atts['extra_class'])) {
            $extra_parts = preg_split('/\s+/', $atts['extra_class']);
            foreach ($extra_parts as $part) {
                $san = sanitize_html_class($part);
                if ($san) { $classes[] = $san; }
            }
        }
        $class_attr = implode(' ', array_map('esc_attr', $classes));

        // Data attributes for JS (e.g., dot color from border)
        $data_attrs = array();
    $dot_color = !empty($atts['border_color']) ? $atts['border_color'] : '#F5D183';
    $data_attrs[] = 'data-dot-color="' . esc_attr($dot_color) . '"';
        $data_attr_html = !empty($data_attrs) ? ' ' . implode(' ', $data_attrs) : '';

        // Determine columns if horizontal
        $cols = 0;
        if ($atts['layout'] === 'horizontal') {
            $cols = intval($atts['horizontal_columns']);
            if ($cols < 1) { $cols = 1; }
            if ($cols > 6) { $cols = 6; }
        }

        ob_start();
        ?>
    <div class="<?php echo $class_attr; ?>"<?php echo $wrapper_style_attr; ?><?php echo $data_attr_html; ?>>
            <div class="gremaza-reviews-list">
                <?php foreach ($items as $item): ?>
                    <?php
                        $text = isset($item['text']) ? $item['text'] : '';
                        $name = isset($item['name']) ? $item['name'] : '';
                        $position = isset($item['position']) ? $item['position'] : '';
                        $item_bg = isset($item['item_background_color']) ? $item['item_background_color'] : '';

                        // Build per-item card styles
                        $card_styles = $global_card_styles; // start with global styles
                        if (!empty($item_bg)) {
                            if (function_exists('sanitize_hex_color')) {
                                $clean_bg = sanitize_hex_color($item_bg);
                            } else {
                                $clean_bg = $item_bg;
                            }
                            if (!empty($clean_bg)) {
                                $card_styles[] = 'background-color: ' . esc_attr($clean_bg);
                            }
                        }
                        // Apply columns width for horizontal layout
                        if ($cols > 0) {
                            $percent = 100 / $cols;
                            $card_styles[] = 'flex: 0 0 ' . esc_attr($percent) . '%';
                            $card_styles[] = 'max-width: ' . esc_attr($percent) . '%';
                            $card_styles[] = 'min-width: 0';
                        }
                        $card_style_attr = !empty($card_styles) ? ' style="' . implode('; ', $card_styles) . '"' : '';

                        // Typography helpers
                        $sizes = array(
                            'text' => isset($item['text_size']) ? $item['text_size'] : '',
                            'name' => isset($item['name_size']) ? $item['name_size'] : '',
                            'position' => isset($item['position_size']) ? $item['position_size'] : '',
                        );
                        $weights = array(
                            'text' => isset($item['text_weight']) ? $item['text_weight'] : '',
                            'name' => isset($item['name_weight']) ? $item['name_weight'] : '',
                            'position' => isset($item['position_weight']) ? $item['position_weight'] : '',
                        );
                        $styles = array(
                            'text' => isset($item['text_style']) ? $item['text_style'] : '',
                            'name' => isset($item['name_style']) ? $item['name_style'] : '',
                            // Use different key to avoid collision with $position string
                            'position' => isset($item['position_style_type']) ? $item['position_style_type'] : '',
                        );

                        // Sanitizers
                        $sanitize_size = function($v) {
                            $v = trim((string)$v);
                            // normalize accidental spaces like "18 px" or "1 rem"
                            $v = preg_replace('/\s+/', '', $v);
                            if ($v === '') return '';
                            // if only number, append px
                            if (preg_match('/^\d+(?:\.\d+)?$/', $v)) {
                                $v = $v . 'px';
                            }
                            if (preg_match('/^\d+(?:\.\d+)?(px|em|rem|%|vh|vw)$/', $v)) {
                                return $v;
                            }
                            return '';
                        };
                        $sanitize_weight = function($v) {
                            $allowed = array('', 'normal', 'bold', '100','200','300','400','500','600','700','800','900');
                            $v = trim((string)$v);
                            return in_array($v, $allowed, true) ? $v : '';
                        };
                        $sanitize_style = function($v) {
                            $allowed = array('', 'normal', 'italic');
                            $v = trim((string)$v);
                            return in_array($v, $allowed, true) ? $v : '';
                        };

                        // Build inline styles for each text block
                        $text_inline = array();
                        $sz = $sanitize_size($sizes['text']); if ($sz) { $text_inline[] = 'font-size: ' . esc_attr($sz); }
                        $wt = $sanitize_weight($weights['text']); if ($wt) { $text_inline[] = 'font-weight: ' . esc_attr($wt); }
                        $st = $sanitize_style($styles['text']); if ($st) { $text_inline[] = 'font-style: ' . esc_attr($st); }
                        $text_style_attr = !empty($text_inline) ? ' style="' . implode('; ', $text_inline) . '"' : '';

                        $name_inline = array();
                        if (!empty($name_style_color)) { $name_inline[] = $name_style_color; }
                        $sz = $sanitize_size($sizes['name']); if ($sz) { $name_inline[] = 'font-size: ' . esc_attr($sz); }
                        $wt = $sanitize_weight($weights['name']); if ($wt) { $name_inline[] = 'font-weight: ' . esc_attr($wt); }
                        $st = $sanitize_style($styles['name']); if ($st) { $name_inline[] = 'font-style: ' . esc_attr($st); }
                        $name_style_attr = !empty($name_inline) ? ' style="' . implode('; ', $name_inline) . '"' : '';

                        $position_inline = array();
                        if (!empty($position_style_color)) { $position_inline[] = $position_style_color; }
                        $sz = $sanitize_size($sizes['position']); if ($sz) { $position_inline[] = 'font-size: ' . esc_attr($sz); }
                        $wt = $sanitize_weight($weights['position']); if ($wt) { $position_inline[] = 'font-weight: ' . esc_attr($wt); }
                        $st = $sanitize_style($styles['position']); if ($st) { $position_inline[] = 'font-style: ' . esc_attr($st); }
                        $position_style_attr = !empty($position_inline) ? ' style="' . implode('; ', $position_inline) . '"' : '';
                    ?>
                    <div class="gremaza-review-card"<?php echo $card_style_attr; ?>>
                        <div class="gremaza-review-quote"<?php echo $quote_style; ?>>“</div>
                        <div class="gremaza-review-content">
                            <?php if (!empty($text)): ?>
                                <div class="gremaza-review-text"<?php echo $text_style_attr; ?>><?php echo wp_kses_post(wpautop($text)); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($name)): ?>
                                <div class="gremaza-review-name"<?php echo $name_style_attr; ?>><?php echo esc_html($name); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($position)): ?>
                                <div class="gremaza-review-position"<?php echo $position_style_attr; ?>><?php echo esc_html($position); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($atts['layout'] === 'horizontal'): ?>
                <div class="gremaza-reviews-pagination" aria-label="Reviews pagination" role="tablist"></div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Let main plugin initialize the class
