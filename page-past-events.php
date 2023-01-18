<!-- In wp-admin we created a page called 'past events'. It will automatically use page.php for it's data. To override this default behavior, wp will look for a file called page-(url slug for the page created).php - in this example the url slug ends in /past-events. so we created page-past-events.php to render this page. -->

<!-- This is for the custom post type of event. If this file is not provided, php will use the archive.php file, which is typically for the blog. If you want the custom post type to have it's own archive screen, then you need to use archive-(custom archive type name, from the first parameter of register_post_type ). In this case it's event, so it must be archive-event.php -->

<?php
  get_header(); ?>

    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">
            Past Events
        </h1>
        <div class="page-banner__intro">
          <p>A recap of our past events.</p>
        </div>
      </div>
    </div>

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
      $pastEvents->the_post(); ?>
      <div class="event-summary">
          <a class="event-summary__date t-center" href="#">
                  <!-- the_field() can be used to pull data from a custom field. If you forgot the name of the custom field, you can go into wp-admin - Custom-Fields - and look for the Field Name. It's the one with no spaces.
                  Note that for this setup we used the Ymd setup, so it outputs something like 20170720 - You can adjust the return value in Custom Fields -  But that's why we are using DateTime, to help with conversion. AND we are using get_field so it's a return value instead of the_field, which echos to the screen.-->
                  <span class="event-summary__month"><?php 
                  // DateTime will default to the current date, unless you add a parameter.
                  $eventDate = new DateTime(get_field('event_date'));
                  echo $eventDate->format('M');//asks for the month. 
                  ?></span>
                  <span class="event-summary__day"><?php 
                  echo $eventDate->format('d');//asks for the day.  
                  ?></span>
                </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p>B<?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
          </div>
      </div>

    <?php } 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    // Note that wp pagination only works out of the box with wp queries that it makes on it's own, based on the current URL. (So it works on archive.php etc.) -- So more code is needed for a custom page to work with pagination. -- So we have to add a parameter to get it to work with custom pages.
    echo paginate_links(array(
        'total' => $pastEvents->max_num_pages //This will enable pagination links to work, but we still see the same post, no matter which page we click on, if we don't add the 'paged' key above.
    ));
    ?>
  </div>

  <?php get_footer();

?>