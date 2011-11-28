Ext.onReady(function() {});

$(document).ready(function() {

    $(function() {
        $('ul#bookmark-categories li').each(function(item) {
            console.log($(this).attr('id'));
            //console.log($('.bookmark-category-container ul'));
            if ($(this).attr('class') == 'selected') {
                $('#bookmark-category-container').find('ul[category="' + $(this).attr('id') + '"]').css('display', 'block');
            }
        });
    });

    $('ul#bookmark-categories li').live('click', function() {
        if ($(this).attr('class') != 'selected') {

            $('#bookmark-category-container').find('ul').each(function(index) {
                if ($(this).css('display') == 'block') {
                    $(this).css('display', 'none');
                }
            });

            $('ul#bookmark-categories li[class="selected"]').each(function() {
                $(this).removeClass('selected');
            });

            $('#bookmark-category-container').find('ul[category="' + $(this).attr('id') + '"]').css('display', 'block');

            $(this).addClass('selected');
        }

        return false;

    });

});