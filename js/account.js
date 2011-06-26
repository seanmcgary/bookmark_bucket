/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
Ext.onReady(function(){});

$(document).ready(function(){

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
});
 

