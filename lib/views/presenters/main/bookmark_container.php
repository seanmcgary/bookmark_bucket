<div class="bookmark-container">
    <h2>Bookmarks</h2>
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
        <ul class="bookmarks_list" category="all">
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