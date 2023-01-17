<!DOCTYPE html>
<!-- This will cause WP to add the language attribute for us -->
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <!-- Note that the below is required for the website to be responsive to the viewport: -->
    <meta name="viewp ort" content="width=device-width, initial-scale=1"> 
    <!-- Instead of using link, in WP we call wp_head() to call the css file. This let's WP be in charge of our head section. WP will have the final say and will load whatever it needs to load in the head. -->
    <?php wp_head(); ?>
  </head>
  <!-- body_class() will add a class attribute on the body tag and will give it attributes based on what screen the user is visiting, since we are putting this in the header. That includes things like the page-id-(number here), etc. --You can use these classnames in your CSS or JS -->
  <body <?php body_class(); ?>>
    <header class="site-header">
      <div class="container">
        <h1 class="school-logo-text float-left">
          <a href="<?php echo site_url(); ?>"><strong>Fictional</strong> University</a>
        </h1>
        <span class="js-search-trigger site-header__search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
        <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
        <div class="site-header__menu group">
          <nav class="main-navigation">
            <ul>
              <!-- technically for the href= you could use/about-us, but if you are using XAMPP or something, you may have multiple WP sites on your host, so it's better to do it this way: -->
              <!-- To have the link light-up when it's the active link - aka we are on that page - we can use an if statement with is_page(). is_page() will return true if the parameter is a match to the page name from the end of the URl slug. 
              NOTE: is_home() could be used for the home page instead of is_page(). There is also is_archive() for an archive.-->
              <!-- Now, to get About Us to light up if we are on a child page of About Us, we have to add an || ---  wp_get_post_parent_id(0) - The 0 means to look up the current page. So this will return the ID of the parent page. 
            For the ==, you  need to go into your wp-admin page, and find what the id is for each of the pages (click on the page, it will say the post number in the URL) -- for the fist one, we have to look for the ID of the About Us page.-->
            <!-- If the current page is the about us page, or if it's a child page of about us (which I went into wp-admin to get 11), then the about us li will have a class added to it for styling.-->
              <li <?php if(is_page('about-us') || wp_get_post_parent_id(0) == 11) echo 'class="current-menu-item"' ?>><a href="<?php echo site_url('/about-us'); ?>">About Us</a></li>
              <li><a href="#">Programs</a></li>
              <li <?php if (get_post_type() == 'event') echo 'class="current-menu-item"'; ?>><a href="<?php echo get_post_type_archive_link('event'); ?>">Events</a></li>
              <li><a href="#">Campuses</a></li>
              <!-- For the blog pages, we would want the blog icon to be highlighted (aka the css class added) for any blog post page that we are on: -->
              <li <?php if(get_post_type() == 'post') echo 'class="current-menu-item"'
              // a blog post has a type of post, while a page has a type of page. (We can also create new event types as well.)
              ?>><a href="<?php echo site_url('/blog'); ?>">Blog</a></li>
            </ul>

            <!-- Dynamic menu, with setup in wp-admin & changes to functions.php-->
            <!-- <?php 
            // Parameter is the code name in functions.php, the first parameter of register_nav_menu(), which is 'headerMenuLocation'
              wp_nav_menu(array('theme_location' => 'headerMenuLocation'));
            ?> -->
          </nav>
          <div class="site-header__util">
            <a href="#" class="btn btn--small btn--orange float-left push-right">Login</a>
            <a href="#" class="btn btn--small btn--dark-orange float-left">Sign Up</a>
            <span class="search-trigger js-search-trigger"><i class="fa fa-search" aria-hidden="true"></i></span>
          </div>
        </div>
      </div>
    </header>


    <!-- Note that we don't close the html or body tags, becuase they will be closed in footer.php -->