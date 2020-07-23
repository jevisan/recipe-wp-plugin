<?php

function r_rate_recipe()
{
    global $wpdb;

    $output     = ['status' => 1];
    $post_ID    = absint($_POST['rid']);
    $rating     = round($_POST['rating'], 1);
    $user_IP   = $_SERVER['REMOTE_ADDR'];

    $rating_count = $wpdb->get_var(
        "SELECT COUNT(*) FROM `". $wpdb->prefix . "recipe_ratings`
        WHERE recipe_id='". $post_ID ."' AND user_ip='". $user_IP . "'"
    );

    // Insert Rating into database
    if ($rating_count > 0) {
        wp_send_json($output);
    }

    $wpdb->insert(
        $wpdb->prefix . 'recipe_ratings', // name of the table
        [
            'recipe_id' => $post_ID,      // data to insert
            'rating'    => $rating,
            'user_ip'   => $user_IP
        ],
        ['%d', '%f', '%s']              // for security reasons,
                                        // third param checks the type of each variable in array
    );

    // Update Recipe Metadata
    $recipe_data    = get_post_meta($post_ID, 'recipe_data', true);
    $recipe_data['rating_count']++;
    $recipe_data['rating'] = round($wpdb->get_var(
        "SELECT AVG(`rating`) FROM `". $wpdb->prefix . "recipe_ratings`
        WHERE recipe_id='". $post_ID ."' AND user_ip='". $user_IP . "'"
    ), 1);

    update_post_meta($post_ID, 'recipe_data', $recipe_data);

    $output['status'] = 2;      // status = 1 if insert was a failure, 2 if success
    wp_send_json($output);      // sends response as json & kills the script
}
