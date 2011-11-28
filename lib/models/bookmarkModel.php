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

    public function insert_new_bookmark($bookmark_data, $user_id)
    {
        $exists = $this->bookmark_exists($bookmark_data['url']);
        $user_tags = $bookmark_data['tags'];

        $url_data = null;

        if($exists == false)
        {

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
            $exists['times_bookmarked'] += 1;

            foreach($bookmark_data['tags'] as $tag)
            {
                if(!in_array($tag, $exists['tags']))
                {
                    //printr($tag);
                    $exists['tags'][] = $tag;
                }
            }

            $this->update_bookmark($exists);
            $original_bookmark_data =
            $bookmark_data = $exists;

        }

        // add bookmark to buckets, if any
        $this->bucket_model->auto_add_bookmark_to_bucket($bookmark_data['bookmark_id'], $user_tags);

        $user = $this->user_model->get_user_for_id($user_id);
        
        $this->tag_model->insert_tags_for_user_bookmark($bookmark_data['tags'], $_SESSION['loggedIn']['user_id'], $exists['bookmark_id']);

        // check to see if its in the user object bookmarks list
        if(!in_array($bookmark_data['bookmark_id'], $user['bookmarks']))
        {
            unset($user['_id']);

            $user['bookmarks'][] = $bookmark_data['bookmark_id'];

            $res = $this->user_model->update_user($user);

            $bookmark_stat = array();

            //$geo = $this->geo->getContextFromIPAddress($_SERVER['REMOTE_ADDR']);

            $bookmark_stat['bookmark_id'] = $bookmark_data['bookmark_id'];
            $bookmark_stat['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $bookmark_stat['date_bookmarked'] = time();
            //$bookmark_stat['geo'] = $geo->address;

            $stat_res = $this->metrics_model->log_bookmark_insert($bookmark_stat);


            if($res != false)
            {
                $bookmark_data['user_tags'] = $this->get_user_tags_for_bookmark($user_id, $bookmark_data['bookmark_id']);
                return $bookmark_data;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
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

    public function get_user_tags_for_bookmark($user_id, $bookmark_id)
    {
        $results = $this->bookmark_tags->find(array('bookmark_id' => $bookmark_id, 'user_id' => $user_id));

        $results = $this->get_one($results);

        if($results != null)
        {
            return $results['tags'];
        }
        else
        {
            return array();
        }
    }

    public function get_bookmark_for_id_with_user_tags($user_id, $bookmark_id)
    {
        $bookmark = $this->get_bookmark_for_id($bookmark_id);

        $bookmark['user_tags'] = $this->get_user_tags_for_bookmark($user_id, $bookmark_id);

        return $bookmark;
    }

    public function get_public_bookmarks_for_user($user_id)
    {
        $results = $this->bookmark_collection->find(array('privacy' => 'public', 'user_id' => $user_id))->sort(array('date_created' => -1));

        $results = $this->get_array($results);

        $bookmarks = array();

        foreach($results as $bookmark)
        {
            $b = $this->bookmark_model->get_bookmark_for_id_with_user_tags($user_id, $bookmark);
            //printr($bookmark);
            $bookmarks[] = $b;
        }

        return $bookmarks;
    }

    public function get_all_bookmarks_for_user($user_id)
    {
        $results = $this->bookmark_collection->find(array('user_id' => $user_id))->sort(array('date_created' => -1));

        $results = $this->get_array($results);

        $bookmarks = array();

        foreach($results as $bookmark)
        {
            $b = $this->bookmark_model->get_bookmark_for_id_with_user_tags($user_id, $bookmark);
            //printr($bookmark);
            $bookmarks[] = $b;
        }

        return $bookmarks;
    }

    public function get_public_bookmarks()
    {
        $results = $this->bookmark_collection->find(array('privacy' => 'public'))->sort(array('date_created' => -1));

        return $this->get_array($results);
    }

    public function get_new_public_bookmarks()
    {
        $results = $this->bookmark_collection->find(array('privacy' => 'public'))->sort(array('date_created' => -1));

        return $this->get_array($results);
    }

    public function get_popular_bookmarks()
    {
        $results = $this->bookmark_collection->find(array('privacy' => 'public'))->sort(array('times_clicked' => -1));

        return $this->get_array($results);
    }

    public function get_bookmark_for_id($bookmark_id)
    {
        $results = $this->bookmark_collection->find(array('bookmark_id' => $bookmark_id));

        return $this->get_one($results);
    }

    public function get_bookmarks()
    {
        $results = $this->bookmark_collection->find();

        return $this->get_array($results);
    }

    public function get_users_for_bookmark($bookmark_id)
    {
        $results = $this->user_collection->find(array('bookmarks' => $bookmark_id));

        return $this->get_array($results);
    }

    public function get_bookmark_by_url($url)
    {
        $results = $this->bookmark_collection->find(array('url' => $url));

        return $this->get_one($results);
    }

    public function delete_user_bookmark_tags($bookmark_id, $user_id)
    {
        $this->bookmark_tags->remove(array('user_id' => $user_id, 'bookmark_id' => $bookmark_id));
    }

    public function bookmark_exists($url)
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





}
