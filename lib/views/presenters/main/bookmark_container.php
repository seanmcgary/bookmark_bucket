<div class="bookmark-container">
    <div class="bookmark-category-container" id="bookmark-category-container">
        <?php
        if(isset($_SESSION['loggedIn']))
        {
        ?>
        <div class="list-container" category="yours">
            <h2>Your Bookmarks</h2>
            <ul class="bookmarks_list" >
                <?=$your_bookmarks?>
            </ul>
        </div>
        <?php
        }
        ?>
        <div class="list-container" category="all">
            <h2>All Bookmarks</h2>
            <ul class="bookmarks_list">
                <?=$all_bookmarks?>
            </ul>
        </div>
        <div class="list-container" category="new">
            <h2>New Bookmarks</h2>
            <ul class="bookmarks_list">
                <?=$new_bookmarks?>
            </ul>
        </div>
        <div class="list-container" category="popular">
            <h2>Popular Bookmarks</h2>
            <ul class="bookmarks_list">
                <?=$popular_bookmarks?>
            </ul>
        </div>
    </div>
</div>