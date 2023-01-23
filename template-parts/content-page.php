<div class="post-item">
    <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="generic-content">
        <!-- You can either list the_content() or the_excerpt() here: -->
    <?php the_excerpt(); ?>
    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
    </div>
</div>