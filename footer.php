<footer class="site-footer">
      <div class="site-footer__inner container container--narrow">
        <div class="group">
          <div class="site-footer__col-one">
            <h1 class="school-logo-text school-logo-text--alt-color">
              <a href="<?php echo site_url() ?>"><strong>Fictional</strong> University</a>
            </h1>
            <p><a class="site-footer__link" href="#">555.555.5555</a></p>
          </div>

          <div class="site-footer__col-two-three-group">
            <div class="site-footer__col-two">
              <h3 class="headline headline--small">Explore</h3>
              <nav class="nav-list">
                <!-- Dynamic menu list -->
                <!-- <?php 
                  wp_nav_menu(array('theme_location' => 'footerLocationOne'));
                ?> -->
                <ul>
                  <li><a href="<?php echo site_url('/about-us') ?>">About Us</a></li>
                  <li><a href="#">Programs</a></li>
                  <li><a href="#">Events</a></li>
                  <li><a href="#">Campuses</a></li>
                </ul>
              </nav>
            </div>

            <div class="site-footer__col-three">
              <h3 class="headline headline--small">Learn</h3>
              <nav class="nav-list">
                <ul>
                  <li><a href="#">Legal</a></li>
                  <li><a href="<?php echo site_url('privacy-policy') ?>">Privacy</a></li>
                  <li><a href="#">Careers</a></li>
                </ul>
                <!-- Dynamic menu list -->
                <!-- <?php 
                  wp_nav_menu(array('theme_location' => 'footerLocationTwo'));
                ?> -->
              </nav>
            </div>
          </div>

          <div class="site-footer__col-four">
            <h3 class="headline headline--small">Connect With Us</h3>
            <nav>
              <ul class="min-list social-icons-list group">
                <li>
                  <a href="#" class="social-color-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                </li>
                <li>
                  <a href="#" class="social-color-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </li>
                <li>
                  <a href="#" class="social-color-youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a>
                </li>
                <li>
                  <a href="#" class="social-color-linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                </li>
                <li>
                  <a href="#" class="social-color-instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </footer>
<!-- search-overlay is hidden by default. when you add search-overlay--active, then the overlay is active. The search overlay will be hidden by default, and we will add the search-overlay--active to it dynamtically. (Once they click a button, the class will be added and it will become visible.) -- This has been moved into Search.js but you could also have it here.-->
  <!-- <div class="search-overlay">
    <div class="search-overlay__top">
        <div class="container">
        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
        <!-- To prevent the browser from showing the previous terms you’ve searched for you can add this attribute/value pair to the search <input> element: autocomplete="off" -->
        <!-- <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
        </div>
    </div>
    <div>
        <div id="search-overlay__results"></div>
    </div>
  </div> --> -->


<!-- wp_footer() gives WP the ability to load JS files. Also, in this case, it adds in the black WP menu bar at the top of the page. -->
<?php wp_footer(); ?>
</body>
</html>