<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
 
class lib_controllers_login extends lib_controllers_baseController
{
    public $page;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->page->render('loginIndex_view', array());


    }

    

    public function logout()
    {
        unset($_SESSION['loggedIn']);
        session_destroy();

        redirect(site_url(''));
    }

    public function process_login($ajax = 'false')
    {
        $post = $this->input->post_array(array('username', 'password'));

        $res = $this->user_model->login_user($post['username'], $post['password']);

        if($res != null)
        {
            // login

            unset($res['password']);

            $_SESSION['loggedIn'] = $res;

            if($ajax == 'true')
            {
                echo json_encode(array('status' => 'true', 'redirect' => site_url('')));
            }
            else
            {
                redirect(site_url(''));
            }
        }
        else
        {
            // error
            if($ajax == 'true')
            {
                echo json_encode(array('status' => 'false', 'msg' => '<span class="failure">Bad username and password</span>'));
            }
            else
            {
                unset($post['password']);
                $_SESSION['login'] = array('form_data' => $post, 'msg' => '<span class="failure">Bad username and password</span>');

                redirect(site_url('login'));
            }
        }
    }


}