<!-- This is for a custom post type. Whenever we register a new custom post type, wp will be on the lookup for a file called single-(new post type name).php -- in this case, it's single-event.php since we registered the new post type as 'event'. -->

<!-- single.php is code for a single blog post -- if you click on the permalink in the blog post list, such as from the home page, it will take you to the individual post. So you can customize the code for the rendering of the individual post. -->


<?php 
    // get_header will pull in the contents of header.php
    get_header();

// have_posts will pull the posts that are listed in the wp-admin - posts screen. The while loop will continue to loop through all the blog posts.
    while(have_posts()) {
    //This function will keep track of the current post we are working with. So each time the while loop runs, the_post() will tell WP to get all the relevant information about our post.
        the_post(); ?>

    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
        <div class="page-banner__intro">
          <p>DON'T FORGET TO REPLACE THIS LATER.</p>
        </div>
      </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <!-- We are using site_url() to get the main url of the site, and add on /blog to the end of it, so this links to the main blog page, which has the while loop that goes over all the posts. (aka in index.php since we updated the homepage to a different page.) -->
                <!-- Sometimes we update the name of a custom post type. get_post_type_archive_link() will pull the custom type you are looking for. It goes off of the keyword you used when you set up the custom post type in mu-plugins or functions.php, in the first parameter of register_post_type() -->
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> 
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

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
                ), // the new filter below says -- if the array of related_programs contains (aka LIKE), the ID of the current program post, then include it.
                array(
                    'key' => 'related_programs', // before the array of data can be saved, it has to be serialized. -- it becomes something like a:3:{i:'0',i:'12',i:'1'} -- It's a big messy string of text. If you were looking for 12, 12 would match, as well as 120.
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"' // we need to search for "result here" becuase of the serialization mentioned above. -- so we are concating on double quotes at the start and end of the function.
                )
              )//we could have multiple restrictions in the array by adding multiple inner arrays. 
            ));
            // We are looking inside $homepageEvents to make sure it has posts. 
            if($homepageEvents->have_posts()){
                echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
            
            while($homepageEvents->have_posts()) {
              //We have to look within the object for the built-in the_post() function as well as the above have_posts() function.
              $homepageEvents->the_post(); ?>
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
                  <p><?php if (has_excerpt()) {
                  //the_excerpt(); // the_excerpt() handles outputting the content to the page for us, which comes with some extra white space. If you prefer to style it different, then use the following instead:
                  echo get_the_excerpt();
              } else {
                echo wp_trim_words(get_the_content(), 18);
              } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
                </div>
            </div>
            <?php }
            }
          ?>
    </div>
<!-- 
    the_title() will pull the title for each post. 
NOTE: For the single blog post, we don't want it to have a link since we are already on the single post's page. So we have deleted the a tag:-->
        <!-- <h2><?php the_title(); ?></h2> -->
<!-- the_content() will pull the content of the post -->
        <!-- <?php the_content(); ?> -->
    <?php }

    // get_footer will pull in the contents of footer.php
    get_footer();
?>