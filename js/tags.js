/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
$(document).ready(function(){
    $('input#tags').live('keydown', function(event){

        if(event.which == 32 || event.which == 188 || event.which == 13){
            event.preventDefault();

            if($(this).val().length > 1){
                $('#tag-container').append($('<div class="tag bookmark-tag"><span class="tag-content">' + $(this).val() + '</span><div class="close">X</div></div>'));
                $(this).val('');
            }
        }
    });

    $('.bookmark-tag .close').live('click', function(){

        $(this).parent().remove();

        return false;
    });
});