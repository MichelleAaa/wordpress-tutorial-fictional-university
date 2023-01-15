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
    // This is used to enable a feature for your theme.
    add_theme_support('title-tag');
}

// The first is when the action should take place (these are WP provided text strings). The second parameter is a made-up function.
add_action('after_setup_theme', 'university_features' );

?>