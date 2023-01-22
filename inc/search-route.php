<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,//GET will almost always work. But if you want to make sure it works on every web post out there, you can use WP_REST_SERVER::READABLE -- that substitutes it for a value of GET.
        'callback' => 'universitySearchResults' // This is a function we make below. -- When WP runs this function, WP sends some extra data along.
    ));//the first argument is the namespace you want to use. The goal is to make a custom REST API URL. The namespace of the core built-in wp namespace is wp -- aka /wp-json/wp/v2/professor (or whatever the post type is called.) Just choose a name that's relatively unique. -- technically v2 is also part of the namespace. (wp/v2 could be called the namespace.) -- the version number is used so you can make changes but still keep a copy of the previous version. -- so in this case we chose 'university/v1' -- since this will be the first version.
    // The second parameter is the route. In the example above, it's professor. -- It's the ending part of the URL.
    // Third parameter is to supply an array.
}

function universitySearchResults($data) {
    $mainQuery = new WP_Query(array(
        // 'post_type' => 'professor', // now the data for the 10 most recently created professors will be saved in this variable.
        'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'), // Instead of just one post type, we can supply an array to get multiple post types instead.
        's' => sanitize_text_field($data['term'])  //s is for search. This says to look inside the $data array for the key 'term'. -- it's like wp-json/university/v1/search?term=
    ));
    //In other instances we normally loop through a collection with a while loop to generate HTML. But in this case, we don't need to do that, we just need to return the data to the API.

    $results = array(
        'generalInfo' => array(),
        'professors' => array(),
        'programs' => array(),
        'events' => array(),
        'campuses' => array()
    );//We are using the loop to get only the data we want.

    while($mainQuery->have_posts()){
        $mainQuery->the_post(); // this gets all of the data for the post available.
        
        if(get_post_type() == 'post' || get_post_type() == 'page'){
            // The array we want to add onto in this case is generalInfo:
            array_push($results['generalInfo'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'postType' => get_post_type(),
                'authorName' => get_the_author()
            ));//takes two arguments. first - the array you want to add onto. Second is the data. So in this case, we are returning several mini-arrays, one for each post, that includes the title and permalink.
        }

        if (get_post_type() == 'professor') {
            array_push($results['professors'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')// 0 would get the first post. the second argument is the size you want to use.
            ));
        }

            if (get_post_type() == 'program') {
                $relatedCampuses = get_field('related_campus');
                // programs each have a campus, so for each program that is being looped over, we are going to see if there's a related campus field. If so, we are doing a foreach in case there's multiple campuses listed.
                if($relatedCampuses) {
                    foreach($relatedCampuses as $campus) { 
                        array_push($results['campuses'], array(
                            'title' => get_the_title($campus),//we have to pass in $campus becuase otherwise it will pull the program details, instead of the $campus details.
                            'permalink' => get_the_permalink($campus)
                        ));//first is array you want to add onto. Second is what you want to add on. 
                    }
                }

                

            array_push($results['programs'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'id' => get_the_id()
            ));
            }

            if (get_post_type() == 'campus') {
            array_push($results['campuses'], array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink()
            ));
            }

            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }

                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
            }
    }

    if ($results['programs']){ // only if programs metaquery isn't empty.
        $programsMetaQuery = array('relation' => 'OR'); // now we loop through the results program array and for each, we will generate a filter array and add it onto this array.

        foreach($results['programs'] as $item) {
            array_push($programsMetaQuery, array(
                    'key' => 'related_programs',//name of the advanced custom field to look within
                    'compare' => 'LIKE',
                    'value' => '"' . $item['id'] . '"'
                    // '"97"'//we need double quotes inside the single quotes.
                ));//second parameter is what you want to add onto the array.
        }
        // PROFESSOR AND EVENT QUERY:
        $programRelationshipQuery = new WP_Query(array(
            'post_type' => array('professor', 'event'),
            'meta_query' => $programsMetaQuery 
        ));

        while($programRelationshipQuery->have_posts()){
            $programRelationshipQuery->the_post();

            if (get_post_type() == 'event') {
                $eventDate = new DateTime(get_field('event_date'));
                $description = null;
                if (has_excerpt()) {
                    $description = get_the_excerpt();
                } else {
                    $description = wp_trim_words(get_the_content(), 18);
                }

                array_push($results['events'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'month' => $eventDate->format('M'),
                    'day' => $eventDate->format('d'),
                    'description' => $description
                ));
            }

            if (get_post_type() == 'professor') {
                array_push($results['professors'], array(
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'image' => get_the_post_thumbnail_url(0, 'professorLandscape')// 0 would get the first post. the second argument is the size you want to use.
                ));
            }
        }

        $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));//First argument is an array that we want to work with, second is to tell the function to look into subarrays. -- Becuase we have two queries, one that searchs on a keyword and one that searches on a relationship -- so this is to remove any possible duplicates. 
        $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }

    return $results;//$professors->posts is the data we would usually be looping through -- it's an array of objects.
}

// now, you can go into the browser to: http://localhost:3000/wp-json/university/v1/search OR -- this worked for me:
// http://fictional-university-2.local//wp-json/university/v1/search

// PHP will convert data structures to JSON for us.