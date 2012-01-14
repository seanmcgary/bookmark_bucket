<div class="right-col">
    <div class="bookmark-container">
        <script type="text/javascript">
            var edit_account = '<?=site_url('account/edit_account')?>';
            var delete_bookmark = '<?=site_url('account/delete_bookmark')?>';
            var delete_bucket = '<?=site_url('account/delete_bucket')?>';
            var new_bucket = '<?=site_url('account/new_bucket')?>';
        </script>
        <div class="account-category-container" id="bookmark-category-container">
            <div class="account_element" category="settings">
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
            <div class="account_element" category="sync">
                <h2 class="no-underline">Sync Token</h2>
                <p>Your sync token is used to identify you when syncing your bookmarks across devices</p>
                <div class="div-row">
                    <div class="field-label">
                        <h4>Token</h4>
                    </div>
                    <div class="field-value">
                        <div class="text">
                            <?=$account_details['sync_key']?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="account_element" category="buckets">
                <h2>Manage Buckets</h2>
                <form name="new_bucket" id="new_bucket">
                    <div class="div-row">
                        <div class="field-label">
                            Bucket Name
                        </div>
                        <div class="field-value">
                            <input type="text" name="bucket_name" id="bucket_name" placeholder="Bucket of Awesome">
                        </div>
                    </div>
                    <div class="div-row">
                        <div class="field-label">
                            Description
                        </div>
                        <div class="field-value">
                            <input type="text" name="bucket_description" id="bucket_description">
                        </div>
                    </div>
                    <div class="div-row">
                        <div class="field-label">
                            Private
                        </div>
                        <div class="field-value">
                            <input type="checkbox" toggle="true" id="public" name="privacy" value="public">
                        </div>
                        <div class="field-status">
                            <span class="gray_text">Bookmarks placed in private buckets are automatically marked as private.</span>
                        </div>
                    </div>
                    <div class="div-row">
                        <div class="field-label">
                            Auto Fill
                        </div>
                        <div class="field-value">
                            <input type="checkbox" toggle="true" id="auto_fill" name="auto_fill" value="on">
                        </div>
                        <div class="field-status">
                            <span class="gray_text">When turned on, bookmarks with pre-defined tags will be automatically placed in the bucket</span>
                        </div>
                    </div>
                    <div class="options" id="bucket_options">
                        <div class="inputbox">
                            <div class="title">Tags</div><input type="text" id="tags" placeholder="Comma or space separated list of tags">
                        </div>
                        <div class="div-row">
                            <div class="tag-container" id="tag-container">

                            </div>
                        </div>
                    </div>
                    <div class="div-row">
                        <div class="field-label">
                            <input type="submit" class="submit-form btn-green" value="Save">
                        </div>
                        <div class="field-value" id="new_bucket_status">

                        </div>
                    </div>
                </form>
                <h2>Your Buckets</h2>
                <ul class="manage-bookmarks_list" id="bucket-list">
                    <?=$buckets?>
                </ul>

            </div>
            <div class="account_element" category="bookmarks">
                <h2>Manage Bookmarks</h2>
                <ul class="manage-bookmarks_list" category="yours">
                    <?=$bookmarks?>
                </ul>
            </div>
            <div class="account_element" category="import">
                <h2>Import Bookmarks</h2>
                <p>Import your bookmarks from your browser(s). Simply provide the exported HTML file to start the import process.</p>
                <div class="div-row">
                    <div class="field-label">
                        <input type="file" id="bookmark_file" name="bookmark_file[]" multiple>
                    </div>
                    <div class="field-value">

                    </div>
                </div>
                <div class="div-row">
                    <table id="import-bookmarks-table" class="import-bookmarks-table">
                        <tr>
                            <th>Import</th>
                            <th>Bookmark Title</th>
                        </tr>
                        <!--<tr>
                            <td>
                                <input type="checkbox">
                            </td>
                            <td>
                                Bookmark Name
                            </td>
                        </tr>-->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    