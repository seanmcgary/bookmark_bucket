<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */

$manage = false;

if(isset($account))
{
    $manage = $account;
}



foreach($bookmarks as $bookmark)
{
?>
<li id="<?=$bookmark['bookmark_id']?>">
    <div class="bookmark">
        <?php
            if($manage == true)
            {
                echo '<div class="delete"><div class="delete-button" bookmark_id="'.$bookmark['bookmark_id'].'">Delete</div></div>';
            }
        ?>

        <div class="bookmark-data">
            <div class="title">
                <a href="<?=$bookmark['url']?>" link-type="external" bookmark_id="<?=$bookmark['bookmark_id']?>"><?=utf8_decode($bookmark['title'])?></a>
            </div>
            <div class="meta">
            <?php
                $tags = '';
                foreach($bookmark['tags'] as $tag)
                {
                    $tags .= $tag.', ';
                }

                $tags = rtrim($tags, ', ');

                echo $tags;
            ?>
            </div>
        </div>
    </div>
</li>
<?php
}
?>