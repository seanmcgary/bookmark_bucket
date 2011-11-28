<div class="bookmark-container">
    <h2>Bookmarks</h2>
    <div class="bookmark-category-container" id="bookmark-category-container">
        <ul class="bookmarks_list" category="all">
            <?=$all_bookmarks?>
        </ul>
        <?php
        foreach($buckets as $bucket)
        {
        ?>
        <ul class="bookmarks_list" category="<?=$bucket['bucket_id']?>">
            <?=$bucket['bucket']?>
        </ul>
        <?php
        }
        ?>
    </div>
</div>