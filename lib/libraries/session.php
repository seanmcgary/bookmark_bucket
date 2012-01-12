<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 11/9/11
 * Time: 7:35 PM
 * To change this template use File | Settings | File Templates.
 */
class lib_libraries_session
{
    private $redis;
    public function __construct()
    {
        $this->redis = core_loadFactory::get_inst('lib_libraries_libRedis', 'redis');
    }

    public function session_exists()
    {
        if(!isset($_SESSION['loggedIn']))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function get_session_attr($attr)
    {
        if($this->session_exists())
        {
            $session = $this->get_session();

            if(array_key_exists($attr, $session))
            {
                return $session[$attr];
            }
            else
            {
                return null;
            }
        }
        else
        {
            return null;
        }
    }

    public function get_session()
    {
        $session_id = $_SESSION['loggedIn']['session_id'];

        $session = $this->redis->conn->get($session_id);

        if($session != false)
        {
            $session = json_decode($session, true);
            //printr($session);
            return $session;
        }
        else
        {
            return null;
        }
    }

    public function get_session_id()
    {
        return $_SESSION['loggedIn']['session_id'];
    }

    public function get_and_update()
    {
        $session = $this->get_session();

        if($session != null)
        {
            $this->update_exprire_time($session);
        }

        return $session;
    }

    public function create_session($user_data)
    {

        $session = $user_data;

        $session['expire_time'] = strtotime("+1000 minutes", time());

        $session_id = sha1($user_data.time());

        $session['session_id'] = $session_id;

        $_SESSION['loggedIn'] = $session;

        $this->redis->conn->set($session_id, json_encode($session));

    }

    public function update_session_data($user_data)
    {
        if($this->session_exists())
        {
            $session = $this->get_session();
            foreach($user_data as $key => $val)
            {
                $session[$key] = $val;
            }
        }
    }

    public function update_exprire_time($session)
    {
        $session['expire_time'] = strtotime('+10 minutes', time());

        $this->redis->conn->set($session['session_id'], $session);
    }

    public function destroy_session($session_id)
    {
        unset($_SESSION['loggedIn']);
        session_destroy();

        $this->redis->conn->delete($session_id);
    }
}