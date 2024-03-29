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
            <input type="button" class="btn-red" button-type="delete-bucket" bucket_id="<?=$bucket['bucket_id']?>" value="Delete">
        </div>
        <div class="bookmark-data">
            <div class="title">
                <?=$bucket['bucket_name']?> (<?=count($bucket['bookmarks'])?>)
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