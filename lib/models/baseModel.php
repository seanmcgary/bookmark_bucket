<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_models_baseModel extends core_model
{
    public $mongo;

    public $models_to_load;

    public $user_collection;
    public $bookmark_collection;
    public $stats_collection;
    public $click_collection;
    public $tag_collection;
    public $invite_collection;
    public $bookmark_tags;
    public $bucket_collection;
    public $bucket_bookmarks_collection;
    public $user_bookmarks_collection;

    public $session;


    public $last_error;
    
    public function __construct()
    {
        parent::__construct();

        $this->mongo = lib_libraries_libMongoDB::get_connection('mongodb://'.$_SERVER['DATABASE_URL'], $_SERVER['DATABASE']);

        $this->session = core_loadFactory::get_inst('lib_libraries_session', 'session');

        $this->models_to_load = array(
                    array('collection_name' => 'user_collection', 'class_name' => 'users'),
                    array('collection_name' => 'bookmark_collection', 'class_name' => 'bookmarks'),
                    array('collection_name' => 'stats_collection', 'class_name' => 'bookmark_stats'),
                    array('collection_name' => 'click_collection', 'class_name' => 'click_log'),
                    array('collection_name' => 'tag_collection', 'class_name' => 'tags'),
                    array('collection_name' => 'invite_collection', 'class_name' => 'invites'),
                    array('collection_name' => 'bookmark_tags', 'class_name' => 'bookmark_tags'),
                    array('collection_name' => 'bucket_collection', 'class_name' => 'buckets'),
                    array('collection_name' => 'user_bookmarks_collection', 'class_name' => 'user_bookmarks'),
                    array('collection_name' => 'bucket_bookmarks_collection', 'class_name' => 'bucket_bookmarks')
            );

        foreach($this->models_to_load as $model)
        {
            $this->{$model['collection_name']} = $this->mongo->mongodb->{$model['class_name']};
        }
    }

    public function clear_all()
    {
        foreach($this->models_to_load as $model)
        {
            $this->{$model['collection_name']}->remove();
        }
    }

    public function get_item_for_id($collection, $id_field, $id)
    {
        $results = $this->{$collection}->find(array($id_field => $id));

        if($results->count() > 0)
        {

            $user = array();
            foreach($results as $res)
            {
                $user = $res;
            }

            return $user;
        }
        else
        {
            return false;
        }
    }

    public function generate_id($collection, $id_field)
    {
        $id = '';
        for($i = 0; $i < 9; $i++)
        {
            $id .= rand(0, 10);
        }

        $result = $this->get_item_for_id($collection, $id_field, $id);

        if($result != false)
        {
            // if it already exists, run it again
            return $this->generate_id($collection, $id_field);
        }
        else
        {
            return $id;
        }
    }

    public function get_one($mongo_results)
    {
        $results = null;

        if($mongo_results->count() > 0)
        {
            foreach($mongo_results as $res)
            {
                $results = $res;
            }
        }

        return $results;
    }

    public function get_array($mongo_results)
    {
        $results = array();

        if($mongo_results->count() >0)
        {
            foreach($mongo_results as $res)
            {
                $results[] = $res;
            }
        }

        return $results;
    }

    public function set_last_error($last_error)
    {
        $this->last_error = $last_error;
    }

    public function get_last_error()
    {
        return $this->last_error;
    }


}
