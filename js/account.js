/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
Ext.onReady(function(){});

$('document').ready(function(){

    $(function(){
        $('ul#account-categories li').each(function(item){
            console.log($(this).attr('id'));
            //console.log($('.bookmark-category-container ul'));
            if($(this).attr('class') == 'selected'){
                $('#bookmark-category-container').find('div[category="' + $(this).attr('id') + '"]').css('display', 'block');
            }
        });
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

    $('.delete-button').live('click', function(){
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

    $('#auto_fill').live('change', function(){
        if($(this).attr('checked') == 'checked'){
            $('#bucket_options').slideToggle();
        } else {
            $('#bucket_options').slideToggle();
        }
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

    $('#new_bucket').live('submit', function(){
        console.log('foobar');

        if($('#bucket_name').val().length > 0){

            var tag_list =  new Array();
            $('#tag-container .tag').each(function(index){
                console.log($(this).find('span').html());
                tag_list.push($(this).find('span').html());
            });

            console.log(tag_list);

            Ext.Ajax.request({
                url: new_bucket,
                form: 'new_bucket',
                success: function(response, opts){
                    var obj = Ext.decode(response.responseText);
                },
                failure: function(response, opts){

                },
                params: {tag_list: JSON.stringify(tag_list)}
            });
        } else {
            $('#new_bucket_status').html('<span class="failure">Please enter a name for your bucket</span>');
        }

        setTimeout(function(){
            $('#new_bucket_status').html('');
        }, 5000);

        return false;
    });
});
 

