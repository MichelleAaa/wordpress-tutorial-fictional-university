<!-- This will enable visitors to type in their search phrase.
    '/' will generate the homepage url for our wordpress installation. esc_url is considered a security practice. Good to use when you echo out the URL from the database. It’s not so much to protect from hacking, but more to protect from issues with site admin. 
    We are using GET as it's going to add the contents of the form to the end of the URL.-->
<form method="get" class="search-form" action="<?php echo esc_url(site_url('/')); ?>">
    <!-- The for in the label and the id on the input must match. -->
    <label class="headline headline--medium" for="s">Perform a New Search:</label>
    <div class="search-form-row">
        <input placeholder="What are you looking for?" class="s" id="s" type="search" name="s">
        <input class="search-submit" type="submit" value="Search">
        <!-- If someone enters Biology, the URL after the submit button is hit will read - localhost:300/(or whatever your site start name is)search/?s=Biology -->
    </div>
</form>