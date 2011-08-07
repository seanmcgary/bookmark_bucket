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
    //printr($bookmark);
?>
<li id="<?=$bookmark['bookmark_id']?>">
    <div class="bookmark">
        <?php
            if($manage == true)
            {
                echo '<div class="delete"><div class="delete-button" bookmark_id="'.$bookmark['bookmark_id'].'">Delete</div></div>';
            }

            $icon_class = 'icon-loggedout';

            if(isset($_SESSION['loggedIn']))
            {
                $icon_class = 'icon';
            }
        ?>

        <div class="<?=$icon_class?>">
        <?php

            if($manage == false)
            {
                $image = '';
                if(in_array($bookmark['bookmark_id'], $user_bookmarks))
                {
                    echo '<img src="images/bookmark_1_icon&16_blue.png">';
                }
                else
                {
                    echo '<img src="images/bookmark_1_icon&16_gray.png" class="bookmark-icon" bookmark_id="'.$bookmark['bookmark_id'].'">';
                }
            }
        ?>
        </div>
        
        <div class="bookmark-data">
            <div class="title">
                <a href="<?=$bookmark['url']?>" link-type="external" bookmark_id="<?=$bookmark['bookmark_id']?>"><?=utf8_decode($bookmark['title'])?></a>
            </div>
            <div class="meta">
            <?php
                echo "Bookmarked ".format_date($bookmark['date_created']);
            ?>
            </div>
            <div class="tags">
            <?php
                foreach($bookmark['tags'] as $tag)
                {
                    if(array_key_exists('user_tags', $bookmark) && in_array($tag, $bookmark['user_tags']))
                    {
                        echo '<a href="#" class="user-tag">'.$tag.'</a>';
                    }
                    else
                    {
                        echo '<a href="#">'.$tag.'</a>';
                    }

                }
            ?>
            </div>
        </div>
    </div>
</li>
<?php
}
?>