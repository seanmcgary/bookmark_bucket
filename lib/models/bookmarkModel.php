<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */

class lib_models_bookmarkModel extends lib_models_baseModel
{
    public $geo;

    public function __construct()
    {
        parent::__construct();
        //$this->geo = lib_libraries_simpleGeoFactory::get_inst();

    }

    /**************************************************************************
     *
     * Global Bookmark utilities
     *
     *************************************************************************/

    public function insert_new_bookmark($bookmark_data, $user_id)
    {
        // check to see if the bookmark exists already based on URL
        $exists = $this->bookmark_exists_by_url($bookmark_data['url']);
        $user_tags = $bookmark_data['tags'];

        $user_bookmark = array(
            'user_id' => $user_id,
            'user_tags' => $user_tags,
            'privacy' => $bookmark_data['privacy'],
            'date_bookmarked' => time()
        );

        $url_data = null;

        // if it doesnt exist, make it exist
        if($exists == false)
        {
            unset($bookmark_data['privacy']);

            $bookmark_data['bookmark_id'] = $this->generate_id('bookmark_collection', 'bookmark_id');
            $bookmark_data['date_created'] = time();
            $bookmark_data['times_bookmarked'] = 1;
            $bookmark_data['times_clicked'] = 0;

            try
            {
                $this->bookmark_collection->insert($bookmark_data, true);
                $exists = $bookmark_data;
            }
            catch(MongoCursorException $e)
            {
                printr($e);
                return false;
            }
        }
        else
        {
            // since it exists, increment some counters
            $exists['times_bookmarked'] += 1;

            // add the new tags
            foreach($bookmark_data['tags'] as $tag)
            {
                if(!in_array($tag, $exists['tags']))
                {
                    //printr($tag);
                    $exists['tags'][] = $tag;
                }
            }

            $this->update_bookmark($exists);
            $bookmark_data = $exists;

        }

        $user_bookmark['bookmark_id'] = $bookmark_data['bookmark_id'];

        if($this->add_user_bookmark($user_bookmark) == true)
        {
            // add the bookmark to the bucket
            $this->bucket_model->auto_add_bookmark_to_bucket($bookmark_data['bookmark_id'], $user_tags);

            $bookmark_stat = array();

            //$geo = $this->geo->getContextFromIPAddress($_SERVER['REMOTE_ADDR']);

            $bookmark_stat['bookmark_id'] = $bookmark_data['bookmark_id'];
            $bookmark_stat['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $bookmark_stat['date_bookmarked'] = time();
            //$bookmark_stat['geo'] = $geo->address;

            $stat_res = $this->metrics_model->log_bookmark_insert($bookmark_stat);

            // return the new bookmark
            $bookmark = $this->get_bookmark_for_id_for_user($user_bookmark['bookmark_id'], $user_id);

            return $bookmark;
        }
        else
        {
            return true;
        }
    }

    public function add_user_bookmark($bookmark_data)
    {
        $bookmark = $this->get_bookmark_for_id_for_user($bookmark_data['bookmark_id'], $bookmark_data['user_id']);

        if($bookmark == null)
        {
            try
            {
                $this->user_bookmarks_collection->insert($bookmark_data);
                return true;
            }
            catch(MongoCursorException $e)
            {
                return false;
            }

        }
        else
        {
            return false;
        }
    }

    public function bookmark_exists_by_url($url)
    {

        $url = str_replace('http://', '', $url);
        $url = str_replace('http://www.', '', $url);
        $url = str_replace('https://', '', $url);
        $url = str_replace('https://www.', '', $url);
        $url = str_replace('www.', '', $url);

        //printr($url);

        $regex_search = new MongoRegex("/[http(s):\/\/(www.)]".$url."/");

        //printr($regex_search);

        $results = $this->bookmark_collection->find(array('url' => array('$regex' => $regex_search, '$options' => 'i')));

        $results = $this->get_one($results);

        //printr($results);

        if($results == null)
        {
            return false;
        }
        else
        {
            return $results;
        }
    }

    public function increment_bookmarked_count($id)
    {
        $bookmark = $this->get_bookmark_for_id($id);

        $bookmark['times_bookmarked'] += 1;

        try
        {
            $this->bookmark_collection->update(array('bookmark_id' => $bookmark['bookmark_id']), $bookmark);
        }
        catch(MongoCursorException $e)
        {
            return false;
        }
    }

    public function increment_clicked_count($id)
    {
        $bookmark = $this->get_bookmark_for_id($id);

        $bookmark['times_clicked'] += 1;

        try
        {
            $this->bookmark_collection->update(array('bookmark_id' => $bookmark['bookmark_id']), $bookmark);
        }
        catch(MongoCursorException $e)
        {
            return false;
        }
    }

    public function update_bookmark($bookmark_data)
    {
        unset($bookmark_data['_id']);

        try
        {
            $this->bookmark_collection->update(array('bookmark_id' => $bookmark_data['bookmark_id']), $bookmark_data);
        }
        catch(MongoCursorException $e)
        {
            return false;
        }
    }

    public function get_bookmark_for_id($bookmark_id)
    {
        $results = $this->bookmark_collection->find(array('bookmark_id' => $bookmark_id));

        return $this->get_one($results);
    }

    /**************************************************************************
     *
     * User specific bookmark actions
     *
     *************************************************************************/
    
    public function get_public_bookmarks_for_user($user_id)
    {
        $results = $this->user_bookmarks_collection->find(array('privacy' => 'public', 'user_id' => $user_id))->sort(array('date_created' => -1));

        $results = $this->get_array($results);

        return $this->get_user_bookmarks($results);
    }

    public function get_private_bookmarks_for_user($user_id)
    {
        $results = $this->user_bookmarks_collection->find(array('privacy' => 'private', 'user_id' => $user_id))->sort(array('date_created' => -1));

        $results = $this->get_array($results);

        return $this->get_user_bookmarks($results);

    }

    public function get_all_bookmarks_for_user($user_id)
    {
        $results = $this->user_bookmarks_collection->find(array('user_id' => $user_id))->sort(array('date_created' => -1));

        $results = $this->get_array($results);

        return $this->get_user_bookmarks($results);
    }

    public function get_bookmark_for_id_for_user($bookmark_id, $user_id)
    {
        $res = $this->user_bookmarks_collection->find(array('bookmark_id' => $bookmark_id, 'user_id' => $user_id));

        $res = $this->get_one($res);

        return $this->get_user_bookmark($res);
    }

    public function get_user_bookmarks($results)
    {
        foreach($results as &$res)
        {
            $res = $this->get_user_bookmark($res);
        }

        return $results;
    }

    public function get_user_bookmark($res)
    {
        if($res != null)
        {
            $bookmark = $this->get_bookmark_for_id($res['bookmark_id']);

            array_merge($res, $bookmark);

            return $bookmark;
        }
        else
        {
            return null;
        }

    }

    public function get_users_for_bookmark($bookmark_id)
    {
        $results = $this->user_collection->find(array('bookmarks' => $bookmark_id));

        return $this->get_array($results);
    }

    public function delete_user_bookmark_tags($bookmark_id, $user_id)
    {
        $this->bookmark_tags->remove(array('user_id' => $user_id, 'bookmark_id' => $bookmark_id));
    }

    public function delete_user_bookmark($bookmark_id, $user_id)
    {
        $this->user_bookmarks_collection->remove(array('bookmark_id' => $bookmark_id, 'user_id' => $user_id));

        $this->delete_user_bookmark_tags($bookmark_id, $user_id);
    }

    /**************************************************************************
     *
     * Global bookmark operations
     *
     **************************************************************************/

    // TODO - determine
    public function get_public_bookmarks()
    {
        //$results = $this->bookmark_collection->find(array('privacy' => 'public'))->sort(array('date_created' => -1));
        $results = $this->bookmark_collection->find()->sort(array('date_created' => -1));

        return $this->get_array($results);
    }

    public function get_recent_public_bookmarks()
    {
        //$results = $this->bookmark_collection->find(array('privacy' => 'public'))->sort(array('date_created' => -1));
        $results = $this->bookmark_collection->find()->sort(array('date_created' => -1));

        return $this->get_array($results);
    }

    public function get_popular_bookmarks()
    {
        //$results = $this->bookmark_collection->find(array('privacy' => 'public'))->sort(array('times_clicked' => -1));
        $results = $this->bookmark_collection->find()->sort(array('times_clicked' => -1));

        return $this->get_array($results);
    }

    /**
     * Gets ALL bookmarks
     * 
     * @return array
     */
    public function get_bookmarks()
    {
        // TODO - Fix this so that we can "paginate" the result sets
        $results = $this->bookmark_collection->find();

        return $this->get_array($results);
    }

    public function get_bookmark_by_url($url)
    {
        $results = $this->bookmark_collection->find(array('url' => $url));

        return $this->get_one($results);
    }

}
