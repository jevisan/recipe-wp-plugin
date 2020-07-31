<?php
function r_dashboard_widgets()
{
    wp_add_dashboard_widget(
        'r_latest_recipe_rating_widget',    // slug
        'Latest Recipe Ratings',            // title
        'r_latest_recipe_rating_display'    // callback
    );
}

function r_latest_recipe_rating_display()
{
    global $wpdb;

    $latest_ratings = $wpdb->get_results(
        "SELECT * FROM `". $wpdb->prefix . "` ORDER BY `ID` DESC LIMIT 5"
    );

    echo '<ul>';
    foreach ($latest_ratings as $rating) {
        $title = get_the_title($rating->recipe_id);
        $permalinkg = get_the_permalink($rating->recipe_id);
        ?>
        <li>
            <a href="<?php echo $permalinkg; ?>"><?php echo $title; ?></a>
            Received a rating of <?php echo $rating->rating; ?>
        </li>
        <?php
    }

    echo '</ul>';
}
