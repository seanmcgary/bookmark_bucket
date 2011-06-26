<div class="bookmark-container">
    <h2>Bookmarks</h2>
    <ul id="bookmark-categories" class="bookmark-categories">
    <?php
        if(isset($_SESSION['loggedIn']))
        {
    ?>
        <li class="selected" id="yours">Your's</li>
        <li id="everyone">Everyone's</li>
        <li id="new">New</li>
        <li id="popular">Popular</li>
    <?php
        }
        else
        {
    ?>
        <li id="everyone" class="selected">Everyone's</li>
        <li id="new">New</li>
        <li id="popular">Popular</li>
    <?php
        }
    ?>
    </ul>
    <div class="bookmark-category-container" id="bookmark-category-container">
        <?php
        if(isset($_SESSION['loggedIn']))
        {
        ?>
        <ul class="bookmarks_list" category="yours">
            <?=$your_bookmarks?>
        </ul>
        <?php
        }
        ?>
        <ul class="bookmarks_list" category="everyone">
            <?=$all_bookmarks?>
        </ul>
        <ul class="bookmarks_list" category="new">
            <?=$new_bookmarks?>
        </ul>
        <ul class="bookmarks_list" category="popular">
            <?=$popular_bookmarks?>
        </ul>
    </div>
</div>