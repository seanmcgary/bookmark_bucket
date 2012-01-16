/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 1/15/12
 * Time: 5:43 PM
 * To change this template use File | Settings | File Templates.
 */

function show_modal(elem){
    elem.modal({
        opacity: 100,
        overlayCss: {
            background: 'rgba(0, 0, 0, .6)'
        }
    });
}

Ext.onReady(function(){});
$(document).ready(function(){

    $('#create_new_bookmark').live('click', function(){
        show_modal($('#new_bookmark_modal'));
    });

    $('#create_new_bucket').live('click', function(){
        show_modal($('#new_bucket_modal'));
    });

    var bucket_dropdown = new DropDown_Select($('#bucket_select'), $('#selected_buckets'));

    $('#toggle_new_bookmark').live('click', function(){
        $('#new_bookmark').slideToggle();
    });

    $('input[type="checkbox"]').checkbox({
        cls: 'jquery-checkbox',  /* checkbox  */
        empty: 'js/jquery-checkbox/empty.png'  /* checkbox  */
    });

    $('#url').live('blur', function() {
        if ($(this).val().length > 0) {

            var url = $(this).val().replace('http://', '');
            $('#bookmark-status').append('<img src="images/ajax-loader.gif">');

            Ext.Ajax.request({
                url: suggest_link_title,
                success: function(response, opts)
                {
                    var obj = Ext.decode(response.responseText);

                    if (obj.status == 'true')
                    {
                        $('#title').val(obj.title);
                        $('#bookmark-status').html('');

                    }
                },
                failure: function(response, opts)
                {

                },
                params: {url: url}
            });
        }

        return false;

    });

    $('#new_bookmark').live('submit', function() {

        if ($('#url').val().length > 0)
        {

            // get the tags
            var tag_list = new Array();
            $('#tag-container .tag').each(function(index) {
                console.log($(this).find('span').html());
                tag_list.push($(this).find('span').html());
            });

            Ext.Ajax.request({
                url: submit_new_bookmark,
                form: 'new_bookmark',
                success: function(response, opts)
                {
                    var obj = Ext.decode(response.responseText);

                    if (obj.status == 'true')
                    {
                        /*if ($('.bookmarks_list[category="yours"] li').length > 0) {
                            $(obj.bookmark).insertBefore($('.bookmarks_list[category="yours"] li')[0]);
                        } else {
                            $('.bookmarks_list[category="yours"]').append(obj.bookmark);
                        }*/

                        console.log(obj.user_bookmarks);

                        $.modal.close();

                        $('.bookmarks_list[category="yours"]').html(obj.user_bookmarks);

                        for(var i in obj.global_bookmarks){
                            if(i != 'your_bookmarks'){
                                $('.bookmarks_list[category="' + i.replace("_bookmarks", "") + '"]').html(obj.global_bookmarks[i]);
                            }
                        }


                        $('#tag-container .tag').remove();

                    }

                    if (obj.status == 'false')
                    {
                        $('#bookmark-status').html(obj.msg);

                        setTimeout(function() {
                            $('#bookmark-status').html('');
                        }, 3000);
                    }

                    $('#url').val('');
                    $('#title').val('');
                },
                failure: function(response, opts)
                {

                },
                params: {tag: JSON.stringify(tag_list), buckets: JSON.stringify(bucket_dropdown.get_buckets())}
            });
        }
        else
        {
            console.log('empty');
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

                    if(obj.status == 'true'){
                       console.log(obj.buckets);
                       $('#bucket-list').html(obj.buckets);

                       // TODO - reset the form
                    }
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