<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */

$manage = false;

if(isset($account))
{
    $manage = $account;
}

foreach($buckets as $bucket)
{
?>
<li id="<?=$bucket['bucket_id']?>">
    <div class="bookmark">
        <div class="delete">
            <div class="delete-button" bookmark_id="<?=$bucket['bookmark_id']?>">
                Delete
            </div>
        </div>
        <div class="bookmark-data">
            <div class="title">
                <?=$bucket['bucket_name']?>
            </div>
            <div class="meta">
            <?php
                echo "Bookmarked ".format_date($bucket['date_created']);
            ?>
            </div>
            <div class="tags">
            <?php
                foreach($bucket['tags'] as $tag)
                {
                    echo '<a href="#">'.$tag.'</a>';
                }
            ?>
            </div>
        </div>
    </div>
</li>
<?php
}
?>