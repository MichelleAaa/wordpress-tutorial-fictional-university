<div class="event-summary">
    <a class="event-summary__date t-center" href="#">
        <!-- the_field() can be used to pull data from a custom field. If you forgot the name of the custom field, you can go into wp-admin - Custom-Fields - and look for the Field Name. It's the one with no spaces.
        Note that for this setup we used the Ymd setup, so it outputs something like 20170720 - You can adjust the return value in Custom Fields -  But that's why we are using DateTime, to help with conversion. AND we are using get_field so it's a return value instead of the_field, which echos to the screen.-->
        <span class="event-summary__month"><?php 
        // DateTime will default to the current date, unless you add a parameter.
        $eventDate = new DateTime(get_field('event_date'));
        echo $eventDate->format('M');//asks for the month. 
        ?></span>
        <span class="event-summary__day"><?php 
        echo $eventDate->format('d');//asks for the day.  
        ?></span>
    </a>
    <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
        <p><?php if (has_excerpt()) {
        //the_excerpt(); // the_excerpt() handles outputting the content to the page for us, which comes with some extra white space. If you prefer to style it different, then use the following instead:
        echo get_the_excerpt();
    } else {
    echo wp_trim_words(get_the_content(), 18);
    } ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
    </div>
</div>