<?php
/*
Plugin Name: My API Plugin
Description: A plugin to handle custom API functionality.
Version: 1.0
Author: Aldin Smajkan
*/

// Enqueue necessary scripts and stylesheets
function my_api_enqueue_scripts() {
    wp_enqueue_script('my-api-script', plugins_url('my-api-plugin/js/my-api-script.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'my_api_enqueue_scripts');

// Register custom API route
function my_api_register_routes() {
    register_rest_route('my-api/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'my_api_get_products',
    ));
}
add_action('rest_api_init', 'my_api_register_routes');

// Callback function to retrieve product data
function my_api_get_products($request) {
	// Check if the plugin is enabled
    $is_plugin_enabled = get_option('my_api_plugin_enabled', false);

    if (!$is_plugin_enabled) {
        return new WP_Error('plugin_disabled', 'The plugin is disabled.', array('status' => 403));
    }
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $products = get_posts($args);

    $response = array();

    foreach ($products as $product) {
        $product_data = array(
            'id' => $product->ID,
            'title' => html_entity_decode(get_the_title($product->ID)),
            'price' => get_post_meta($product->ID, '_regular_price', true),
            'short_description' => html_entity_decode(get_the_excerpt($product->ID)),
            'sku' => get_post_meta($product->ID, '_sku', true),
            'variations' => array(),
            'images' => array(),
        );

        // Get images for the product
        $product_images = get_post_meta($product->ID, '_product_image_gallery', true);
        if ($product_images) {
            $product_images = explode(',', $product_images);
            foreach ($product_images as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                if ($image_url) {
                    $product_data['images'][] = $image_url;
                }
            }
        }

        // Get variations if available
        if ($product->post_type === 'product_variation') {
            $parent_product_id = wp_get_post_parent_id($product->ID);
            $parent_product = get_post($parent_product_id);

            if ($parent_product) {
                // Get images for the parent product
                $parent_product_images = get_post_meta($parent_product->ID, '_product_image_gallery', true);
                if ($parent_product_images) {
                    $parent_product_images = explode(',', $parent_product_images);
                    foreach ($parent_product_images as $image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                        if ($image_url) {
                            $product_data['images'][] = $image_url;
                        }
                    }
                }

                $product_data['title'] = get_the_title($parent_product->ID);
                $product_data['price'] = get_post_meta($parent_product->ID, '_regular_price', true);
                $product_data['short_description'] = get_the_excerpt($parent_product->ID);
                $product_data['sku'] = get_post_meta($parent_product->ID, '_sku', true);
            }
        }

        // Add variations data if available
        if ($product->post_type === 'product' && $product->post_parent === 0) {
            $variations = get_children(array(
                'post_parent' => $product->ID,
                'post_type' => 'product_variation',
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'asc',
                'numberposts' => -1,
            ));

            foreach ($variations as $variation) {
                $variation_data = array(
                    'id' => $variation->ID,
                    'title' => strip_tags(html_entity_decode(get_the_title($variation->ID))),
                    'price' => get_post_meta($variation->ID, '_regular_price', true),
                    'sku' => get_post_meta($variation->ID, '_sku', true),
                    'images' => array(),
                );

                // Get images for the variation
                $variation_image_id = get_post_meta($variation->ID, '_thumbnail_id', true);
                if ($variation_image_id) {
                    $image_url = wp_get_attachment_image_url($variation_image_id, 'full');
                    if ($image_url) {
                        $variation_data['images'][] = $image_url;
                    }
                }

                $product_data['variations'][] = $variation_data;
            }
        }

        $response[] = $product_data;
    }

    return $response;
}

// Add dashboard menu
function my_api_plugin_menu() {
    add_menu_page(
        'My API Plugin',
        'My API Plugin',
        'manage_options',
        'my-api-plugin',
        'my_api_plugin_dashboard',
        'dashicons-admin-plugins',
        75
    );
}
add_action('admin_menu', 'my_api_plugin_menu');

// Callback function to display dashboard page
function my_api_plugin_dashboard() {
    // Check if the plugin is enabled or disabled
    $is_plugin_enabled = get_option('my_api_plugin_enabled', false);

    if (isset($_POST['my_api_plugin_action'])) {
        // Handle plugin enable/disable action
        $action = $_POST['my_api_plugin_action'];

        if ($action === 'enable') {
            update_option('my_api_plugin_enabled', true);
            $is_plugin_enabled = true;
        } elseif ($action === 'disable') {
            update_option('my_api_plugin_enabled', false);
            $is_plugin_enabled = false;
        }
    }

    ?>
    <div class="wrap">
        <h1>My API Plugin Dashboard</h1>
        <?php if ($is_plugin_enabled) { ?>
            <p>Status: <strong>Enabled</strong></p>
            <button id="my-api-disable-button" class="button button-primary">Disable Plugin</button>
        <?php } else { ?>
            <p>Status: <strong>Disabled</strong></p>
            <button id="my-api-enable-button" class="button button-primary">Enable Plugin</button>
        <?php } ?>
    </div>

    <script>
    (function($) {
        $(document).ready(function() {
            // Handle enable button click
            $('#my-api-enable-button').on('click', function() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: {
                        action: 'my_api_plugin_enable',
                        security: '<?php echo wp_create_nonce('my-api-plugin'); ?>',
                    },
                    success: function(response) {
                        // Reload the page
                        location.reload();
                    }
                });
            });

            // Handle disable button click
            $('#my-api-disable-button').on('click', function() {
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    method: 'POST',
                    data: {
                        action: 'my_api_plugin_disable',
                        security: '<?php echo wp_create_nonce('my-api-plugin'); ?>',
                    },
                    success: function(response) {
                        // Reload the page
                        location.reload();
                    }
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}

// AJAX callback to enable the plugin
function my_api_plugin_enable() {
    // Verify the nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'my-api-plugin')) {
        die('Invalid nonce');
    }

    // Update plugin status
    update_option('my_api_plugin_enabled', true);

    wp_die(); // This is required to terminate the AJAX call
}
add_action('wp_ajax_my_api_plugin_enable', 'my_api_plugin_enable');

// AJAX callback to disable the plugin
function my_api_plugin_disable() {
    // Verify the nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'my-api-plugin')) {
        die('Invalid nonce');
    }

    // Update plugin status
    update_option('my_api_plugin_enabled', false);

    wp_die(); // This is required to terminate the AJAX call
}
add_action('wp_ajax_my_api_plugin_disable', 'my_api_plugin_disable');
