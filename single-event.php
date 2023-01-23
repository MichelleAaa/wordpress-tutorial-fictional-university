<!-- This is for a custom post type. Whenever we register a new custom post type, wp will be on the lookup for a file called single-(new post type name).php -- in this case, it's single-event.php since we registered the new post type as 'event'. -->

<!-- single.php is code for a single blog post -- if you click on the permalink in the blog post list, such as from the home page, it will take you to the individual post. So you can customize the code for the rendering of the individual post. -->


<?php 
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
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a> 
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>
        <?php 
        // The advanced custom fields program gives us access to this function. We just tell it which custom field name we want to retrieve. -- you can go to wp-admin - custom fields - and find the field name which has _ instead of spaces.
        // If you want to see what's inside the variable, you can use - print_r() to check it out. print_r($related_programs).
        $relatedPrograms = get_field('related_programs');
        // This will check if there's even one related_programs item. It will evaluate to false if there's nothing.
        if($relatedPrograms) {

        echo '<hr class="section-break">';
        echo "<h2 class='headline headline--medium'>Related Program(s)</h2>";
        echo '<ul class="link-list min-list">';
// The name of the second variable, $program, doesn't matter. Only the array name must be correct.
// echo get_the_title($program); -  We give the ID of the post or a wp post object. that's what program is, as each item in the array is a wp post object. -- This will output the program names that we added in the wp-admin panel - by adding the relationship in the event post type, as we set up a custom field - relationship type.
        foreach($relatedPrograms as $program) { ?>
            <li><a href="<?php echo get_the_permalink($program); ?>"><?php echo get_the_title($program); ?></a></li>

        <?php }
        echo '</ul>';
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