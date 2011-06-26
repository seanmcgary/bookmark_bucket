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
        $this->geo = lib_libraries_simpleGeoFactory::get_inst();

    }

    public function insert_new_bookmark($bookmark_data, $user_id)
    {
        $bookmark_data['url'] = 'http://'.$bookmark_data['url'];
        $exists = $this->bookmark_exists($bookmark_data['url']);

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
            }
            catch(MongoCursorException $e)
            {
                printr($e);
                return false;
            }
        }
        else
        {
            //printr($exists);
            $exists['times_bookmarked'] += 1;

            foreach($bookmark_data['tags'] as $tag)
            {
                if(!in_array($tag, $exists['tags']))
                {
                    //printr($tag);
                    $exists['tags'][] = $tag;
                }
            }

            //$this->increment_bookmarked_count($exists['bookmark_id']);
            $this->update_bookmark($exists);
            $bookmark_data = $exists;
        }

        $user = $this->user_model->get_user_for_id($user_id);

        //printr($user);

        if(!in_array($bookmark_data['bookmark_id'], $user['bookmarks']))
        {
            unset($user['_id']);

            $user['bookmarks'][] = $bookmark_data['bookmark_id'];

            $res = $this->user_model->update_user($user);

            $bookmark_stat = array();

            $geo = $this->geo->getContextFromIPAddress($_SERVER['REMOTE_ADDR']);

            $bookmark_stat['bookmark_id'] = $bookmark_data['bookmark_id'];
            $bookmark_stat['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $bookmark_stat['date_bookmarked'] = time();
            $bookmark_stat['geo'] = $geo->address;

            //printr($bookmark_stat);

            $stat_res = $this->metrics_model->log_bookmark_insert($bookmark_stat);


            if($res != false)
            {
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

    public function bookmark_exists($url)
    {
        $results = $this->bookmark_collection->find(array('url' => $url, 'privacy' => 'public'));

        $results = $this->get_one($results);

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
