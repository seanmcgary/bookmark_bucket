<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 11/15/11
 * Time: 5:39 PM
 * To change this template use File | Settings | File Templates.
 */
class lib_models_bucketModel extends lib_models_baseModel
{
    public function __construct()
    {
        parent::__construct();

        $this->bookmark_model = core_modelFactory::get_inst('lib_modes_bookmarkModel', 'bookmark_model');
    }

    /**
     * @param $bucket_data
     *      - bucket_name
     *      - bucket_description
     *      - auto_fill
     *      - public
     *      - tags
     *      - followers []
     *      - views - 0
     * @return void
     */
    public function create_bucket($bucket_data)
    {
        $bucket_data['bucket_id'] = $this->generate_id('bucket_collection', 'bucket_id');
        $bucket_data['date_created'] = time();
        $bucket_data['bookmarks'] = array();
        $bucket_data['views'] = 0;
        $bucket_data['followers'] = 0;
        $bucket_data['url'] = str_replace(" ", "_", preg_replace("/[!#$%^&*\\(\\)\\[\\]\\{\\}+=?><]/", "", strtolower($bucket_data['bucket_name'])));

        try
        {
            $this->bucket_collection->insert($bucket_data, true);

            return $bucket_data;
        }
        catch(MongoCursorException $e)
        {
            $this->last_error = $e;
            return false;
        }
    }

    public function delete_bucket($bucket_id)
    {
        try
        {
            $this->bucket_collection->remove(array('bucket_id' => $bucket_id));
            return true;
        }
        catch(MongoCursorException $e)
        {
            return false;
        }
    }

    public function delete_bucket_bookmarks($bucket_id)
    {
        try
        {
            $this->bucket_bookmarks_collection->remove(array('bucket_id' => $bucket_id));
            return true;
        }
        catch(MongoCursorException $e)
        {
            return false;
        }
    }

    public function increment_bucket_view($bucket_id)
    {
        $bucket = $this->get_bucket_raw_for_id($bucket_id);

        $bucket['views']++;

        $this->update_bucket($bucket);
    }

    public function get_bucket_raw_for_id($bucket_id)
    {
        $res = $this->bucket_collection->find(array('bucket_id' => $bucket_id));

        return $this->get_one($res);
    }

    public function get_bucket_full_for_id($bucket_id)
    {
        $res = $this->bucket_collection->find(array('bucket_id' => $bucket_id));

        $bucket = $this->get_one($res);

        if($bucket != null)
        {
            $bucket = $this->fill_bucket($bucket);
            return $bucket;
        }
        else
        {
            return null;
        }
    }

    public function get_all_user_buckets($user_id, $sort = array('date_created' => -1))
    {
        $res = $this->bucket_collection->find(array('user_id' => $user_id))->sort($sort);

        $buckets = $this->get_array($res);

        return $this->fetch_bookmarks($buckets);
    }

    public function get_user_buckets_public($user_id)
    {
        $res = $this->bucket_collection->find(array('user_id' => $user_id, 'public' => true));

        $buckets = $this->get_array($res);

        return $this->fetch_bookmarks($buckets);
    }

    public function get_user_buckets_private($user_id)
    {
        $res = $this->bucket_collection->find(array('user_id' => $user_id, 'public' => false));

        $buckets = $this->get_array($res);

        return $this->fetch_bookmarks($buckets);
    }

    public function auto_add_bookmark_to_bucket($bookmark_id, $tags)
    {
        if(!empty($tags))
        {
            $tag_search = array();
            foreach($tags as $tag)
            {
                $tag_search[] = array('tags' => $tag);
            }

            $query = array('auto_fill' => true, '$or' => $tag_search);

            $buckets = $this->bucket_collection->find($query);

            $buckets = $this->get_array($buckets);

            foreach($buckets as $bucket)
            {
                if(!in_array($bookmark_id, $bucket['bookmarks']))
                {
                    $bucket['bookmarks'][] = $bookmark_id;

                    $this->update_bucket($bucket);
                }
            }
        }
    }

    public function add_bookmark_to_buckets($bookmark_id, $buckets)
    {
        foreach($buckets as $bucket)
        {
            $this->add_bookmark_to_bucket($bucket, $bookmark_id);
        }
    }

    public function add_bookmark_to_bucket($bucket_id, $bookmark_id)
    {
        $bucket = $this->get_bucket_raw_for_id($bucket_id);

        if(!in_array($bookmark_id, $bucket['bookmarks']))
        {
            $bucket['bookmarks'][] = $bookmark_id;

            $this->update_bucket($bucket);
            return true;
        }
        else
        {

            return false;
        }
    }

    public function update_bucket($bucket_data)
    {
        try
        {
            $this->bucket_collection->update(array('bucket_id' => $bucket_data['bucket_id']), $bucket_data);
            return true;
        }
        catch(MongoCursorException $e)
        {
            $this->set_last_error($e);
            return false;
        }
    }

    public function remove_bookmark_from_bucket_by_user($bookmark_id, $user_id)
    {
        $buckets = $this->get_all_user_buckets($user_id);

        foreach($buckets as $bucket)
        {
            $this->remove_bookmark_from_bucket($bucket['bucket_id'], $bookmark_id);
        }
    }

    public function remove_bookmark_from_bucket($bucket_id, $bookmark_id)
    {
        $bucket = $this->get_bucket_raw_for_id($bucket_id);

        if(in_array($bookmark_id, $bucket['bookmarks']))
        {
            $index_to_delete = null;
            for($i = 0; $i < sizeof($bucket['bookmarks']); $i++)
            {
                if($bucket['bookmarks'][$i] == $bookmark_id)
                {
                    $index_to_delete = $i;
                    break;
                }
            }

            unset($bucket['bookmarks'][$index_to_delete]);

            $bucket['bookmarks'] = array_values($bucket['bookmarks']);

            return $this->update_bucket($bucket);
        }
        else
        {
            return null;
        }
    }

    public function fetch_bookmarks($buckets)
    {
        foreach($buckets as &$bucket)
        {
            $bucket = $this->fill_bucket($bucket);
        }

        return $buckets;
    }

    public function fill_bucket($bucket)
    {
        $bookmarks = array();
        foreach($bucket['bookmarks'] as $bookmark)
        {
            $bookmarks[] = $this->bookmark_model->get_bookmark_for_id_for_user($bookmark, $bucket['user_id']);
        }

        $bucket['bookmarks'] = $bookmarks;

        return $bucket;
    }


}