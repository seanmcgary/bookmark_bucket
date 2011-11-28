<div class="bookmark-container">
    <?php //printr($user); ?>
    <div class="user-profile">
        <div class="name">
            <div class="avatar">
                <img src="<?=$user['avatar']?>">
            </div>
            <div class="username">
                <?=$user['username']?>
            </div>
            <div class="fullname">
                (<?=$user['fullname']?>)
            </div>
            <div class="follow">
                <input type="button" class="button btn-gray" value="Follow">
            </div>
        </div>
        <div class="profile">
            <div class="profile-left">
                <div class="div-row-mini">
                    <div class="field-label-small">
                        Full Name
                    </div>
                    <div class="field-value-small">
                        <?=$user['fullname']?>
                    </div>
                </div>
                <div class="div-row-mini">
                    <div class="field-label-small">
                        Username
                    </div>
                    <div class="field-value-small">
                        <?=$user['username']?>
                    </div>
                </div>
                <div class="div-row-mini">
                    <div class="field-label-small">
                        Member Since
                    </div>
                    <div class="field-value-small">
                        <?=$user['date_created']?>
                    </div>
                </div>
            </div>
            <div class="profile-right">
                <div class="top">
                    <div class="stat">
                        <div class="number">
                            <?=count($user['bookmarks'])?>
                        </div>
                        <div class="desc">
                            Bookmarks
                        </div>
                    </div>
                    <div class="stat">
                        <div class="number">
                            <?=count($user['buckets'])?>
                        </div>
                        <div class="desc">
                            Buckets
                        </div>
                    </div>
                    <div class="stat">
                        <div class="number">
                            <?=count($user['followers'])?>
                        </div>
                        <div class="desc">
                            Followers
                        </div>
                    </div>
                </div>
                <div class="bottom">
                    <div class="text">
                        Following N people
                    </div>
                    <div class="following">
                        <div class="image">
                            <img src="http://placehold.it/30">
                        </div>
                        <div class="image">
                            <img src="http://placehold.it/30">
                        </div>
                        <div class="image">
                            <img src="http://placehold.it/30">
                        </div>
                        <div class="image">
                            <img src="http://placehold.it/30">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>