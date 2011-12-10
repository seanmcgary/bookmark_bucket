<div class="inputbox">
    <div class="title">Buckets</div>
    <select name="bucket_select" id="bucket_select">
        <option value="-1">Select one or more Buckets...</option>
    <?php
        foreach($buckets as $bucket)
        {
            echo '<option value="'.$bucket['bucket_id'].'">'.$bucket['bucket_name'].'</option>';
        }
    ?>
    </select>
</div>
<div class="div-row">
    <div class="tag-container" id="selected_buckets"></div>
</div>