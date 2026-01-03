<?php
/**
 * Post Carousel Element for WPBakery Page Builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GremazaPostCarousel {
    
    public function __construct() {
        // Hook into multiple actions to ensure registration
        add_action('vc_before_init', array($this, 'map_shortcode'));
        add_action('init', array($this, 'map_shortcode'), 20);
        add_action('wp_loaded', array($this, 'map_shortcode'));
        // Register the shortcode
        add_shortcode('gremaza_post_carousel', array($this, 'render_shortcode'));
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
        
        // Get all post types
        $post_types = get_post_types(array('public' => true), 'objects');
        $post_type_options = array();
        foreach ($post_types as $post_type) {
            if (!in_array($post_type->name, array('attachment', 'revision', 'nav_menu_item'))) {
                $post_type_options[$post_type->label] = $post_type->name;
            }
        }
        
        vc_map(array(
            'name' => 'Post Carousel',
            'base' => 'gremaza_post_carousel',
            'category' => 'By Gremaza',
            'description' => 'Display posts in a carousel with category filtering',
            'icon' => 'icon-wpb-ui-carousel',
            'show_settings_on_create' => true,
            'is_container' => false,
            'content_element' => true,
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __('Post Type', 'gremaza-wpb-addons'),
                    'param_name' => 'post_type',
                    'value' => $post_type_options,
                    'std' => 'post',
                    'description' => __('Select the post type to display', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Number of Posts', 'gremaza-wpb-addons'),
                    'param_name' => 'posts_per_page',
                    'value' => '9',
                    'description' => __('How many posts to load', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'dropdown',
                    'heading' => __('Order By', 'gremaza-wpb-addons'),
                    'param_name' => 'orderby',
                    'value' => array(
                        __('Date', 'gremaza-wpb-addons') => 'date',
                        __('Title', 'gremaza-wpb-addons') => 'title',
                        __('Random', 'gremaza-wpb-addons') => 'rand',
                        __('Menu Order', 'gremaza-wpb-addons') => 'menu_order',
                    ),
                    'std' => 'date',
                    'description' => __('Sort order', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'dropdown',
                    'heading' => __('Order', 'gremaza-wpb-addons'),
                    'param_name' => 'order',
                    'value' => array(
                        __('Descending', 'gremaza-wpb-addons') => 'DESC',
                        __('Ascending', 'gremaza-wpb-addons') => 'ASC',
                    ),
                    'std' => 'DESC',
                    'description' => __('Sort direction', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'dropdown',
                    'heading' => __('Show Category Filter', 'gremaza-wpb-addons'),
                    'param_name' => 'show_filter',
                    'value' => array(
                        __('Yes', 'gremaza-wpb-addons') => 'yes',
                        __('No', 'gremaza-wpb-addons') => 'no',
                    ),
                    'std' => 'yes',
                    'description' => __('Show category filter buttons', 'gremaza-wpb-addons'),
                ),
                
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra CSS Class', 'gremaza-wpb-addons'),
                    'param_name' => 'extra_class',
                    'value' => '',
                    'description' => __('Add extra CSS class for custom styling', 'gremaza-wpb-addons'),
                ),
            )
        ));
    }
    
    public function render_shortcode($atts) {
        // Extract shortcode attributes
        $atts = shortcode_atts(array(
            'post_type' => 'post',
            'posts_per_page' => '9',
            'orderby' => 'date',
            'order' => 'DESC',
            'show_filter' => 'yes',
            'extra_class' => '',
        ), $atts);
        
        // Generate unique ID for this carousel
        $carousel_id = 'gremaza-post-carousel-' . uniqid();
        
        // Build CSS classes
        $css_class = 'gremaza-post-carousel';
        if (!empty($atts['extra_class'])) {
            $css_class .= ' ' . esc_attr($atts['extra_class']);
        }
        
        // Get taxonomy for the post type - dynamically detect
        $taxonomy = 'category'; // Default
        $taxonomies = get_object_taxonomies($atts['post_type'], 'objects');
        
        // Priority order: category, car_category, post_tag, then first available
        if (isset($taxonomies['category'])) {
            $taxonomy = 'category';
        } elseif (isset($taxonomies['car_category'])) {
            $taxonomy = 'car_category';
        } elseif (isset($taxonomies['post_tag'])) {
            $taxonomy = 'post_tag';
        } elseif (!empty($taxonomies)) {
            // Get first hierarchical taxonomy, or first taxonomy if no hierarchical found
            foreach ($taxonomies as $tax_name => $tax_obj) {
                if ($tax_obj->hierarchical) {
                    $taxonomy = $tax_name;
                    break;
                }
            }
            // If no hierarchical taxonomy found, use first available
            if ($taxonomy === 'category' && !isset($taxonomies['category'])) {
                $taxonomy = key($taxonomies);
            }
        }
        
        // Query posts
        $query_args = array(
            'post_type' => $atts['post_type'],
            'posts_per_page' => intval($atts['posts_per_page']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_status' => 'publish',
        );
        
        $query = new WP_Query($query_args);
        
        // Get categories
        $categories = array();
        if ($atts['show_filter'] === 'yes') {
            $categories = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => true,
            ));
        }
        
        // Start output buffering
        ob_start();
        ?>
        <div class="<?php echo esc_attr($css_class); ?>" id="<?php echo esc_attr($carousel_id); ?>" data-post-type="<?php echo esc_attr($atts['post_type']); ?>">
            
            <?php if ($atts['show_filter'] === 'yes' && !empty($categories) && !is_wp_error($categories)): ?>
                <div class="gremaza-carousel-filters">
                    <button class="gremaza-filter-btn active" data-category="*">
                        <?php _e('All', 'gremaza-wpb-addons'); ?>
                    </button>
                    <?php foreach ($categories as $category): ?>
                        <button class="gremaza-filter-btn" data-category="<?php echo esc_attr($category->term_id); ?>">
                            <?php echo esc_html($category->name); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="gremaza-carousel-wrapper">
                <button class="gremaza-carousel-prev" aria-label="Previous">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                
                <div class="gremaza-carousel-container">
                    <div class="gremaza-carousel-track">
                        <?php
                        if ($query->have_posts()) {
                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                
                                // Get post categories
                                $post_terms = wp_get_post_terms($post_id, $taxonomy);
                                $term_ids = array();
                                if (!empty($post_terms) && !is_wp_error($post_terms)) {
                                    foreach ($post_terms as $term) {
                                        $term_ids[] = $term->term_id;
                                    }
                                }
                                $term_classes = !empty($term_ids) ? implode(' ', array_map(function($id) { return 'cat-' . $id; }, $term_ids)) : '';
                                
                                // Get featured image
                                $image_url = get_the_post_thumbnail_url($post_id, 'medium_large');
                                if (!$image_url) {
                                    $image_url = GREMAZA_WPB_PLUGIN_URL . 'assets/images/placeholder.jpg';
                                }
                                
                                // Get Mercedes buttons if post type is car_model
                                $mercedes_buttons = array();
                                if ($atts['post_type'] === 'car_model') {
                                    $mercedes_buttons = get_post_meta($post_id, '_mercedes_albania_slider_buttons', true);
                                }
                                ?>
                                <div class="gremaza-carousel-item <?php echo esc_attr($term_classes); ?>" data-categories="<?php echo esc_attr(implode(',', $term_ids)); ?>">
                                    <div class="gremaza-carousel-card">
                                        <div class="gremaza-carousel-image" style="background-image: url('<?php echo esc_url($image_url); ?>');">
                                            <a href="<?php echo esc_url(get_permalink()); ?>" class="gremaza-carousel-image-link"></a>
                                        </div>
                                        <div class="gremaza-carousel-content">
                                            <h2 class="gremaza-carousel-title">
                                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                                    <?php echo esc_html(get_the_title()); ?>
                                                </a>
                                            </h2>
                                            <a href="<?php echo esc_url(get_permalink()); ?>" class="gremaza-carousel-read-more">
                                                <?php _e('Lexo më shumë', 'gremaza-wpb-addons'); ?>
                                            </a>
                                            
                                            <?php if ($atts['post_type'] === 'car_model' && !empty($mercedes_buttons)): ?>
                                                <div class="gremaza-carousel-buttons">
                                                    <?php if (!empty($mercedes_buttons['button1_text'])): ?>
                                                        <a href="<?php echo esc_url($mercedes_buttons['button1_link'] ?? '#'); ?>" class="gremaza-carousel-btn gremaza-carousel-btn-outline">
                                                            <?php echo esc_html($mercedes_buttons['button1_text']); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($mercedes_buttons['button2_text'])): ?>
                                                        <a href="<?php echo esc_url($mercedes_buttons['button2_link'] ?? '#'); ?>" class="gremaza-carousel-btn gremaza-carousel-btn-blue">
                                                            <?php echo esc_html($mercedes_buttons['button2_text']); ?>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        } else {
                            ?>
                            <div class="gremaza-carousel-empty">
                                <p><?php _e('No posts found.', 'gremaza-wpb-addons'); ?></p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                
                <button class="gremaza-carousel-next" aria-label="Next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
}

// Don't initialize here - let the main plugin do it
