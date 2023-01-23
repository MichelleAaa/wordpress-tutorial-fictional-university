<?php
  get_header(); 
  pageBanner(array(
    'title' => 'Search Results',
    'subtitle' => 'You searched for &ldquo;' . esc_html(get_search_query()) .  '&rdquo;'
  )); // get_search_query() -- will pull the data from the url (aka the search term that was submitted through the form's get request.) 
  // note that get_search_query() excapes JS, so if someone entered <script> etc it won't run as code. You could override this feature by putting false as a parameter: get_search_query(false) -- but that's not a good idea. -- for added security, we can also use esc_html().
  ?>

  <div class="container container--narrow page-section">
    <!-- Blog post while loop -->
    <?php
    if(have_posts()){// If the search has results:
        while(have_posts()) {
        the_post(); 
        
        if(get_post_type() == 'event'){
        get_template_part('template-parts/event', 'excerpt');//first - point towards another file that you want to pull in and use. second --is the rest of the file name, after the dash -  so in this case the file is called event-excerpt.php 
        } else {
        get_template_part("template-parts/content", get_post_type());//this will pull in all the applicable files that start with content that are applicable to the search results.
            }
        }
    } else { 
        echo '<h2 class="headline headline--small-plus">No results match that search.</h2>';
    }
    
    ?>
    
    
    <?php 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    echo paginate_links();

    get_search_form(); // if you have a file called searchform.php, this will pull in it's content.
    ?>
  </div>

  <?php get_footer();

?>