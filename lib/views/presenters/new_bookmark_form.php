<h1>New Bookmark</h1>
<div class="bookmark-container">
    <form name="new_bookmark" id="new_bookmark">
        <div class="inputbox">
            <div class="title">Url</div><input type="text" id="url" name="url" placeholder="http://www.example.com">
        </div>
        <div class="inputbox">
            <div class="title">Title </div><input type="text" id="title" name="title">
        </div>
        <div id="options" class="options show_options">
            <div class="div-row">
                <div class="field-label">
                    Private
                </div>
                <div class="field-value">
                    <input type="checkbox" id="public" name="privacy" value="public">
                </div>
                <div class="field-status">
                    <span class="gray_text">Bookmarks marked as private will only be visible to you. Setting this to "off" will make the bookmark public.</span>
                </div>
            </div>
            <div class="inputbox">
                <div class="title">Tags</div><input type="text" id="tags" name="tags" placeholder="Comma or space separated list of tags">
            </div>
            <div class="div-row">
                <div class="tag-container" id="tag-container">

                </div>
            </div>
            <?=$bucket_dropdown_select?>
        </div>
        <div class="div-row">
            <div class="field-value">
                <input type="submit" class="submit-form float-left btn-green" value="Add">
            </div>
            <div class="field-label" id="bookmark-status">

            </div>
        </div>
    </form>
</div>