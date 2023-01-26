<?php 

    add_action('rest_api_init', 'universityLikeRoutes'); // this can be used to add a new field to a route or a new custom route.

    function universityLikeRoutes() {
    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'POST',
        'callback' => 'createLike'
    ));

    register_rest_route('university/v1', 'manageLike', array(
        'methods' => 'DELETE',
        'callback' => 'deleteLike'
    ));
}

function createLike($data) {
    // current_user_can('publish_note') -- Check if a user can do something -- and put the name of the thing as a parameter.
    if (is_user_logged_in()) {
        $professor = sanitize_text_field($data['professorId']); // this pulls the data in from like.js, 

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(), // the query will only contain results if the current user is the author of the like.
            'post_type' => 'like',
            'meta_query' => array(
                array(
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $professor
                )
            )//we are using a metaquery becuase we only want to pull it in when the liked ID matches the ID of the post.
            ));

        if ($existQuery->found_posts == 0  && get_post_type($professor) == 'professor') {
            // Create new like post
                return wp_insert_post(array(  
                'post_type' => 'like',
                'post_status' => 'publish', // this is needed for wp to consider it a finalized post instead of a draft.
                'post_title' => 'Our PHP Create Post Test',
                // 'post_content' => 'Hello world 123' -- we don't need this one and turned off the 'editor' by not including it in mu-plugins
                'meta_input' => array(
                    'liked_professor_id' => $professor
                )
            ));//This lets us create a new post. In the () we provide an array that describes the new post we want to create.
        } else {
            die("Invalid professor id.");
        }

        

    } else {
        die("Only logged in users can create a like.");
    }
}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']);
    
    if (get_current_user_id() == get_post_field('post_author', $likeId) && get_post_type($likeId) == 'like') {
        wp_delete_post($likeId, true);//second argument is if you watn to send it to the trash first. true skips the trash step and fully deletes it.
        return "Congrats, like deleted.";
    } else {
        die("You do not have permission to delete that.");
    }
}

?>