<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
 
class lib_controllers_account extends lib_controllers_baseController
{
    public function __construct()
    {
        parent::__construct();

        if($this->session->session_exists() == false)
        {
            redirect(site_url('login'));
        }
    }

    public function index()
    {

        $data['account_details'] = $this->user_model->get_user_for_id($this->session->get_session_attr('user_id'));

        $data['user_fields'] = array('username' => 'Username', 'fullname' => 'Full Name', 'email' => 'Email');

        $data['user_bookmarks'] = $this->bookmark_model->get_all_bookmarks_for_user($this->session->get_session_attr('user_id'));

        $data['bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $data['user_bookmarks'], 'user_bookmarks' => $data['user_bookmarks'], 'account' => true), true);

        $buckets = $this->bucket_model->get_all_user_buckets($this->session->get_session_attr('user_id'));

        $data['buckets'] = $this->load->view('presenters/account/bucket_list', array('buckets' => $buckets, 'account' => true), true);

        $data['new_bucket_form'] = $this->load->view('presenters/new_bucket_form', array(), true);

        $this->page->load_javascript(site_url('js/account.js'));
        $this->page->load_javascript(site_url('js/tags.js'));

        $this->page->render('accountIndex_view', $data, 'template/leftCol_account_view');

    }

    public function edit_account()
    {
        $post = $this->input->post_array(array('username', 'email', 'fullname', 'password', 'confirm_pass'));

        $user = $this->user_model->get_user_for_id($this->session->get_session_attr('user_id'));

        if($post['username'] != $user['username'] || $post['fullname'] != $user['fullname'] || $post['email'] != $user['email'] || $post['password'] != '')
        {

            $errors = false;

            $error_msg = '<span class="failure">';

            // check for empty strings

            foreach($post as $item)
            {
                if($item == '')
                {
                    $errors = true;
                    $error_msg .= 'Fields cannot be left blank<br>';
                }
            }

            if($this->user_model->get_user_for_username($post['username']) != null && $post['username'] != $user['username'])
            {
                $error_msg .= 'Username already exists<br>';
                $errors = true;
            }

            if($this->user_model->get_user_for_email($post['email']) != null && $post['email'] != $user['email'])
            {
                $error_msg .= 'Email already exists<br>';
                $errors = true;
            }

            if($post['password'] == '')
            {
                unset($post['password']);
                unset($post['confirm_pass']);
            }

            if(sha1($post['password']) == $user['password'])
            {
                unset($post['password']);
                unset($post['confirm_pass']);
            }

            if($post['password'] != $post['confirm_pass'])
            {
                $error_msg .= 'Passwords do not match<br>';

                $errors = true;
            }

            if($errors == false)
            {
                unset($post['confirm_pass']);
                $post['password'] = sha1($post['password']);
                
                foreach($post as $key => $val)
                {
                    $user[$key] = $val;
                }

                $res = $this->user_model->update_user($user);

                if($res === true)
                {
                    $this->session->update_session_data($this->user_model->get_user_for_id($this->session->get_session_attr('user_id')));

                    echo json_encode(array('status' => 'true', 'msg' => '<span class="success">Account Updated</span>'));
                }
                else
                {
                    echo json_encode(array('status' => 'false', 'msg' => '<span class="failure">'.$res.'</span>'));
                }
            }
            else
            {
                $error_msg .= '</span>';

                echo json_encode(array('status' => 'false', 'msg' => $error_msg));
            }
        }
        else
        {
            echo json_encode(array('status' => 'ok'));


        }

    }

    public function delete_bucket()
    {
        $bucket_id = $this->input->post('bucket_id');

        if($this->bucket_model->delete_bucket($bucket_id) == true)
        {
            echo json_encode(array('status' => 'true'));
        }
        else
        {
            echo json_encode(array('status' => 'false'));
        }
    }

    public function delete_bookmark()
    {
        $bookmark_id = $this->input->post('bookmark_id');

        $res = $this->user_model->delete_bookmark($bookmark_id, $this->session->get_session_attr('user_id'));



        if($res == true)
        {
            echo json_encode(array('status' => 'true'));
        }
        else
        {
            echo json_encode(array('status' => 'false'));
        }
    }

    public function new_bucket()
    {
        $post = $this->input->post_array(array('bucket_name', 'bucket_description', 'privacy', 'auto_fill', 'tag_list'));

        $errors = false;

        if($post['privacy'] == null)
        {
            $post['privacy'] = 0;
        }

        foreach($post as $key => $item)
        {
            if(!is_array($item) && $key != 'tag_list')
            {
                if($item === '')
                {
                    echo $key."\n";
                    echo $item;
                    $errors = true;
                }
            }
        }

        if($errors == false)
        {

            if($post['privacy'] == null)
            {
                unset($post['privacy']);

                $post['public'] = true;
            }
            else
            {
                unset($post['privacy']);

                $post['public'] = false;
            }

            if($post['auto_fill'] == null)
            {
                $post['auto_fill'] = false;
            }
            else
            {
                $post['auto_fill'] = true;
            }

            if($post['tag_list'] != null)
            {
                $post['tags'] = json_decode($post['tag_list'], true);

            }
            else
            {
                unset($post['tag_list']);
                $post['tag_list'] = array();
            }

            $post['user_id'] = $this->session->get_session_attr('user_id');
            unset($post['tag_list']);
            $res = $this->bucket_model->create_bucket($post);

            if($res != false)
            {
                $bucket_list = $this->bucket_helper->get_bucket_list();

                echo json_encode(array('status' => 'true', 'buckets' => $bucket_list));

            }
            else
            {
                echo json_encode(array('status' => 'false', 'msg' => 'Error creating bucket'));
            }
        }
        else
        {
            echo json_encode(array('status' => 'false', 'msg' => 'Please fill in all fields'));
        }


    }
}