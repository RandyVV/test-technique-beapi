<?php

/**
 * Récupère les posts les plus populaires (les plus consultés)
 * @return WP_Query
 */
function myviewscounter_get_most_viewed_posts()
{
    $args = [
        'order' => 'DESC',
        'orderby' => 'meta_value_num',
        'meta_key' => 'views_count',
    ];

    $query = new WP_Query($args);

    return $query;
}

/**
 * Incrémente le compteur de vues d'un post
 */
function myviewscounter_increment_view()
{
    $post_id = get_the_ID();

    $views_count = get_post_meta($post_id, 'views_count', true);

    if ($views_count == '') {
        $views_count = 0;
    }

    $views_count++;

    update_post_meta($post_id, 'views_count', $views_count);
}

add_action('the_content', 'myviewscounter_increment_view');

