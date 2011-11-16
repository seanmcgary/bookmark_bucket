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
    }

    /**
     * @param $bucket_data
     *      - bucket_name
     *      - bucket_description
     *      - auto_fill
     *      - public
     *      - tags
     * @return void
     */
    public function create_bucket($bucket_data)
    {
        $bucket_data['bucket_id'] = $this->generate_id('bucket_collection', 'bucket_id');
        $bucket_data['date_created'] = time();
        $bucket_data['bookmarks'] = array();

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

    public function get_all_user_buckets($user_id)
    {
        $res = $this->bucket_collection->find(array('user_id' => $user_id));

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
            $bookmarks[] = $this->bookmark_model->get_bookmark_for_id($bookmark);
        }

        $bucket['bookmarks'] = $bookmarks;

        return $bucket;
    }


}