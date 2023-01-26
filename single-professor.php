<!-- NOTES are from single-event -- not all have been updated. -->

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
        <div class="generic-content">
            <!-- the_post_thumbnail() will output the featured image you uploaded to the wp-admin post page for this post.-- It creates an HTML image element for you and pulls in the source. -->
            <!-- <?php the_post_thumbnail(); the_content(); ?> -->

            <div class="row group">
                <div class="one-third">
                    <!-- If you want to use a custom img size you set up in functions.php with add_image_size(), you can enter the name here as a parameter (the name was the first parameter of add_image_size().) -->
                    <?php the_post_thumbnail('professorPortrait'); ?>
                </div>
                <div class="two-thirds">
                    <?php 
                        $likeCount = new WP_Query(array(
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID()
                                )
                            )//we are using a metaquery becuase we only want to pull it in when the liked ID matches the ID of the post.
                        ));

                        $existStatus = 'no';

                        // If the user is logged in, then we create the query. 
                        if(is_user_logged_in()) {
                            $existQuery = new WP_Query(array(
                            'author' => get_current_user_id(), // the query will only contain results if the current user is the author of the like.
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID()
                                )
                            )//we are using a metaquery becuase we only want to pull it in when the liked ID matches the ID of the post.
                            ));

                            if ($existQuery->found_posts) {
                                $existStatus = 'yes';
                            }
                        }
                    ?>

                    <span class="like-box" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>" data-professor="<?php the_ID(); ?>" data-exists="<?php echo $existStatus; ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
                    </span>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
        <?php 




        // The advanced custom fields program gives us access to this function. We just tell it which custom field name we want to retrieve. -- you can go to wp-admin - custom fields - and find the field name which has _ instead of spaces.
        // If you want to see what's inside the variable, you can use - print_r() to check it out. print_r($related_programs).
        $relatedPrograms = get_field('related_programs');
        // This will check if there's even one related_programs item. It will evaluate to false if there's nothing.
        if($relatedPrograms) {

        echo '<hr class="section-break">';
        echo "<h2 class='headline headline--medium'>Subject(s) Taught</h2>";
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