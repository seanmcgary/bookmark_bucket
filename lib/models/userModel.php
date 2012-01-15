<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_models_userModel extends lib_models_baseModel
{
    
    public function __construct()
    {
        parent::__construct();

    }

    public function insert_new_user($user_data)
    {
        $user_data['password'] = sha1($user_data['password']);
        $user_data['user_id'] = $this->generate_id('user_collection', 'user_id');
        $user_data['date_created'] = time();
        $user_data['sync_key'] = $this->generate_sync_key();
        $user_data['num_invites'] = 5;
        $user_data['followers'] = array();
        $user_data['views'] = 0;

        $email_hash = md5(strtolower(trim($user_data['email'])));

        $user_data['avatar'] = 'http://www.gravatar.com/avatar/'.$email_hash.'?d=mm';

        try
        {
            $this->user_collection->insert($user_data, true);

            return true;
        }
        catch(MongoCursorException $e)
        {
            return $e;
        }
    }

    public function increment_user_view($user_id)
    {
        $user = $this->get_user_for_id($user_id);

        $user['views'] += 1;

        printr($user);

        $this->update_user($user);
    }

    public function api_auth_user($username, $password)
    {
        $password = sha1($password);

        $res = $this->user_collection->find(array('username' => $username, 'password' => $password));

        $user = $this->get_one($res);

        if($user != null)
        {
            unset($user['password']);
        }

        return $user;

    }


    public function login_user($username, $password)
    {
        $password = sha1($password);

        $res = $this->user_collection->find(array('username' => $username, 'password' => $password));

        $user = $this->get_one($res);

        if($user != null)
        {
            unset($user['password']);
        }

        return $user;
    }

    public function get_detailed_user_for_username($username)
    {
        $user = $this->get_user_for_username($username);

        if($user != null)
        {
            $user['bookmarks'] = $this->bookmark_model->get_public_bookmarks_for_user($user['user_id']);
            $user['buckets'] = $this->bucket_model->get_user_buckets_public($user['user_id']);
        }

        return $user;
    }

    public function get_user_for_id($user_id, $with_bookmarks = false)
    {
        $results = $this->user_collection->find(array('user_id' => $user_id));

        if($with_bookmarks == true)
        {
            $user['bookmarks'] = $this->bookmark_model->get_all_bookmarks_for_user($user_id);
        }
        else
        {
            return $this->get_one($results);
        }

    }

    public function get_user_for_username($username)
    {
        $results = $this->user_collection->find(array('username' => $username));

        return $this->get_one($results);
    }

    public function get_user_for_email($email)
    {
        $results = $this->user_collection->find(array('email' => $email));

        return $this->get_one($results);
    }

    public function update_user($user_data)
    {
        try
        {
            $this->user_collection->update(array('user_id' => $user_data['user_id']), $user_data);
            return true;
        }
        catch(MongoCursorException $e)
        {
            return $e;
        }
    }

    public function get_user_for_sync_key($sync_key)
    {
        $results = $this->user_collection->find(array('sync_key' => $sync_key));

        return $this->get_one($results);
    }

    public function generate_sync_key()
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $id = '';
        for($i = 0; $i < 16; $i++)
        {
            $id .= $chars[rand(0, 61)];
        }

        $result = $this->get_user_for_sync_key($id);

        if($result != false)
        {
            // if it already exists, run it again
            return $this->generate_sync_key();
        }
        else
        {
            return $id;
        }
    }

    public function delete_bookmark($bookmark_id, $user_id)
    {
        $bookmark = $this->bookmark_model->get_bookmark_for_id($bookmark_id);

        if($bookmark != null)
        {
            $this->bookmark_model->delete_user_bookmark($bookmark_id, $user_id);

            $this->bucket_model->remove_bookmark_from_bucket_by_user($bookmark_id, $user_id);

            // decrement the bookmark count
            $this->bookmark_model->decrement_bookmarked_count($bookmark_id);

            return true;
        }
        else
        {
            return false;
        }

    }



}
