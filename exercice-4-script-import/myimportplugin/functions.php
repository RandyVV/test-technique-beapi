<?php

/**
 * Importe des posts à partir d'un fichier JSON
 * @param string $path Chemin du fichier JSON
 */
function myimportplugin_import($path)
{
    $json = file_get_contents($path);

    $posts = json_decode($json, true);

    foreach ($posts as $post) {
        $author_id = myimportplugin_get_author_id_by_display_name($post['name']);

        if ($author_id === null) {
            // on n'importe pas les posts dont ont ne reconnaît pas l'auteur
            // on pourrait aussi créer un utilisateur avec wp_insert_user() à la volée
            continue;
        }

        $post_id = wp_insert_post([
            'post_type' => 'post',
            'post_author' => $author_id,
            'post_content' => $post['content'],
        ]);

        myimportplugin_import_attachment($post['picture'], $post_id);
    }
}

/**
 * Recherche l'ID d'un auteur à partir de son nom d'affichage
 * @param string $display_name Nom d'affichage de l'auteur
 * @return int|null ID de l'auteur ou null si non trouvé
 */
function myimportplugin_get_author_id_by_display_name($display_name)
{
    $args = [
        'display_name' => $display_name,
    ];

    $query = new WP_User_Query($args);

    $results = $query->get_results();

    if (!empty($results)) {
        return $results[0]->ID;
    }

    return null;
}

/**
 * Importe l'image associée à un post
 * @param string $url URL de l'image
 * @param int $post_id ID du post auquel l'image est associée
 * @return int ID de l'image importée
 */
function myimportplugin_import_attachment($url, $post_id)
{

    $upload_dir = wp_upload_dir();

    $image_data = file_get_contents($url);

    $filename = basename($url);
    $file = $upload_dir['path'] . '/' . $filename;

    file_put_contents($file, $image_data);

    $filetype = mime_content_type($filename);

    $attachment = [
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    ];

    $attach_id = wp_insert_attachment($attachment, $file, $post_id);
    
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}