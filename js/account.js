/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
Ext.onReady(function(){});

function show_default(){
    $('ul#account-categories li').each(function(item){
        //console.log($('.bookmark-category-container ul'));
        if($(this).attr('class') == 'selected'){
            var elem = $('#bookmark-category-container').find('div[category="' + $(this).attr('id') + '"]');
            elem.css('display', 'block');
        }

    });
}

$('document').ready(function(){
    var location = document.location.href.split("/#");


    $(function(){
        if(location.length > 0 && location[1] != ''){
            $('.account_element').each(function(){
                $(this).css('display', 'none');
                $(this).attr('class', $(this).attr('class').replace('selected', ''));
            })

            var selected = $('#bookmark-category-container').find('div[category="' + location[1] + '"]');

            if(selected.length > 0){
                selected.css('display', 'block');
                selected.addClass('selected');
            } else {
                show_default();
            }
        } else {
            show_default();
        }

    });

    $('input[type="checkbox"]').each(function(item){
        if($(this).attr('toggle') == 'true'){
            $(this).checkbox({
                cls: 'jquery-checkbox',  /* checkbox  */
                empty: 'js/jquery-checkbox/empty.png'  /* checkbox  */
            });
        }
    });

    $('#edit-account').live('submit', function(){
        var form_id = $(this).attr('id');
        $('#edit-account-status').html('<span class="working">Updating...</span>');
        Ext.Ajax.request({
            url: edit_account,
            form: form_id,
            success: function(response, opts){
                var obj = Ext.decode(response.responseText);

                if(obj.status == 'true'){

                    $('#edit-account-status').html(obj.msg);

                } else if(obj.status == 'false'){

                    $('#edit-account-status').html(obj.msg);
                    
                } else {
                    // do nothing
                    $('#edit-account-status').html('');
                }

                setTimeout(function(){
                    $('#edit-account-status').html('');
                }, 3000);

            },
            failure: function(response, opts){

            }
        });

        return false;
    });

    $('input[button-type="delete-bookmark"]').live('click', function(){
        var id = $(this).attr('bookmark_id');

        Ext.Ajax.request({
            url: delete_bookmark,
            success: function(response, opts){
                var obj = Ext.decode(response.responseText);

                if(obj.status == 'true'){
                    $('ul.manage-bookmarks_list li#' + id).remove();
                }

            },
            failure: function(response, opts){

            },
            params: {bookmark_id: id}
        });

        return false;
    });

    $('input[button-type="delete-bucket"]').live('click', function(){
            var id = $(this).attr('bucket_id');

            Ext.Ajax.request({
                url: delete_bucket,
                success: function(response, opts){
                    var obj = Ext.decode(response.responseText);

                    if(obj.status == 'true'){
                        $('ul#bucket-list li#' + id).remove();
                    }

                },
                failure: function(response, opts){

                },
                params: {bucket_id: id}
            });

            return false;
        });

    $('ul#account-categories li').live('click', function(){
        if($(this).attr('class') != 'selected'){

            $('#bookmark-category-container').find('.account_element').each(function(index){
                //console.log($(this));
                if($(this).css('display') == 'block'){
                    $(this).css('display', 'none');
                }
            });

            $('ul#account-categories li[class="selected"]').each(function(){
                $(this).removeClass('selected');
            });

            $('#bookmark-category-container').find('div[category="' + $(this).attr('id') + '"]').css('display', 'block');

            $(this).addClass('selected');
        }

        return false;

    });

    $('#bookmark_file').live('change', function(event){
        var files = event.target.files;

        for(var i = 0; i < files.length; i++){
            console.log(files[i]);

            var file = files[i];

            if(file.type == 'text/html'){

                var reader = new FileReader();

                reader.readAsText(files[i]);

                reader.onload = function(event){
                    $(event.target.result).find('DT > A').each(function(index){
                        var row = $('<tr></tr>');
                        var import_box = $('<td><input type="checkbox" class="import-toggle"></td>');
                        var bookmark = $('<td></td>');
                        bookmark.append($(this));
                        var li = $('<li></li>');

                        row.append(import_box);
                        row.append(bookmark);

                        $('#import-bookmarks-table').append(row);
                    });
                }
            }
        }
    });
});
 

