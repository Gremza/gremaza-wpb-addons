<?php
if (!defined('ABSPATH')) {
    exit;
}


class GremazaSeparator {
    public function __construct() {
        // Register on multiple actions for reliability (like hero banner)
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        add_shortcode('gremaza_separator', array($this, 'render_shortcode'));
    }

    // Use the same naming as hero banner for registration
    public function map_shortcode() {
        $this->vc_map_separator();
    }

    // Keep the original vc_map_separator for mapping

    public function vc_map_separator() {
        if (!function_exists('vc_map')) {
            error_log('Gremaza Separator vc_map: vc_map not found');
            return;
        }
        static $registered = false;
        if ($registered) {
            error_log('Gremaza Separator vc_map: already registered');
            return;
        }
        $registered = true;
        error_log('Gremaza Separator vc_map called');
        vc_map(array(
            'name' => __('Separator Gremza', 'gremaza-wpb-addons'),
            'base' => 'gremaza_separator',
            'category' => __('By Gremaza', 'gremaza-wpb-addons'),
            'description' => __('Stylish horizontal separator', 'gremaza-wpb-addons'),
            'icon' => 'icon-wpb-ui-separator',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'as_parent' => array('except' => ''),
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __('Style', 'gremaza-wpb-addons'),
                    'param_name' => 'style',
            'value' => array(
                __('Wave', 'gremaza-wpb-addons') => 'wave',
                __('Double Wave', 'gremaza-wpb-addons') => 'double-wave',
                __('Zigzag', 'gremaza-wpb-addons') => 'zigzag',
                __('Zigzag Reverse', 'gremaza-wpb-addons') => 'zigzag-reverse',
                __('Curve', 'gremaza-wpb-addons') => 'curve',
                __('Tilt', 'gremaza-wpb-addons') => 'tilt',
                __('Triangle', 'gremaza-wpb-addons') => 'triangle',
                __('Triangle Middle', 'gremaza-wpb-addons') => 'triangle-middle',
            ),
                    'description' => __('Choose separator style', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Primary Color', 'gremaza-wpb-addons'),
                    'param_name' => 'color1',
                    'value' => '#0099ff',
                    'description' => __('Main color for the separator', 'gremaza-wpb-addons'),
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => __('Secondary Color', 'gremaza-wpb-addons'),
                    'param_name' => 'color2',
                    'value' => '#ffffff',
                    'description' => __('Secondary color (for wave style)', 'gremaza-wpb-addons'),
                    'dependency' => array('element' => 'style', 'value' => array('wave')),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Height (px)', 'gremaza-wpb-addons'),
                    'param_name' => 'height',
                    'value' => '60',
                    'description' => __('Height of the separator', 'gremaza-wpb-addons'),
                ),
            ),
        ));
    }

    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'style' => 'wave',
            'color1' => '#0099ff',
            'color2' => '#ffffff',
            'height' => '60',
        ), $atts, 'gremaza_separator');

        $style = $atts['style'];
        $color1 = esc_attr($atts['color1']);
        $color2 = esc_attr($atts['color2']);
        $height = intval($atts['height']);

        ob_start();
        if ($style === 'wave') {
            ?>
            <div class="gremaza-separator gremaza-separator-wave" style="height:<?php echo $height; ?>px;">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <path d="M0,60 Q100,0 200,60 T400,60 T600,60 T800,60 T1000,60 T1200,60 L1200,120 L0,120 Z" fill="<?php echo $color1; ?>"/>
                    <path d="M0,60 Q100,0 200,60 T400,60 T600,60 T800,60 T1000,60 T1200,60" fill="none" stroke="<?php echo $color2; ?>" stroke-width="8"/>
                </svg>
            </div>
            <?php
        } elseif ($style === 'curve') {
            ?>
            <div class="gremaza-separator gremaza-separator-curve" style="height:<?php echo $height; ?>px;">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <path d="M0,0 C300,120 900,0 1200,120 L1200,120 L0,120 Z" fill="<?php echo $color1; ?>"/>
                </svg>
            </div>
            <?php
        } elseif ($style === 'tilt') {
            ?>
            <div class="gremaza-separator gremaza-separator-tilt" style="height:<?php echo $height; ?>px;">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <polygon points="0,0 1200,0 1200,120" fill="<?php echo $color1; ?>"/>
                </svg>
            </div>
            <?php
        } elseif ($style === 'double-wave') {
            ?>
            <div class="gremaza-separator gremaza-separator-double-wave" style="height:<?php echo $height; ?>px;">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <path d="M0,60 Q100,0 200,60 T400,60 T600,60 T800,60 T1000,60 T1200,60 L1200,120 L0,120 Z" fill="<?php echo $color1; ?>"/>
                    <path d="M0,80 Q100,20 200,80 T400,80 T600,80 T800,80 T1000,80 T1200,80" fill="none" stroke="<?php echo $color2; ?>" stroke-width="6"/>
                    <path d="M0,40 Q100,100 200,40 T400,40 T600,40 T800,40 T1000,40 T1200,40" fill="none" stroke="<?php echo $color2; ?>" stroke-width="6"/>
                </svg>
            </div>
            <?php
        } elseif ($style === 'zigzag') {
            // Render a dense row of small white triangles with colored background above
            $triangle_base = 40; // width of each triangle
            $triangle_height = $height; // height of each triangle
            $svg_width = 1200;
            $svg_height = $triangle_height;
            $num_triangles = ceil($svg_width / $triangle_base);
            ?>
            <div class="gremaza-separator gremaza-separator-zigzag" style="height:<?php echo $triangle_height; ?>px;">
                <svg viewBox="0 0 <?php echo $svg_width; ?> <?php echo $svg_height; ?>" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <rect x="0" y="0" width="<?php echo $svg_width; ?>" height="<?php echo $svg_height; ?>" fill="<?php echo $color1; ?>"/>
                    <?php
                    for ($i = 0; $i < $num_triangles; $i++) {
                        $x1 = $i * $triangle_base;
                        $x2 = $x1 + $triangle_base / 2;
                        $x3 = $x1 + $triangle_base;
                        $y1 = $svg_height;
                        $y2 = 0;
                        $y3 = $svg_height;
                        echo '<polygon points="' . $x1 . ',' . $y1 . ' ' . $x2 . ',' . $y2 . ' ' . $x3 . ',' . $y3 . '" fill="#fff" />';
                    }
                    ?>
                </svg>
            </div>
            <?php
        } elseif ($style === 'zigzag-reverse') {
            // Render a dense row of small colored triangles with white/transparent background
            $triangle_base = 40; // width of each triangle
            $triangle_height = $height; // height of each triangle
            $svg_width = 1200;
            $svg_height = $triangle_height;
            $num_triangles = ceil($svg_width / $triangle_base);
            ?>
            <div class="gremaza-separator gremaza-separator-zigzag-reverse" style="height:<?php echo $triangle_height; ?>px;">
                <svg viewBox="0 0 <?php echo $svg_width; ?> <?php echo $svg_height; ?>" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <rect x="0" y="0" width="<?php echo $svg_width; ?>" height="<?php echo $svg_height; ?>" fill="#fff"/>
                    <?php
                    for ($i = 0; $i < $num_triangles; $i++) {
                        $x1 = $i * $triangle_base;
                        $x2 = $x1 + $triangle_base / 2;
                        $x3 = $x1 + $triangle_base;
                        $y1 = $svg_height;
                        $y2 = 0;
                        $y3 = $svg_height;
                        echo '<polygon points="' . $x1 . ',' . $y1 . ' ' . $x2 . ',' . $y2 . ' ' . $x3 . ',' . $y3 . '" fill="' . $color1 . '" />';
                    }
                    ?>
                </svg>
            </div>
            <?php
        } elseif ($style === 'triangle') {
            ?>
            <div class="gremaza-separator gremaza-separator-triangle" style="height:<?php echo $height; ?>px;">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <polygon points="0,0 1200,120 1200,120 0,120" fill="<?php echo $color1; ?>"/>
                </svg>
            </div>
            <?php
        } elseif ($style === 'triangle-middle') {
            // Centered triangle, base on top, width 120px, triangle is color1, background is white/transparent
            $triangle_width = 120;
            $triangle_height = $height;
            $svg_width = 1200;
            $svg_height = $triangle_height;
            $center_x = $svg_width / 2;
            $half_base = $triangle_width / 2;
            $base_y = 0;
            $apex_y = $triangle_height;
            ?>
            <div class="gremaza-separator gremaza-separator-triangle-middle" style="height:<?php echo $triangle_height; ?>px; position: relative;">
                <svg viewBox="0 0 <?php echo $svg_width; ?> <?php echo $svg_height; ?>" preserveAspectRatio="none" style="width:100%;height:100%;display:block;">
                    <rect x="0" y="0" width="<?php echo $svg_width; ?>" height="<?php echo $svg_height; ?>" fill="#fff"/>
                    <polygon points="<?php echo ($center_x - $half_base); ?>,<?php echo $base_y; ?> <?php echo ($center_x + $half_base); ?>,<?php echo $base_y; ?> <?php echo $center_x; ?>,<?php echo $apex_y; ?>" fill="<?php echo $color1; ?>"/>
                </svg>
            </div>
            <?php
        }
        return ob_get_clean();
    }
}

new GremazaSeparator();
