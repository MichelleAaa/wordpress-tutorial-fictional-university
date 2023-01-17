<?php
  get_header(); ?>

    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">
            <!-- This one function will take care of outputting the archive type name. Day: June 8, 2017. Category: Awards. Author: Brad -- These are the samples it will output. -->
        <?php 
        the_archive_title();
        ?>
        <!-- THIS IS ANOTHER WAY TO ADJUST THE PAGE BANNER TITLE FOR CATEGORY AND AUTHOR ARCHIVES. -- IF YOU WANT FINE-GRAINED CONTROL OVER THE TITLES YOU COULD USE THIS IF YOU DON'T PREFER THE_ARCHIVE_TITLE() ABOVE. -->
        <!-- is_category() will return true if you are on a category archive screen, otherwise, false. -->
        <!-- <?php if (is_category()) {
                // This will echo the category name, if it's a category:
            single_cat_title();;
        }
        // is_author() will return true if you are on an author category screen.
        if(is_author()){
            echo "Posts by "; the_author();
        }
        ?> -->
        </h1>
        <div class="page-banner__intro">
            <!-- Author archives pull in the biography from the author. 
        wp-admin - users - your profile - see biographical info -- anything you type here will be pulled in by the_archive_description().
        category archives - wp-admin - posts - categories - click on the category name - Description field - anything you type here will be pulled in by the_archive_description().  -->
          <p><?php the_archive_description(); ?></p>
        </div>
      </div>
    </div>

  <div class="container container--narrow page-section">
    <!-- Blog post while loop -->
    <?php
    while(have_posts()) {
      the_post(); ?>
      <div class="post-item">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      </div>
      <div class="metabox">
        <!-- the_author_posts_link() - shows the name of the author as a link which will bring you to an archive of other posts by that same author. The name is not capitalized by default. You can go into wp-admin - users - Your Profile - Your username cannot be changed, but you can change is the Nickname field, which is used for the blog. Aftwards, in "Display name publicly as", change it to the name you just entered.   -->
        <!-- the_time() outputs the time the blog post was created. Parameters - "F" outputs the month. "Y" outputs the year, etc. -- See: https://wordpress.org/support/article/formatting-date-and-time/ -- You can separate with ., -, space, etc. between the letters in the string-->
        <!-- get_the_category_list() - If a post has mutliple categories, whatever we type in as a parameter is what will be separated between the two category names. So you could do , &, etc.  -->
        <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time("n.j.y"); ?> in <?php echo get_the_category_list(", "); ?></p>
      </div>
      <div class="generic-content">
        <!-- You can either list the_content() or the_excerpt() here: -->
      <?php the_excerpt(); ?>
      <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
      </div>
    <?php } 
    // This is used to create pagination links -- note that in wp-admin we can change the number of blog posts that show per page. by default though it will not have page links to see more:
    echo paginate_links();
    ?>
  </div>

  <?php get_footer();

?>