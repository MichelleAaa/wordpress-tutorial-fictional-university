<!-- page.php is used to display pages from wp-admin - pages. -->

<?php 
    if (!is_user_logged_in()){ // If a user is not logged in.
        wp_redirect(esc_url(site_url('/')));//we re-direct non-logged in users back to the home page.
        exit;
    }

    // get_header will pull in the contents of header.php
    get_header();

// have_posts will pull the posts that are listed in the wp-admin - posts screen. The while loop will continue to loop through all the blog posts.
    while(have_posts()) {
    //This function will keep track of the current post we are working with. So each time the while loop runs, the_post() will tell WP to get all the relevant information about our post.
        the_post(); 
        
        // This calls the pageBanner function in functions.php and inserts the code here:
        pageBanner();
        ?>


    <div class="container container--narrow page-section">
        <div class="create-note">
            <h2 class="headline headline--medium">Create New Note</h2>
            <input class="new-note-title" placeholder="Title">
            <textarea class="new-note-body" placeholder="Your note here..."></textarea>
            <span class="submit-note">Create Note</span>
        </div>
        <ul class="min-list link-list" id="my-notes">
            <?php 
            $userNotes = new WP_Query(array(
                'post_type' => 'note',
                'posts_per_page' => -1,//gives us all the notes on the page.
                'author' => get_current_user_id() // this is so we only get posts that were created by the logged-in user, no one else.
            ));
            while($userNotes->have_posts()) {
                $userNotes->the_post(); ?>
                <!-- The_ID() will add the id of the post as a data attribute.  -->
                <li data-id="<?php the_ID(); ?>">
                <!-- Note that readonly will make the input and textarea fields read only. In MyNotes.js we are targeting the input/text area fields and removing readonly when edit is clicked. -->
                    <!-- In wp, whenever you are using data from the database as an HTML attribute, you want to wrap it with a function called esc_attr(). -->
                    <input readonly class="note-title-field" value="<?php echo esc_attr(get_the_title()); ?>">
                    <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i>Edit</span>
                    <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i>Delete</span>
                    <!-- We need to use wp_strip_all_tags() becuase the content comes up with <p> tags and things, so this will remove them so it's not outputting HTML as text. -->
                    <textarea readonly class="note-body-field"><?php echo esc_attr(wp_strip_all_tags(get_the_content())); ?></textarea>
                    <!-- The save button is hidden by default, and once we click the edit button, it will become visible due to the JS in MyNotes.js. -->
                    <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                </li>
            <?php }
            ?>
        </ul>
    
    </div>

    <?php }

    // get_footer will pull in the contents of footer.php
    get_footer();
?>