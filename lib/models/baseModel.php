<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_models_baseModel extends core_model
{
    public $mongo;
    public $user_collection;
    public $bookmark_collection;
    public $stats_collection;
    public $click_collection;
    public $tag_collection;
    
    public function __construct()
    {
        parent::__construct();

        $this->mongo = lib_libraries_libMongoDB::get_connection('mongodb://seanmcgary.com:27017', 'bookmarks');

        $this->user_collection = $this->mongo->mongodb->{"users"};
        $this->bookmark_collection = $this->mongo->mongodb->{"bookmarks"};
        $this->stats_collection = $this->mongo->mongodb->{"bookmark_stats"};
        $this->click_collection = $this->mongo->mongodb->{"click_log"};
        $this->tag_collection = $this->mongo->mongodb->{"tags"};
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

        if($mongo_results->count() >0)
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


}
