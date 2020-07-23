<?php

function r_filter_recipe_content($content)
{
    // check if this is a single post
    if (!is_singular('recipe')) {
        return $content;
    }
    // continue filtering the post
    global $post;
    $recipe_data = get_post_meta($post->ID, 'recipe_data', true);
    $recipe_html = file_get_contents('recipe-template.php', true);
    $recipe_html = str_replace("RATE_I18N", __("Rating", "recipe"), $recipe_html);
    $recipe_html = str_replace('RECIPE_ID', $post->ID, $recipe_html);

    return $recipe_html . $content;
}
