<div class="left-col">
    <div class="list">
        <h3>User's Buckets</h3>
        <ul class="bookmark-categories" id="bookmark-categories">
            <li id="all" class="selected">All Bookmarks</li>
        <?php
            foreach($buckets as $bucket)
            {
                echo '<li id="'.$bucket['bucket_id'].'">'.$bucket['bucket_name'].'</li>';
            }
        ?>
        </ul>
    </div>
</div>