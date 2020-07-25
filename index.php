<?php
/*
 * Plugin Name: Recipe
 * Description: Plugin for recipes creation and rating
 * Version: 1.0.0
 * Author: Jevisan
 */

/**
 * This prevents the plugin to be called directly
 */
if (!function_exists('add_action')) {
    echo "Hi there! I'm just a plugin, not much I can do when called directly.";
    exit;
}

// Setup
define('RECIPE_PLUGIN_URL', __FILE__);

// Includes
include('includes/activate.php');
include('includes/deactivate.php');
include('includes/init.php');
include('includes/front/enqueue.php');
include('process/save-post.php');
include('process/filter-content.php');
include('process/rate-recipe.php');
include('includes/admin/init.php');
include(dirname(RECIPE_PLUGIN_URL) . '/includes/widgets.php');
include('includes/widgets/daily-recipe.php');
include('includes/cron.php');
include('includes/utility.php');

// Hooks
register_activation_hook(__FILE__, 'r_activate_plugin');
register_deactivation_hook(__FILE__, 'r_deactivate_plugin');
add_action('init', 'recipe_init');
add_action('save_post_recipe', 'r_save_post_admin', 10, 3);
add_filter('the_content', 'r_filter_recipe_content');
add_action('wp_enqueue_scripts', 'r_enqueue_scripts', 100);
add_action('wp_ajax_r_rate_recipe', 'r_rate_recipe');
add_action('wp_ajax_nopriv_r_rate_recipe', 'r_rate_recipe'); // also process ajax requests from users with no login
add_action('admin_init', 'recipe_admin_init');
add_action('widgets_init', 'r_widgets_init');
add_action('r_daily_recipe_hook', 'r_daily_generate_recipe');

// Shortcodes
