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
    while(have_posts()) {
      the_post(); ?>
      <div class="event-summary">
                <a class="event-summary__date t-center" href="#">
                  <span class="event-summary__month">Mar</span>
                  <span class="event-summary__day">25</span>
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
  </div>

  <?php get_footer();

?>