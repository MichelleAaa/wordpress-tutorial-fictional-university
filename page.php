<!-- page.php is used to display pages from wp-admin - pages. -->

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
        <!-- <h2><?php the_title(); ?></h2> -->
<!-- the_content() will pull the content of the post -->
        <!-- <?php the_content(); ?> -->

        <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg'); ?>)"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title(); ?></h1>
        <div class="page-banner__intro">
          <p>DON'T FORGET TO REPLACE THIS LATER.</p>
        </div>
      </div>
    </div>

    <!-- This is a breadcrumb box, where we are displaying links to other related pages. For a child page, we would want to display the parent name, then the current child page name. -->
    <!-- In wp-admin, when you edit a post or page, towards the end of the URL you will see a number. It's the unique numerical ID for that page or post.
    get_the_ID() Will get the ID of the current post or page. 
    If you want to know if the current page has a parent page, you would instead need to use wp_get_post_parent_id() -- The function will give the id number of the parent, if the post has a parent. If it doesn't, then it will output 0 zero.
    wp_get_post_parent_id(get_the_ID()) -- This will get the ID of the current page's parent, if there is one.
  -->
    <div class="container container--narrow page-section">
      <?php 
      $theParent = wp_get_post_parent_id(get_the_ID());
      // This will output the current page's parent's ID number, if there is one, or zero, if there's no parent.
        if ($theParent) { ?>
          <div class="metabox metabox--position-up metabox--with-home-link">
        
        <!-- When you are on a child page, we are displaying a link to the parent page. But when we are on a parent page, there's no need for this box. For this, we are using an if statement -->

        <!-- get_permalink() allows us to pass in the id of a post/page to get the link for. In this case, we are asking for the id of the current page's parent page.-->
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent); ?></a> 
          <!-- 
          get_the_title() allows you to pass in an id in the () of the page you want to pull the title of. So in this case, we set it to the parent's post ID.
          We add the_title() to the span to display the title of the current page -->
          <span class="metabox__main"><?php the_title(); ?></span>
        </p>
      </div>

        <?php }
      ?>

<!-- This creates a side menu of page links. 
If you want an entire sidebar menu of all links on the side, you could use:
        <?php 
            wp_list_pages();
          ?>
          Note that the function also outputs an odd title, pages, at the top.

However, for this example, we only want children pages of a current page being viewed. So if we are on About Us, it should list About Us, and then the two child pages, of Our History, and Our Goals -->

<!-- Note that for  <?php echo get_the_title($theParent); ?> -- If the current page is the parent and the parent doesn't have a parent, the parent will return a zero. get_the_title() will interpret a zero to be the current page.-->

<!-- If the page is not a parent or child page, we don't want this section to display at all. 
We are using $theParent becuase on a child page that number will be larger than zero.-->
    
  <!-- get_pages() is similar to wp_list_pages, however, wp_list_pages will output to the page for you the name of those pages, while get_pages() just returns the pages in memory. -->
    
    <?php 
    // First parameter - child of - get_the_ID will get the current id, and will return names of any children pages. -- If the current page has no children, then get_the_ID will return something falsy.
    $testArray = get_pages(array(
      "child_of" => get_the_ID()
    )); 
    // If the current page has a parent, or if it is a parent:
    if($theParent || $testArray) { ?>
      <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
        <ul class="min-list">
          <!-- The arguement must be an associative array. 
        title_li = NULL gets rid of the 'pages' li that automatically shows up at the top of the page.
        "child_of" will identify children of the listed id. You can hardcode an ID, or you could use get_the_ID() to target the current post's ID.
        If we are on a parent page, we can use the id of the current page, with get_the_ID().
        If we are on a child page, we need to check the parent's ID.
        -->
          <?php 
          if ($theParent){// If you are on a child page, then theParent is true.
            $findChildrenOf  = $theParent;
          } else { // If you are on a parent page -- then get the ID of the current page, since it's the parent.
            $findChildrenOf = get_the_ID();
          }

            wp_list_pages(array(
              "title_li" => NULL,
              "child_of" => $findChildrenOf,
              "sort_column" => 'menu_order' 
            )); // get_pages() is similar to wp_list_pages, however, wp_list_pages will output to the page for you the name of those pages, while get_pages() just returns the pages in memory.
            // By default WP will alphabetically order the pages. If you want that, you can leave out - "sort_column" => 'menu_order' - from the array. If you want a custom sort order, then you can use the listed key/values. Then, go into wp-admin - pages - quick edit - under parent page - enter an order value. - If you enter 1 for one child, and 2 for another, WP will order as requested.

          ?>
        </ul>
      </div>
      <?php } ?>

      <div class="generic-content">
        <?php the_content(); ?>
      </div>
    </div>

    <?php }

    // get_footer will pull in the contents of footer.php
    get_footer();
?>