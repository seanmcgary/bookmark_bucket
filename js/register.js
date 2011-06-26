/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
Ext.onReady(function(){});

$(document).ready(function(){

    $('#register').live('submit', function(){
        var incomplete = false;
        $(this).find('input').each(function(index){
            if($(this).val().length == 0){
                incomplete = true;
                $(this).css('border', '1px solid red');
            } else {
                $(this).css('border', '1px solid rgba(0, 0, 0, .1)');
            }
        });

        if(incomplete == true)
        {
            $('#register-status').append('<span class="failure">Please fill in all fields</span>');
            return false;
        }
    });
});