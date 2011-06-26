<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */

class lib_models_tagModel extends lib_models_baseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_new_tag($tag)
    {
        $existing_tag = $this->get_tag($tag);

        if($existing_tag == null)
        {
            // create new tag
            $tag_data = array();
            $tag_data['tag'] = $tag;
            $tag_data['post_count'] = 1;
            $tag_data['date_created'] = time();

            //printr($tag_data);

            try
            {
                $this->tag_collection->insert($tag_data);

                return true;
            }
            catch(MongoCursorException $e)
            {
                return false;
            }
        }
        else
        {
            // increment the count
            $existing_tag['post_count'] = $existing_tag['post_count'] + 1;

            $this->update_tag($existing_tag);
        }
    }

    public function update_tag($tag_data)
    {
        unset($tag_data['_id']);
        try
        {
            $this->tag_collection->update(array('tag' => $tag_data['tag']), $tag_data);
            return true;
        }
        catch(MongoCursorException $e)
        {
            return false;
        }

    }

    public function get_tag($tag)
    {
        $results = $this->tag_collection->find(array('tag' => $tag));

        return $this->get_one($results);

    }

    public function get_all_tags()
    {
        $results = $this->tag_collection->find();

        return $this->get_array($results);
    }
}
