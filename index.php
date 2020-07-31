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
include('includes/shortcodes/creator.php');
include('process/submit-user-recipe.php');
include('includes/shortcodes/auth-form.php');
include('process/create-account.php');
include('process/login.php');
include('includes/shortcodes/auth-alt-form.php');
include('includes/front/logout-link.php');

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
add_action('wp_ajax_r_submit_user_recipe', 'r_submit_user_recipe');
add_action('wp_ajax_nopriv_r_submit_user_recipe', 'r_submit_user_recipe');
add_action('wp_ajax_nopriv_recipe_create_account', 'recipe_create_account');
add_action('wp_ajax_nopriv_recipe_user_login', 'recipe_user_login');
// add_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
// add_filter('authenticate', 'wp_authenticate_spam_check', 99);

add_filter('authenticate', 'r_alt_authenticate', 100, 3);
add_filter('wp_nav_menu_secondary_items', 'ju_new_nav_menu_items', 999);

// Shortcodes
add_shortcode('recipe_creator', 'r_recipe_creator_shortcode');
add_shortcode('recipe_auth_form', 'r_recipe_auth_form_shortcode');
add_shortcode('recipe_auth_alt_form', 'r_recipe_auth_alt_form_shortcode');
