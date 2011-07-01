/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
Ext.onReady(function(){});

$(document).ready(function(){

    $(function(){
        $('ul#bookmark-categories li').each(function(item){
            console.log($(this).attr('id'));
            //console.log($('.bookmark-category-container ul'));
            if($(this).attr('class') == 'selected'){
                $('#bookmark-category-container').find('ul[category="' + $(this).attr('id') + '"]').css('display', 'block');
            }
        });
    });


    $('#url').live('blur', function(){
        if($(this).val().length > 0){

            var url = $(this).val().replace('http://', '');
            
            Ext.Ajax.request({
                url: suggest_link_title,
                success: function(response, opts)
                {
                    var obj = Ext.decode(response.responseText);

                    if(obj.status == 'true')
                    {
                        $('#title').val(obj.title);
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

    $('#new_bookmark').live('submit', function(){

        if($('#url').val().length > 0)
        {

            // get the tags
            var tag_list =  new Array();
            $('#tag-container .tag').each(function(index){
                console.log($(this).find('span').html());
                tag_list.push($(this).find('span').html());
            });

            Ext.Ajax.request({
                url: submit_new_bookmark,
                form: 'new_bookmark',
                success: function(response, opts)
                {
                    var obj = Ext.decode(response.responseText);

                    if(obj.status == 'true')
                    {
                        $('.bookmarks_list[category="yours"]').append(obj.bookmark);

                        $('#tag-container .tag').remove();

                    }

                    if(obj.status == 'false')
                    {
                        $('#bookmark-status').html(obj.msg);

                        setTimeout(function(){
                            $('#bookmark-status').html('');
                        }, 3000);
                    }

                    $('#url').val('');
                    $('#title').val('');
                },
                failure: function(response, opts)
                {

                },
                params: {tag: JSON.stringify(tag_list)}
            });
        }
        else
        {
            console.log('empty');
        }

        return false;
    });

    $('a[link-type="external"]').live('click', function(){
        var link = $(this).attr('href');
        Ext.Ajax.request({
            url: log_click,
            success: function(rsponse, opts){
                window.location = link;
            },
            failure: function(response, opts){

            },
            params: {bookmark_id: $(this).attr('bookmark_id')}
        });

        return false;
        
    });

    $('input#tags').live('keydown', function(event){

        if(event.which == 32 || event.which == 188 || event.which == 13){
            event.preventDefault();

            if($(this).val().length > 1){
                $('#tag-container').append($('<div class="tag"><span class="tag-content">' + $(this).val() + '</span><div class="close">X</div></div>'));
                $(this).val('');
            }
        }
    });

    $('.tag .close').live('click', function(){

        $(this).parent().remove();
        

        return false;
    });

    $('ul#bookmark-categories li').live('click', function(){
        if($(this).attr('class') != 'selected'){

            $('#bookmark-category-container').find('ul').each(function(index){
                if($(this).css('display') == 'block'){
                    $(this).css('display', 'none');
                }
            });

            $('ul#bookmark-categories li[class="selected"]').each(function(){
                $(this).removeClass('selected');
            });

            $('#bookmark-category-container').find('ul[category="' + $(this).attr('id') + '"]').css('display', 'block');

            $(this).addClass('selected');
        }

        return false;

    });
});