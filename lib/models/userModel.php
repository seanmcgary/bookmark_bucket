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
        $user_data['bookmarks'] = array();
        $user_data['sync_key'] = $this->generate_sync_key();

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

    public function get_user_for_id($user_id)
    {
        $results = $this->user_collection->find(array('user_id' => $user_id));

        return $this->get_one($results);
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

    public function get_user_bookmarks($user_id)
    {
        $user = $this->get_user_for_id($user_id);

        //printr($user);

        $bookmarks = array();

        foreach($user['bookmarks'] as $bookmark)
        {
            $b = $this->bookmark_model->get_bookmark_for_id($bookmark);
            //printr($bookmark);
            $bookmarks[] = $b;
        }

        return $bookmarks;
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
        $user = $this->get_user_for_id($user_id);

        printr($user);

        if(in_array($bookmark_id, $user['bookmarks']))
        {
            $count = 0;
            $index = null;
            foreach($user['bookmarks'] as &$bookmark)
            {
                if($bookmark == $bookmark_id)
                {
                    $index = $count;
                }

                $count++;
            }

            if($index != null)
            {
                unset($user['bookmarks'][$index]);

                $user['bookmarks'] = array_values($user['bookmarks']);

                return true;
            }

        }

        return false;
    }



}
