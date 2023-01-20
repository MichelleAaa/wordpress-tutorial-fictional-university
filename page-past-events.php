<!-- In wp-admin we created a page called 'past events'. It will automatically use page.php for it's data. To override this default behavior, wp will look for a file called page-(url slug for the page created).php - in this example the url slug ends in /past-events. so we created page-past-events.php to render this page. -->

<!-- This is for the custom post type of event. If this file is not provided, php will use the archive.php file, which is typically for the blog. If you want the custom post type to have it's own archive screen, then you need to use archive-(custom archive type name, from the first parameter of register_post_type ). In this case it's event, so it must be archive-event.php -->

<?php
  get_header(); 
  pageBanner(array(
    'title' => 'Past Events',
    'subtitle' => 'A recap of our past events.'
  ));
  ?>

  <div class="container container--narrow page-section">
    <!-- Blog post while loop -->
    <?php
  //This is a page, not a post file. So we need to create a custom query becuase there's no wp auto query with a page.

  $today = date('Ymd');//Will return today's date in ymd format. 20170628 - that's an example of the format -- and it's the same format used by our event_date field.
    // Again, WP_Query is the blueprint that's provided by wordpress. We just tell the class what data we want to query from the database.
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1), //This tells the custom query which page it is on. We basically need to get the number from the end of the URL. -- get_query_var() can be used to get information about the current URL. In this case, we need the url number. -- Note that the first page doesn't have a page number, when it's number 1. So , 1 is the fallback. It says to consider it number 1 if there's no paged url number, which means we are on the first page of the results.
        // 'posts_per_page' => 1, // -1 would give us all posts that meet the conditions. -- WP default is 10 so we will leave it as that. -- You can test pagination with this though, by selecting 1 and making sure you have at least two applicable posts.
        'post_type' => 'event', // The post type is going to target the custom post type of 'event'
        'meta_key' => 'event_date', //This is required when using 'meta_value below.
        'orderby' => 'meta_value_num',//By default, it's set to 'post_date'. 'title' would order alphabetically by title., 'rand' randomizes. -- 'meta_value' is the custom or original data set on the post. So we are saying we want to sort by a custom field. (note that event_date is a custom field.) -- When you add on 'meta_value' you need the 'meta_key' value to enter the name of the custom field. -- In this case though, we need to use 'meta_value_num' becuase we need to sort by a number.
        'order' => 'ASC', //by default it's set to 'DESC'. 
        'meta_query' => array(
        array( // this will make sort of a sentence in a way. Only give us posts where the key is comparable to the value listed.
            // So the below says -- only find event posts with an event_date that are less than the current date. (aka in the past)
            'key' => 'event_date',
            'compare' => '<',
            'value' => $today,
            'type' => 'numeric' // we are comparing numbers.
        )
        )//we could have multiple restrictions in the array by adding multiple inner arrays. We only need one inner array to check for one thing. 
    ));


    while($pastEvents->have_posts()) {
      $pastEvents->the_post(); 
        // This file is located in template-parts (folder), event.php file. We are pulling in the static content from there:
        // get_template_part() takes two arguments. The first one is a pointer towards the new file. -- note that the file is called event.php, but you just write event. -- The second argument will look inside the folder for the specialty name. 
        get_template_part('template-parts/event', 'excerpt');
    } 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    // Note that wp pagination only works out of the box with wp queries that it makes on it's own, based on the current URL. (So it works on archive.php etc.) -- So more code is needed for a custom page to work with pagination. -- So we have to add a parameter to get it to work with custom pages.
    echo paginate_links(array(
        'total' => $pastEvents->max_num_pages //This will enable pagination links to work, but we still see the same post, no matter which page we click on, if we don't add the 'paged' key above.
    ));
    ?>
  </div>

  <?php get_footer();

?>