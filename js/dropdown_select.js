/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 12/8/11
 * Time: 3:01 PM
 * To change this template use File | Settings | File Templates.
 */
function DropDown_Select(select_box, tag_container){
    var self = this;

    self.select_box = select_box;
    self.tag_container = tag_container;

    self.init();
}

DropDown_Select.prototype = {
    init: function(){
        var self = this;
        self.select_box.live('change', function(){
            var selected_index = $(this).get(0).selectedIndex;
            var bucket_name = $(this).get(0).options[selected_index].text;
            var bucket_id = $(this).val();

            console.log(bucket_id);
            if(bucket_id != -1){
                self.tag_container.append($('<div class="tag bucket-tag" bucket_id="' + bucket_id + '"><span class="tag-content">' + bucket_name + '</span><div class="close">X</div></div>'));

                // reset the selected element to the default
                $(this).find('option:selected').removeAttr('selected');
                
                // remove the selected on from the list
                $(this).find('option[value="' + bucket_id + '"]').remove();
            }
        });

        self.tag_container.find('.bucket-tag > .close').live('click', function(){
            var tag = $(this).parent();

            var bucket_name = tag.find('span.tag-content').html();

            var bucket_id = tag.attr('bucket_id');

            self.select_box.append('<option value="' + bucket_id + '">' + bucket_name + '</option>');

            tag.remove();
        });
    },
    get_buckets: function(){
        var self = this;

        if(self.tag_container.children().length > 0){
            var buckets = [];

            for(var i = 0; i < self.tag_container.children().length; i++){
                buckets.push($(self.tag_container.children()[i]).attr('bucket_id'));
            }

            return buckets;

        } else {
            return [];
        }
    }
}