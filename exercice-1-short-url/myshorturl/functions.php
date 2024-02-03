<?php

/**
 * Retourne la configuration du plugin
 * @return array
 */
function myshorturl_get_config()
{
    return [
        'tinyurl_token' => 'FRrIXX6uQR1OQB5CnHujmhHWRtT8f1lLYuJhUeX32uroFvHrngdvqhSALv2z',
        'tinyurl_api_create' => 'https://api.tinyurl.com/create'
    ];
}

/**
 * Récupère un short URL pour une URL donnée
 * @param string $url
 * @return string
 */
function myshorturl_fetch_shorturl($url)
{
    $config = myshorturl_get_config();

    $api_response = wp_remote_post(
        $config['tinyurl_api_create'],
        [
            'headers' => [
                'Authorization' => 'Bearer ' . $config['tinyurl_token'],
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'url' => $url,
                'domain' => 'tinyurl.com',
                'expires_at' => null,
            ])
        ]
    );

    $response_body = json_decode(wp_remote_retrieve_body($api_response), true);

    $short_url = $response_body['data']['tiny_url'];

    return $short_url;
}

/**
 * Ajoute la métadonnée shorturl à un post lors de sa publication
 * @param int $post_id
 */
function myshorturl_add_shorturl_metadata($post_id)
{
    $post_url = get_post_permalink($post_id);

    $short_url = myshorturl_fetch_shorturl($post_url);

    add_metadata(
        'post',
        $post_id,
        'shorturl',
        $short_url,
        true
    );
}
add_action('publish_post', 'myshorturl_add_shorturl_metadata');
