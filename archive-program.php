<!-- This was adapted from archive-event.php and the notes are not all up to date. -->
<!-- This is for the custom post type of event. If this file is not provided, php will use the archive.php file, which is typically for the blog. If you want the custom post type to have it's own archive screen, then you need to use archive-(custom archive type name, from the first parameter of register_post_type ). In this case it's event, so it must be archive-event.php -->

<?php
  get_header(); ?>

    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">
            All Programs
        </h1>
        <div class="page-banner__intro">
          <p>There is something for everyone. Have a look around.</p>
        </div>
      </div>
    </div>

  <div class="container container--narrow page-section">
    <!-- Blog post while loop -->
    <ul class="link-list min-list">
    <?php
  //In front-page.php we created a custom query to sort/order the event posts. On that page we had to make a custom query becuase the home page doesn't naturally query data from the 'event' post type. This page, archive-event.php, on the other hand, pulls from the 'event' post type as it is. So it's not necessary to replicate the same code from front-page.php. (Based on the URL, wP is going to make a query to 'event' post type anyways.) We just want to change and tweek the query in subtle ways, instead of need an entire custom query.
  // Instead of making a custom query, we are going to put the code to customize the default wp query in functions.php

    while(have_posts()) {
      the_post(); ?>
      <!-- This code will repeat once for each program. -->
      <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>

    <?php } 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    echo paginate_links();
    ?>
    </ul>
    
  </div>

  <?php get_footer();

?>