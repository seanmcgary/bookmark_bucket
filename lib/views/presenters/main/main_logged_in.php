<div class="bookmark-container">
    <h2>New Bookmark</h2>
    <form name="new_bookmark" id="new_bookmark">
        <div class="inputbox">
            <div class="http">http://</div><input type="text" id="url" name="url" placeholder="www.example.com">
        </div>
        <div class="inputbox">
            <div class="title">Title </div><input type="text" id="title" name="title">
        </div>
        <div class="div-row">
            <div class="field-label">
                Privacy
            </div>
            <div class="field-value">
                Public <input type="radio" id="public" name="privacy" value="public" checked><br>Private <input type="radio" id="private" name="privacy" value="private">
            </div>
        </div>
        <div class="inputbox">
            <div class="title">Tags</div><input type="text" id="tags" name="tags" placeholder="Comma or space separated list of tags">
        </div>
        <div class="div-row">
            <div class="left-half">
                <div class="tag-container" id="tag-container">

                </div>
            </div>
            <div class="right-half">
                <div class="div-row float-right">
                    <div class="field-label" id="bookmark-status">

                    </div>
                    <div class="field-value">
                        <input type="submit" class="submit-form-large float-right" value="Add">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?=$bookmark_container?>