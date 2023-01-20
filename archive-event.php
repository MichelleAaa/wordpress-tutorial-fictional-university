<!-- This is for the custom post type of event. If this file is not provided, php will use the archive.php file, which is typically for the blog. If you want the custom post type to have it's own archive screen, then you need to use archive-(custom archive type name, from the first parameter of register_post_type ). In this case it's event, so it must be archive-event.php -->

<?php
  get_header();
  pageBanner(array(
    'title' => 'All Events',
    'subtitle' => 'See what is going on in our world.'
  ));
  ?>

  <div class="container container--narrow page-section">
    <!-- Blog post while loop -->
    <?php
  //In front-page.php we created a custom query to sort/order the event posts. On that page we had to make a custom query becuase the home page doesn't naturally query data from the 'event' post type. This page, archive-event.php, on the other hand, pulls from the 'event' post type as it is. So it's not necessary to replicate the same code from front-page.php. (Based on the URL, wP is going to make a query to 'event' post type anyways.) We just want to change and tweek the query in subtle ways, instead of need an entire custom query.
  // Instead of making a custom query, we are going to put the code to customize the default wp query in functions.php


    while(have_posts()) {
      the_post(); 
      // This file is located in template-parts (folder), event.php file. We are pulling in the static content from there:
      // get_template_part() takes two arguments. The first one is a pointer towards the new file. -- note that the file is called event.php, but you just write event. -- The second argument will look inside the folder for the specialty name. 
      get_template_part('template-parts/event', 'excerpt');

    } 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Check out our past events archive.</a></p>
  </div>

  <?php get_footer();

?>