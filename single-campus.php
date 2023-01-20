<!-- This is for a custom post type. Whenever we register a new custom post type, wp will be on the lookup for a file called single-(new post type name).php -- in this case, it's single-event.php since we registered the new post type as 'event'. -->

<!-- single.php is code for a single blog post -- if you click on the permalink in the blog post list, such as from the home page, it will take you to the individual post. So you can customize the code for the rendering of the individual post. -->


<?php 

// the_ID(); // This will echo out the numerical ID of the page. (You can add it temporarily if you need the page/posts id number -- though you can also look in wp admin.)
    // get_header will pull in the contents of header.php
    get_header();

// have_posts will pull the posts that are listed in the wp-admin - posts screen. The while loop will continue to loop through all the blog posts.
    while(have_posts()) {
    //This function will keep track of the current post we are working with. So each time the while loop runs, the_post() will tell WP to get all the relevant information about our post.
        the_post(); 
        pageBanner();
        ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <!-- We are using site_url() to get the main url of the site, and add on /blog to the end of it, so this links to the main blog page, which has the while loop that goes over all the posts. (aka in index.php since we updated the homepage to a different page.) -->
                <!-- Sometimes we update the name of a custom post type. get_post_type_archive_link() will pull the custom type you are looking for. It goes off of the keyword you used when you set up the custom post type in mu-plugins or functions.php, in the first parameter of register_post_type() -->
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> 
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <!-- <div class="acf-map"> -->
  <div class="">

    <!-- Blog post while loop -->
    <!-- <ul class="link-list min-list"> -->
   

        <!-- This relies on setting up a google maps API, and then setting up a custom field of google map for the campus post type. Then you go into each campus post and record the campus location in the map that shows up. (You have to list the api key in functions.php.) -->
        <!-- //   $mapLocation = get_field('map_location'); -->
        <!-- //   echo $mapLocation['lng']; -- You could access the longtitude of the location. -->
        <!-- ?></a></li> -->
        
        <!-- This could be used to display the map, but I dont' have an api key: -->
        <!-- <?php $mapLocation = get_field('map_location'); ?> -->
        <!-- <div class="marker" data-lat="<?php $mapLocation['lat'] ?>" data-lng="<?php $mapLocation['lng'] ?>">
            <h3><?php the_title(); ?></h3>
            <?php echo $mapLocation['address']; ?>
        </div> -->
        <!-- }  -->
        <!-- // </div> -->

        <?php 

// ________________
        $relatedPrograms = new WP_Query(array(
            'posts_per_page' => -1, // -1 would give us all posts that meet the conditions.
            'post_type' => 'program', // The post type is going to target the custom post type of 'event'
            'orderby' => 'title',
            'order' => 'ASC', //by default it's set to 'DESC'. 
            'meta_query' => array(
            // the new filter below says -- if the array of related_programs contains (aka LIKE), the ID of the current program post, then include it.
            array(
                'key' => 'related_campuses', // before the array of data can be saved, it has to be serialized. -- it becomes something like a:3:{i:'0',i:'12',i:'1'} -- It's a big messy string of text. If you were looking for 12, 12 would match, as well as 120.
                'compare' => 'LIKE',
                'value' => '"' . get_the_ID() . '"' // we need to search for "result here" becuase of the serialization mentioned above. -- so we are concating on double quotes at the start and end of the function.
                // Becuase of the while loop for the professor is above this code, you have to be sure you do the wp reset (above) so this gets the ID of the page, not of the while loop above it.
            )
            )//we could have multiple restrictions in the array by adding multiple inner arrays. 
        ));
        // We are looking inside $homepageEvents to make sure it has posts. 
        if($relatedPrograms->have_posts()){
            echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Programs Available At This Campus</h2>';
        
        echo '<ul class="min-list link-list">';
        while($relatedPrograms->have_posts()) {
            //We have to look within the object for the built-in the_post() function as well as the above have_posts() function.
            $relatedProfessors->the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>">
            </a>
            </li>
        <?php }
        echo '</ul>';
        }
//___________

        wp_reset_postdata();

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