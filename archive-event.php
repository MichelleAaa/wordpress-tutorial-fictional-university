<!-- This is for the custom post type of event. If this file is not provided, php will use the archive.php file, which is typically for the blog. If you want the custom post type to have it's own archive screen, then you need to use archive-(custom archive type name, from the first parameter of register_post_type ). In this case it's event, so it must be archive-event.php -->

<?php
  get_header(); ?>

    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">
            All Events
        </h1>
        <div class="page-banner__intro">
          <p>See what is going on in our world.</p>
        </div>
      </div>
    </div>

  <div class="container container--narrow page-section">
    <!-- Blog post while loop -->
    <?php
  //In front-page.php we created a custom query to sort/order the event posts. On that page we had to make a custom query becuase the home page doesn't naturally query data from the 'event' post type. This page, archive-event.php, on the other hand, pulls from the 'event' post type as it is. So it's not necessary to replicate the same code from front-page.php. (Based on the URL, wP is going to make a query to 'event' post type anyways.) We just want to change and tweek the query in subtle ways, instead of need an entire custom query.
  // Instead of making a custom query, we are going to put the code to customize the default wp query in functions.php


    while(have_posts()) {
      the_post(); ?>
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
    echo paginate_links();
    ?>
    <hr class="section-break">
    <p>Looking for a recap of past events? <a href="<?php echo site_url('/past-events'); ?>">Check out our past events archive.</a></p>
  </div>

  <?php get_footer();

?>