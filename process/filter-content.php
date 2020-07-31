<?php

function r_filter_recipe_content($content)
{
    // check if this is a single post
    if (!is_singular('recipe')) {
        return $content;
    }
    // continue filtering the post
    global $post, $wpdb;
    $recipe_data = get_post_meta($post->ID, 'recipe_data', true);
    $recipe_html = file_get_contents('recipe-template.php', true);
    $recipe_html = str_replace("RATE_I18N", __("Rating", "recipe"), $recipe_html);
    $recipe_html = str_replace('RECIPE_ID', $post->ID, $recipe_html);
    $recipe_html = str_replace('RECIPE_RATING', $recipe_data['rating'], $recipe_html);

    $user_IP   = $_SERVER['REMOTE_ADDR'];

    $rating_count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM `". $wpdb->prefix . "recipe_ratings`
        WHERE recipe_id=%d AND user_ip=%s",
        $post->ID, $user_IP
        ));

    if ($rating_count > 0 ) {
        $recipe_html = str_replace(
            'READONLY_PLACEHOLDER', 'data-rateit-READONLY="true"', $recipe_html
        );
    } else {
        $recipe_html = str_replace('READONLY_PLACEHOLDER', '', $recipe_html);
    }

    return $recipe_html . $content;
}
