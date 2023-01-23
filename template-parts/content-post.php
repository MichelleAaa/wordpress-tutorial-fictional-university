<div class="post-item">
        <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <!-- </div> -->
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
      </div>