<?php
if (!defined('ABSPATH')) {
    exit;
}

class GremazaAnimatedDivider {
    public function __construct() {
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_animated_divider', array($this, 'render_animated_divider'));
    }

    public function map_shortcode() {
        $this->vc_map_animated_divider();
    }

    public function vc_map_animated_divider() {
        if (!function_exists('vc_map')) {
            error_log('Gremaza Animated Divider vc_map: vc_map not found');
            return;
        }
        static $registered = false;
        if ($registered) {
            error_log('Gremaza Animated Divider vc_map: already registered');
            return;
        }
        $registered = true;
        error_log('Gremaza Animated Divider vc_map called');
        vc_map(array(
            'name' => __('Animated SVG Divider', 'gremaza-wpb-addons'),
            'base' => 'gremaza_animated_divider',
            'category' => __('By Gremaza', 'gremaza-wpb-addons'),
            'description' => __('SVG divider with animation styles', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-ui-separator',
            'show_settings_on_create' => true,
            'content_element' => true,
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __('Decoration Style', 'gremaza-wpb-addons'),
                    'param_name' => 'style',
                    'value' => array(
                        __('Swirl Line', 'gremaza-wpb-addons') => 'swirl',
                        __('Flower', 'gremaza-wpb-addons') => 'flower',
                        __('Dotted Line', 'gremaza-wpb-addons') => 'dotted',
                        __('Curly Flourish', 'gremaza-wpb-addons') => 'curly',
                    ),
                    'description' => __('Choose decorative divider style', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Color', 'gremaza-wpb-addons'),
                    'param_name' => 'color',
                    'value' => '#0099ff',
                    'description' => __('Divider color', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Height (px)', 'gremaza-wpb-addons'),
                    'param_name' => 'height',
                    'value' => '40',
                    'description' => __('Height of the divider', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Width (px or %)', 'gremaza-wpb-addons'),
                    'param_name' => 'width',
                    'value' => '200',
                    'description' => __('Width of the divider (e.g. 200, 50%, 300px)', 'gremaza-wpb-addons'),
                ),
            ),
        ));
    }

    public function render_animated_divider($atts) {
        $atts = shortcode_atts(array(
            'style' => 'wave',
            'color' => '#0099ff',
            'height' => '40',
            'width' => '200',
        ), $atts, 'gremaza_animated_divider');

        $style = $atts['style'];
        $color = esc_attr($atts['color']);
        $height = intval($atts['height']);
        $width = trim($atts['width']);
        if (is_numeric($width)) {
            $width_css = $width . 'px';
        } else {
            $width_css = $width;
        }

        ob_start();
        if ($style === 'swirl') {
            // Improved swirl line SVG
            ?>
            <div class="gremaza-animated-divider gremaza-animated-divider-swirl" style="height:<?php echo $height; ?>px;width:<?php echo esc_attr($width_css); ?>;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 200 40" width="100%" height="100%" style="display:block;">
                    <path d="M10,30 Q40,10 70,30 Q100,50 130,10 Q160,30 190,20" fill="none" stroke="<?php echo $color; ?>" stroke-width="3.5" stroke-linecap="round"/>
                    <path d="M130,10 Q140,0 150,10" fill="none" stroke="<?php echo $color; ?>" stroke-width="2"/>
                </svg>
            </div>
            <?php
        } elseif ($style === 'flower') {
            // Improved flower shape SVG (classic flower: center + 6 petals)
            ?>
            <div class="gremaza-animated-divider gremaza-animated-divider-flower" style="height:<?php echo $height; ?>px;width:<?php echo esc_attr($width_css); ?>;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 200 40" width="100%" height="100%" style="display:block;">
                    <g transform="translate(100,20)">
                        <circle r="6" fill="<?php echo $color; ?>"/>
                        <ellipse rx="14" ry="5" fill="none" stroke="<?php echo $color; ?>" stroke-width="2" transform="rotate(0) translate(18,0)"/>
                        <ellipse rx="14" ry="5" fill="none" stroke="<?php echo $color; ?>" stroke-width="2" transform="rotate(60) translate(18,0)"/>
                        <ellipse rx="14" ry="5" fill="none" stroke="<?php echo $color; ?>" stroke-width="2" transform="rotate(120) translate(18,0)"/>
                        <ellipse rx="14" ry="5" fill="none" stroke="<?php echo $color; ?>" stroke-width="2" transform="rotate(180) translate(18,0)"/>
                        <ellipse rx="14" ry="5" fill="none" stroke="<?php echo $color; ?>" stroke-width="2" transform="rotate(240) translate(18,0)"/>
                        <ellipse rx="14" ry="5" fill="none" stroke="<?php echo $color; ?>" stroke-width="2" transform="rotate(300) translate(18,0)"/>
                    </g>
                </svg>
            </div>
            <?php
        } elseif ($style === 'dotted') {
            // Improved dotted line SVG (evenly spaced dots)
            ?>
            <div class="gremaza-animated-divider gremaza-animated-divider-dotted" style="height:<?php echo $height; ?>px;width:<?php echo esc_attr($width_css); ?>;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 200 40" width="100%" height="100%" style="display:block;">
                    <?php 
                    $dot_count = 20; 
                    $dot_radius = 2.5;
                    $start = 10;
                    $end = 190;
                    $step = ($end - $start) / ($dot_count - 1);
                    for($i=0;$i<$dot_count;$i++): 
                        $cx = $start + $i * $step; 
                    ?>
                        <circle cx="<?php echo $cx; ?>" cy="20" r="<?php echo $dot_radius; ?>" fill="<?php echo $color; ?>"/>
                    <?php endfor; ?>
                </svg>
            </div>
            <?php
        } elseif ($style === 'curly') {
            // Curly flourish SVG
            ?>
            <div class="gremaza-animated-divider gremaza-animated-divider-curly" style="height:<?php echo $height; ?>px;width:<?php echo esc_attr($width_css); ?>;margin:0 auto;display:flex;align-items:center;justify-content:center;">
                <svg viewBox="0 0 200 40" width="100%" height="100%" style="display:block;">
                    <path d="M10,30 Q40,10 70,30 Q100,50 130,30 Q160,10 190,30" fill="none" stroke="<?php echo $color; ?>" stroke-width="3" stroke-linecap="round"/>
                    <path d="M60,20 Q70,10 80,20" fill="none" stroke="<?php echo $color; ?>" stroke-width="2"/>
                    <path d="M120,20 Q130,10 140,20" fill="none" stroke="<?php echo $color; ?>" stroke-width="2"/>
                </svg>
            </div>
            <?php
        }
        return ob_get_clean();
    }
}

new GremazaAnimatedDivider();
