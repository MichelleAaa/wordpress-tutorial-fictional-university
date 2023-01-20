<?php 
// get_header will pull in the contents of header.php
get_header(); 
?>

<div class="page-banner">
    <!-- For image links, we could get the entire link by going to wp-content/..., but there's an easier way.
Original: style="background-image: url(images/library-hero.jpg)
Updated to: style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg');
?>)
Note that get_theme_file_uri() will generate the entire link to the folder/file for us so we don't have to list out wp-content/...
-->
      <div class="page-banner__bg-image" 
      style="background-image: url(<?php echo get_theme_file_uri('images/library-hero.jpg');?>)"></div>
      <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome!</h1>
        <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
        <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
        <a href="<?php echo get_post_type_archive_link('program') ?>" class="btn btn--large btn--blue">Find Your Major</a>
      </div>
    </div>

    <div class="full-width-split group">
      <div class="full-width-split__one">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
          <?php
          $today = date('Ymd');//Will return today's date in ymd format. 20170628 - that's an example of the format -- and it's the same format used by our event_date field.
            // Again, WP_Query is the blueprint that's provided by wordpress. We just tell the class what data we want to query from the database.
            $homepageEvents = new WP_Query(array(
              'posts_per_page' => 2, // -1 would give us all posts that meet the conditions.
              'post_type' => 'event', // The post type is going to target the custom post type of 'event'
              'meta_key' => 'event_date', //This is required when using 'meta_value below.
              'orderby' => 'meta_value_num',//By default, it's set to 'post_date'. 'title' would order alphabetically by title., 'rand' randomizes. -- 'meta_value' is the custom or original data set on the post. So we are saying we want to sort by a custom field. (note that event_date is a custom field.) -- When you add on 'meta_value' you need the 'meta_key' value to enter the name of the custom field. -- In this case though, we need to use 'meta_value_num' becuase we need to sort by a number.
              'order' => 'ASC', //by default it's set to 'DESC'. 
              'meta_query' => array(
                array( // this will make sort of a sentence in a way. Only give us posts where the key is comparable to the value listed.
                  // So the below says -- only find items with an event_date that is greater than or equal to the current date.
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                  'type' => 'numeric' // we are comparing numbers.
                )
              )//we could have multiple restrictions in the array by adding multiple inner arrays. We only need one inner array to check for one thing. -- only check for posts that are today's date or a future date, no past dates. (We only want to show upcoming events.)
            ));
            
            while($homepageEvents->have_posts()) {
              //We have to look within the object for the built-in the_post() function as well as the above have_posts() function.
              $homepageEvents->the_post(); 
              // This file is located in template-parts (folder), event.php file. We are pulling in the static content from there:
              // get_template_part() takes two arguments. The first one is a pointer towards the new file. -- note that the file is called event.php, but you just write event. -- The second argument will look inside the folder for the specialty name. 
              get_template_part('template-parts/event', 'excerpt');
            }
          ?>

          <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link('event'); ?>" class="btn btn--blue">View All Events</a></p>
        </div>
      </div>
      <div class="full-width-split__two">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
          <?php  
          // Custom Query:
          // WP out of the box has a class called WP_Query. We are creating a new object with that as the blueprint.
          $homepagePosts = new WP_Query(array(
            "posts_per_page" => 2, //By default it will output 10 posts.
            //"category_name" => 'awards' // If you wanted to pull only the first 2 posts from the awards category.
            // 'post_type' => 'page' -- This would query all of the pages.
          )); // The two most recent blog posts will be contained in this variable now, as well as some functions/methods.
          
          // By default, have_posts() and the_post() are tied to the default automatic query that WP makes on it's own. The one that's tied to the current URL. So instead of using those functions, for custom queries we need to add the object name we just created above and reference the wp generated functions that were already in created in that object from the wp managed class.
            while($homepagePosts->have_posts()){
              $homepagePosts->the_post(); ?>
              <!-- <li><?php the_title(); ?></li> -->
            <div class="event-summary">
            <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
              <!-- the_time() - "M" for Month, "d" for day,  -->
              <span class="event-summary__month"><?php the_time('M'); ?></span>
              <span class="event-summary__day"><?php the_time('d'); ?></span>
            </a>
            <div class="event-summary__content">
              <!-- wp_trim_words - First parameter is the content you want trimmed, second is how many words you want it limited to -->
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <!-- When you use the_excerpt(), if a post has a custom excerpt in the wp-admin page, it will show up. For posts that don't have it, it will pull in the first 55 words of the post as a fallback.  Instead, you could set up a custom fallback using an if statement:  
              has_excerpt() will return true only if there's a custom excerpt in the post's wp-admin setup.-->
              <p> <?php if (has_excerpt()) {
                  //the_excerpt(); // the_excerpt() handles outputting the content to the page for us, which comes with some extra white space. If you prefer to style it different, then use the following instead:
                  echo get_the_excerpt();
              } else {
                echo wp_trim_words(get_the_content(), 18);
              } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
            </div>
          </div>
            <?php }
            // With custom queries, you should always call the below at the end of the query. It resets global variables back to the original state. (Sometimes if you are at the bottom of a template file it's not needed, but it's just good habit to do it anyways just in case.)
            wp_reset_postdata();
          ?>

          <p class="t-center no-margin"><a href="<?php echo site_url('/blog') ?>" class="btn btn--yellow">View All Blog Posts</a></p>
        </div>
      </div>
    </div>

    <div class="hero-slider">
      <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
          <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bus.jpg');?>)">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Free Transportation</h2>
                <p class="t-center">All students have free unlimited bus fare.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/apples.jpg');?>) ">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">An Apple a Day</h2>
                <p class="t-center">Our dentistry program recommends eating apples.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="hero-slider__slide" style="background-image: url(<?php echo get_theme_file_uri('images/bread.jpg');?>)">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Free Food</h2>
                <p class="t-center">Fictional University offers lunch plans for those in need.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
      </div>
    </div>

<?php
    // get_footer will pull in the contents of footer.php
    get_footer();
?>