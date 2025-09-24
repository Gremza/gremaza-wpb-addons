<?php
/**
 * Citation Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
	exit;
}

class GremazaCitation {

	public function __construct() {
		add_action('vc_before_init', array($this, 'map_shortcode'));
		add_action('init', array($this, 'map_shortcode'), 20);
		add_action('wp_loaded', array($this, 'map_shortcode'));
		add_shortcode('gremaza_citation', array($this, 'render_shortcode'));
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
			'name' => __('Citation', 'gremaza-wpb-addons'),
			'base' => 'gremaza_citation',
			'category' => 'By Gremaza',
			'description' => __('Single citation text with customizable left border and styles', 'gremaza-wpb-addons'),
			'icon' => 'icon-wpb-quote',
			'show_settings_on_create' => true,
			'is_container' => false,
			'content_element' => true,
			'params' => array(
				array(
					'type' => 'textarea',
					'heading' => __('Citation Text', 'gremaza-wpb-addons'),
					'param_name' => 'text',
					'admin_label' => true,
				),

				// Design options
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
					'std' => '#333333',
					'group' => __('Design', 'gremaza-wpb-addons'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Font Size', 'gremaza-wpb-addons'),
					'param_name' => 'font_size',
					'description' => __('e.g. 18px, 1.1rem (leave empty for default)', 'gremaza-wpb-addons'),
					'group' => __('Design', 'gremaza-wpb-addons'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Font Weight', 'gremaza-wpb-addons'),
					'param_name' => 'font_weight',
					'value' => array(
						__('Default', 'gremaza-wpb-addons') => '',
						__('Light (300)', 'gremaza-wpb-addons') => '300',
						__('Normal (400)', 'gremaza-wpb-addons') => '400',
						__('Medium (500)', 'gremaza-wpb-addons') => '500',
						__('Semi Bold (600)', 'gremaza-wpb-addons') => '600',
						__('Bold (700)', 'gremaza-wpb-addons') => '700',
					),
					'group' => __('Design', 'gremaza-wpb-addons'),
				),
				array(
					'type' => 'dropdown',
					'heading' => __('Font Style', 'gremaza-wpb-addons'),
					'param_name' => 'font_style',
					'value' => array(
						__('Default', 'gremaza-wpb-addons') => '',
						__('Normal', 'gremaza-wpb-addons') => 'normal',
						__('Italic', 'gremaza-wpb-addons') => 'italic',
					),
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
					'type' => 'textfield',
					'heading' => __('Line Height', 'gremaza-wpb-addons'),
					'param_name' => 'line_height',
					'description' => __('e.g. 1.6, 24px (optional)', 'gremaza-wpb-addons'),
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
					'heading' => __('Left Border Width', 'gremaza-wpb-addons'),
					'param_name' => 'border_width',
					'description' => __('e.g. 4px (defaults to 4px if empty)', 'gremaza-wpb-addons'),
					'group' => __('Design', 'gremaza-wpb-addons'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Padding', 'gremaza-wpb-addons'),
					'param_name' => 'padding',
					'description' => __('CSS padding shorthand, e.g. 16px 20px (optional)', 'gremaza-wpb-addons'),
					'group' => __('Design', 'gremaza-wpb-addons'),
				),
				array(
					'type' => 'textfield',
					'heading' => __('Border Radius', 'gremaza-wpb-addons'),
					'param_name' => 'border_radius',
					'description' => __('e.g. 6px (optional)', 'gremaza-wpb-addons'),
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
			'text' => '',
			'background_color' => '',
			'text_color' => '#333333',
			'font_size' => '',
			'font_weight' => '',
			'font_style' => '',
			'text_align' => 'left',
			'line_height' => '',
			'border_color' => '#F5D183',
			'border_width' => '4px',
			'padding' => '',
			'border_radius' => '',
			'extra_class' => '',
		), $atts);

		$sanitize_size = function($v) {
			$v = trim((string)$v);
			$v = preg_replace('/\s+/', '', $v);
			if ($v === '') return '';
			if (preg_match('/^\d+(?:\.\d+)?$/', $v)) { $v .= 'px'; }
			return preg_match('/^\d+(?:\.\d+)?(px|em|rem|%|vh|vw)$/', $v) ? $v : '';
		};
		$sanitize_weight = function($v) {
			$allowed = array('', 'normal', 'bold','100','200','300','400','500','600','700','800','900');
			$v = trim((string)$v);
			return in_array($v, $allowed, true) ? $v : '';
		};
		$sanitize_style = function($v) {
			$allowed = array('', 'normal', 'italic');
			$v = trim((string)$v);
			return in_array($v, $allowed, true) ? $v : '';
		};
		$sanitize_line_height = function($v) {
			$v = trim((string)$v);
			if ($v === '') return '';
			// allow number, unit value like 24px, 1.6, etc
			if (preg_match('/^(?:\d+(?:\.\d+)?(?:px|em|rem|%)?|\d+(?:\.\d+)?)$/', $v)) {
				return $v;
			}
			return '';
		};

		// Build wrapper styles
		$wrapper_styles = array('border-left-style: solid');
		if (!empty($atts['background_color'])) {
			$wrapper_styles[] = 'background-color: ' . esc_attr($atts['background_color']);
		}
		if (!empty($atts['text_color'])) {
			$wrapper_styles[] = 'color: ' . esc_attr($atts['text_color']);
		}
		if (!empty($atts['border_color'])) {
			$wrapper_styles[] = 'border-left-color: ' . esc_attr($atts['border_color']);
		}
		$bw = $sanitize_size($atts['border_width']);
		$wrapper_styles[] = 'border-left-width: ' . ($bw ?: '4px');
		if (!empty($atts['padding'])) {
			$wrapper_styles[] = 'padding: ' . esc_attr($atts['padding']);
		}
		if (!empty($atts['border_radius'])) {
			$wrapper_styles[] = 'border-radius: ' . esc_attr($atts['border_radius']);
		}
		$wrapper_style_attr = ' style="' . implode('; ', $wrapper_styles) . '"';

		// Text styles
		$text_styles = array();
		$fs = $sanitize_size($atts['font_size']); if ($fs) { $text_styles[] = 'font-size: ' . esc_attr($fs); }
		$fw = $sanitize_weight($atts['font_weight']); if ($fw) { $text_styles[] = 'font-weight: ' . esc_attr($fw); }
		$fst = $sanitize_style($atts['font_style']); if ($fst) { $text_styles[] = 'font-style: ' . esc_attr($fst); }
		$ta = in_array($atts['text_align'], array('left','center','right'), true) ? $atts['text_align'] : '';
		if ($ta) { $text_styles[] = 'text-align: ' . esc_attr($ta); }
		$lh = $sanitize_line_height($atts['line_height']); if ($lh) { $text_styles[] = 'line-height: ' . esc_attr($lh); }
		$text_style_attr = !empty($text_styles) ? ' style="' . implode('; ', $text_styles) . '"' : '';

		$classes = array('gremaza-citation');
		if (!empty($atts['extra_class'])) {
			$extra_parts = preg_split('/\s+/', $atts['extra_class']);
			foreach ($extra_parts as $part) {
				$san = sanitize_html_class($part);
				if ($san) { $classes[] = $san; }
			}
		}
		$class_attr = implode(' ', array_map('esc_attr', $classes));

		ob_start();
		?>
		<div class="<?php echo $class_attr; ?>"<?php echo $wrapper_style_attr; ?>>
			<?php if (!empty($atts['text'])): ?>
				<div class="gremaza-citation-text"<?php echo $text_style_attr; ?>>
					<?php echo wp_kses_post(wpautop($atts['text'])); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

// Let main plugin initialize the class

