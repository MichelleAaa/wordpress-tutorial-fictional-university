<!-- The functions.php file is different from the rest of the files. The other files are template files, used to load the HTML on the website. This file however, is where we have a conversation with the wordpress system itself. It works behind the scenes. -->

<?php

function university_files() {

    // Load a JavaScript file -- 
    // The first parameter is a made-up name. 
    // The second parameter is where we point towards the file we want to load. 
    // The third parameter is to tell WP if there's any dependencies. In this case, we are relying on jquery, so it goes into the array. (If you have no dependencies, then you could enter NULL for the third value.)
    // The fourth parameter is the version number, and this we can make up as a string value.
    // The fifth value is whether you want WP to load this file right before the closing body tag. True or False. -- We entered true becuase we want this to load at the end of the body and not in the head section. (It's better for overall performance.)
    wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);

    //  Link For fontawesome - Since we aren't loading a file locally, only providing a link, we don't use a function for the second argument. We just list the web address.
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    // Note that this is not the full web address. This is the original HTML version:
    //   <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous" />
    // NOTE that we didn't copy over the https: at the front of the link.

    // Add custom google fonts:
    // Again, we start the link with the //.
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');


    // wp_enqueue_style takes two arguments. The first is a nickname for our main stylesheet (style.css). So here we made up 'university_main_styles'. The second is to point towards the file. get_stylesheet_uri() will pull the main style.css if we don't pass anything in as a parameter.
    // If you wanted to add an additional css file, you would need to duplicate the below line and add in the details of the new file in the parameters of get_stylesheet_uri().
//   wp_enqueue_style('university_main_styles', get_stylesheet_uri());
//   Note that we don't have to include all of our css in style.css, but we will always need to leave that file available since it's used by WP.

//   Note that we no longer want to load just style.css, we instead want to pull in code from the build folder.
  wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css'));
//   This loads a second css file. Note we created a new name in the first parameter and are referencing the new style sheet in the second's parameter.
  wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));
}

// add_action requires 2 parameters. WP allows us to give it instructions using this method. 'wp_enqueue_scripts' 
// The second parameter is a function that we define (we make up the name and ensure the function is above the call.)
// Note that we don't add the parenthesis with the function, becuase we don't want to call the function right now. We are just giving WP the function name so it can run it at the precise moment it should be added.
add_action('wp_enqueue_scripts', 'university_files');

function university_features() {
  // NOTE THAT THE THREE REGISTER_NAV_MENU()'S CAN BE REMOVED BECUASE WE ONLY TESTED DYNAMIC MENUS IN WP, BUT ARE BACK TO USING THE HARDCODED VERSIONS FOR THE TIME BEING.
  // First argument is a name we make up. The second text string is the text that will show up in the wp-admin panel. 
  // register_nav_menu('headerMenuLocation',"Header Menu Location");  
  // This one will be for the footer1:
  // register_nav_menu('footerLocationOne',"Footer Location One");  
  // This one will be for the footer2:
  // register_nav_menu('footerLocationTwo',"Footer Location Two");  

  // This is used to enable a feature for your theme.
    add_theme_support('title-tag');
}

// The first is when the action should take place (these are WP provided text strings). The second parameter is a made-up function.
add_action('after_setup_theme', 'university_features' );


// NOTE: the below has been moved into mu-plugins in it's own file. mu-plugins always must be loaded by wp, so it's safe to move the code there, however, you could leave it in functions.php if you wanted to:
/*
function university_post_types(){
  // The first argument is the name of the custom post type we want to create, in this case, 'event'. The second argument is an associative array of different options to describe your post type. 
  register_post_type('event', array(
    'show_in_rest' => true, //You can add this if you are adding excerpt to the supports option below. This show_in_rest will let us see the custom post type of 'event' that we are adding in the wp-admin screen with the modern block editor (when editing a post) instead of the older editor layout. -- rest api makes raw api data avialable for JS to fetch, so we need it enabled for custom post types. (Note that for this to work, you must add 'editor' below, or else you will go back to the standard editor.)
    'supports' => array('title', 'editor', 'excerpt', 'custom-fields'), //If you want the new post type to support custom excerpts in the wp-admin - post type setup, you have to add this. It's optional.(By default new post types support title and editor. It's up to us to decide if we want the rest.) -- Note for wp-admin - if you don't see excerpt, click screen options while editing the post, and select the Excert checkbox.
    // custom-fields is optional. It's if you need extra fields to be added for that post type, such as an event date for the event post type.
    'rewrite' => array('slug' => 'events'), // This will change the keyword that's displayed in the URL slug. (If you don't include it, it will use the slug for the post type, aka, the text entered in register_post_type as the first parameter.)
    'public' => true, // This means it's visible to visitors of the website.
    'has_archive' => true, // In order for the new post type to have an archive, we have to add this line.  – The archive can typically be accessed with the site url + /(new post type – in this case “/event/”
    //Note that the archive will by default be powered by archive.php. If you want a new theme file that's only responsible for the event archive, you would need to create a new file in the theme's folder with the rest of the .php files. The name of the file must be archive-(name of custom post type).php. In this case, it's archive-event.php
    'menu_icon' => 'dashicons-calendar', //in wp-admin, this is the menu icon image on the far left of the name of the tab. It's optional to set this value. It will default to the same icon as posts if not added. -- Google wordpress dashicons for options.
    'labels' => array(
      'name' => "Events", // wp-admin will show a new post (with the URL showing the name 'event' if we don't add this option.) This option will update wp-admin to show 'Events' on the sidebar.
      'add_new_item' => "Add New Event",
      //When you create a new event in wp-admin, in the edit box it says "Add New Post". This line is needed to update the text. -- Now it will say -- Add New Event
        'edit_item' => 'Edit Event',
    //   If you try to edit an existing event, the headline in wp-admin will read 'Edit Post'. This line is needed to update the text to -- Edit Event
        'all_items' => "All Events",
    // When you hover over the Events tab in wp-admin, it just sayd Events, not All Events. This will update it to say -- "All Events'
    'singular_name' => 'Event'//
    // There are tons of other labels you can change, but these are the most commonly used. 
    ) 
  ));
}
// The first parameter is the event hook, in this case, 'init'. The second argument is the name of a function we will create.
add_action('init', 'university_post_types');
*/

// Note that anytime you want to adjust wp default queries, you can add a section to the below function, including a new if statement.
function university_adjust_queries($query) {
// When WP calls a function it gives reference to the query that's being called. So when WP passed on the $query object, we can manipulate the object within the function. 

//-- This code would even effect the wp-admin panel, how many posts it shows us. We can't leave this alone as it's too broad, we only want to impact the archive-event page. So the if statement is required to limit this code. -- We only want the code to run on the front-end, not the back end wp-admin panel. -- And we only want the code to run when we are on the archive-event.php file.
//  $query->is_main_query() will only evaluate to true if the query that is passed in is a main query for the page -- such as the main archive-event.php query that wp runs -- not a custom query on that page. (in case we wanted to make one.)
  if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
    //$query->set('posts_per_page', '1');// if you entered '1' as the second parameter, then it would only output one event per page.
    // set() is built-in. First parameter is the query key we want to change. Second argument is the value you want to use.
    // Note that these values are similar to what we worked on in front-page.php, but it's different because that was a custom query, while this is just modifying the automatic wp query.
    $today = date('Ymd');//Will return today's date in ymd format. 20170628 - that's an example of the format -- and it's the same format used by our event_date field.
    $query->set('meta_key', 'event_date'); //This is required when using 'meta_value below.
    $query->set('orderby', 'meta_value_num'); //By default, it's set to 'post_date'. 'title' would order alphabetically by title., 'rand' randomizes. -- 'meta_value' is the custom or original data set on the post. So we are saying we want to sort by a custom field. (note that event_date is a custom field.) -- When you add on 'meta_value' you need the 'meta_key' value to enter the name of the custom field. -- In this case though, we need to use 'meta_value_num' becuase we need to sort by a number.
    $query->set('order', 'ASC'); //by default it's set to 'DESC'. 
    $query->set('meta_query', array(
                array( // this will make sort of a sentence in a way. Only give us posts where the key is comparable to the value listed.
                  // So the below says -- only find items with an event_date that is greater than or equal to the current date.
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'numeric' // we are comparing numbers.
                )
              )
            );
  }

  // Adjust the wp default query for archive-program.php:
// If not in the admin section (aka only for the front-end queries), is post type program, and it's the main query.
    if( !is_admin() && is_post_type_archive('program') && is_main_query()){
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', -1);//this means even if we have 100 programs, they will all be listed at once.
    }
}

// The first is the timing (Wp defined options). -- 'pre_get_posts' means to call the function in the second parameter right before you get the posts. The second parameter is a made-up function name.
  add_action('pre_get_posts', 'university_adjust_queries');

?>