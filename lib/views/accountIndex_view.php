<div class="right-col">
    <div class="bookmark-container">
        <script type="text/javascript">
            var edit_account = '<?=site_url('account/edit_account')?>';
            var delete_bookmark = '<?=site_url('account/delete_bookmark')?>';
        </script>
        <h2>Your Account</h2>
        <form name="edit-account" id="edit-account" method="post" action="<?=site_url('login/process_login')?>">
    <?php
        //printr($account_details);
        foreach($user_fields as $key => $value)
        {
    ?>
        <div class="div-row">
            <div class="field-label">
                <?=$value?>
            </div>
            <div class="field-value">
                <input type="text" name="<?=$key?>" id="<?=$key?>" value="<?=$account_details[$key]?>">
            </div>
        </div>
    <?php
        }
    ?>
            <div class="div-row">
                <div class="field-label">
                    New Password
                </div>
                <div class="field-value">
                    <input type="password" id="password" name="password">
                </div>
            </div>
            <div class="div-row">
                <div class="field-label">
                    Confirm New Password
                </div>
                <div class="field-value">
                    <input type="password" id="confirm_pass" name="confirm_pass">
                </div>
            </div>
            <div class="div-row">
                <div class="field-label">
                    <input type="submit" class="submit-form" value="Save">
                </div>
                <div class="field-value" id="edit-account-status">

                </div>
            </div>
        </form>
    </div>
    <div class="bookmark-container">
        <h2>Sync Token</h2>
        <p>Your sync token is used to identify you when syncing your bookmarks across devices</p>
        <div class="div-row">
            <div class="field-label">
                <h3>Token</h3>
            </div>
            <div class="field-value">
                <div class="text">
                    <?=$account_details['sync_key']?>
                </div>
            </div>
        </div>
    </div>
    <div class="bookmark-container" id="user-bookmarks">
        <h2>Manage Bookmarks</h2>
        <ul class="manage-bookmarks_list" category="yours">
            <?=$bookmarks?>
        </ul>
    </div>
</div>