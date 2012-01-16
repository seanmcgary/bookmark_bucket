/**
 * @author Sean McGary <sean@seanmcgary.com>
 */


Ext.onReady(function() {});

$(document).ready(function() {

    $(function() {
        $('ul#bookmark-categories li').each(function(item) {
            console.log($(this).attr('id'));
            //console.log($('.bookmark-category-container ul'));
            if ($(this).attr('class') == 'selected') {

                $('#bookmark-category-container').find('.list-container[category="' + $(this).attr('id') + '"]').css('display', 'block');
            }
        });
    });

    $('a[link-type="external"]').live('click', function() {
        var link = $(this).attr('href');
        Ext.Ajax.request({
            url: log_click,
            success: function(rsponse, opts) {
                window.location = link;
            },
            failure: function(response, opts) {

            },
            params: {bookmark_id: $(this).attr('bookmark_id')}
        });

        return false;

    });

    $('ul#bookmark-categories li').live('click', function() {
        if ($(this).attr('class') != 'selected') {

            $('#bookmark-category-container').find('.list-container').each(function(index) {
                if ($(this).css('display') == 'block') {
                    $(this).css('display', 'none');
                }
            });

            $('ul#bookmark-categories li[class="selected"]').each(function() {
                $(this).removeClass('selected');
            });

            //$('#bookmark-category-container').find('ul[category="' + $(this).attr('id') + '"]').css('display', 'block');
            $('#bookmark-category-container').find('.list-container[category="' + $(this).attr('id') + '"]').css('display', 'block');
            $(this).addClass('selected');
        }

        return false;

    });

    $('.icon img.bookmark-icon').mouseenter(function() {
        $(this).attr('src', 'images/bookmark_1_icon&16_blue.png');
    });

    $('.icon img.bookmark-icon').mouseleave(function() {

        if ($(this).attr('src') != 'images/ajax-loader.gif') {
            $(this).attr('src', 'images/bookmark_1_icon&16_gray.png');
        }

    });

    $('.icon img.bookmark-icon').live('click', function(event) {
        var bookmark_id = $(this).attr('bookmark_id');
        var self = $(this);
        $(this).attr('src', 'images/ajax-loader.gif');

        var icon = $(this);

        Ext.Ajax.request({
            url: bookmark_existing,
            success: function(response, opts) {
                var obj = Ext.decode(response.responseText);

                if (obj.status == 'true') {
                    self.unbind('mouseleave');
                    //console.log(icon);
                    icon.removeClass('bookmark-icon');
                    icon.attr('src', 'images/bookmark_1_icon&16_blue.png');

                    $('.bookmarks_list[category="yours"]').html('');
                    $('.bookmarks_list[category="yours"]').append(obj.user_bookmarks);

                } else {
                    icon.attr('src', 'images/bookmark_1_icon&16_gray.png');
                }
            },
            failure: function(response, opts) {

            },
            params: {bookmark_id: bookmark_id}
        });

        return false;
    });
});
