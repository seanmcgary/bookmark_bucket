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