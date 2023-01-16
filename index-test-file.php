<?php 
// get_header will pull in the contents of header.php
    get_header();
?>

<!-- <h1><?php bloginfo('name'); ?></h1> -->
<!-- Websites slogin, wp-admin-settings-general-tagline: -->
<!-- <p><?php bloginfo('description'); ?></p> -->

<?php 
// have_posts will pull the posts that are listed in the wp-admin - posts screen. The while loop will continue to loop through all the blog posts.
    while(have_posts()) {
    //This function will keep track of the current post we are working with. So each time the while loop runs, the_post() will tell WP to get all the relevant information about our post.
        the_post(); ?>
<!-- 
        the_permalink(); will take you to a screen with just that one post. It will update the URL with the title slug with the words separted by "-".
    the_title() will pull the title for each post. -->
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<!-- the_content() will pull the content of the post -->
        <?php the_content(); ?>
        <hr>

        <?php }

// Notes on when to echo or not to echo:
        function doubleMe($x) {
            echo $x * 2;
            // Instead of echoing, a lot of functions return a value. 
        }
        function tripleMe($x) {
            echo $x * 3;
            // Instead of echoing, a lot of functions return a value. 
        }
// Since the value was just returned, to output to the page, we would add echo.
        echo doubleMe(5);

        echo tripleMe(doubleMe(5));

        //WP functions:
        // If the WP function starts with the word get, then it's not going to echo anything for you. It only returns the value. You have to add echo to the start.
        // If the function starts with the word the - that means WP will echo out the value, and you don't need to add echo to the front of the function call.
        // the_title();
        // get_the_title();
        // the_ID();
        // get_the_id();


?>

<?php 
// get_footer will pull in the contents of footer.php
    get_footer();
?>