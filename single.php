<!-- single.php is code for a single blog post -- if you click on the permalink in the blog post list, such as from the home page, it will take you to the individual post. So you can customize the code for the rendering of the individual post. -->
 
<?php 
    // get_header will pull in the contents of header.php
    get_header();

// have_posts will pull the posts that are listed in the wp-admin - posts screen. The while loop will continue to loop through all the blog posts.
    while(have_posts()) {
    //This function will keep track of the current post we are working with. So each time the while loop runs, the_post() will tell WP to get all the relevant information about our post.
        the_post(); ?>
<!-- 
    the_title() will pull the title for each post. 
NOTE: For the single blog post, we don't want it to have a link since we are already on the single post's page. So we have deleted the a tag:-->
        <h2><?php the_title(); ?></h2>
<!-- the_content() will pull the content of the post -->
        <?php the_content(); ?>
    <?php }

    // get_footer will pull in the contents of footer.php
    get_footer();
?>