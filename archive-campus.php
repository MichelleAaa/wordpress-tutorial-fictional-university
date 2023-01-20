<!-- This was adapted from archive-event.php and the notes are not all up to date. -->
<!-- This is for the custom post type of event. If this file is not provided, php will use the archive.php file, which is typically for the blog. If you want the custom post type to have it's own archive screen, then you need to use archive-(custom archive type name, from the first parameter of register_post_type ). In this case it's event, so it must be archive-event.php -->

<?php
  get_header(); 
  pageBanner(array(
    'title' => 'Our Campuses',
    'subtitle' => 'We have several conveniently located campuses.'
  ));
  ?>

  <div class="container container--narrow page-section">
    <!-- This is css for displaying the google map: -->
  <!-- <div class="acf-map"> -->
  <div class="">

    <!-- Blog post while loop -->
    <!-- <ul class="link-list min-list"> -->
    <?php
  //In front-page.php we created a custom query to sort/order the event posts. On that page we had to make a custom query becuase the home page doesn't naturally query data from the 'event' post type. This page, archive-event.php, on the other hand, pulls from the 'event' post type as it is. So it's not necessary to replicate the same code from front-page.php. (Based on the URL, wP is going to make a query to 'event' post type anyways.) We just want to change and tweek the query in subtle ways, instead of need an entire custom query.
  // Instead of making a custom query, we are going to put the code to customize the default wp query in functions.php

    while(have_posts()) {
      the_post(); 
      // $mapLocation = get_field('map_location');
      
      ?>
      <!-- This relies on setting up a google maps API, and then setting up a custom field of google map for the campus post type. Then you go into each campus post and record the campus location in the map that shows up. (You have to list the api key in functions.php.) -->
    <!-- //   $mapLocation = get_field('map_location'); -->
    <!-- //   echo $mapLocation['lng']; -- You could access the longtitude of the location. -->
      <!-- ?></a></li> -->
    
      <!-- This could be used to display the map, but I dont' have an api key: -->
      <!-- <div class="marker" data-lat="<?php $mapLocation['lat'] ?>" data-lng="<?php $mapLocation['lng'] ?>">
        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php echo $mapLocation['address']; ?>
    </div> -->

    <!-- Temporary display without API key: -->

         <div>
     <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

    <?php } 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    echo paginate_links();
    ?>
    <!-- </ul> -->
    </div>
  </div>

  <?php get_footer();

?>