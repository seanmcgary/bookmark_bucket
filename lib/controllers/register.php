<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
 
class lib_controllers_register extends lib_controllers_baseController
{
    public $page;

    public function __construct()
    {
        parent::__construct();

        $this->mail = core_loadFactory::get_inst('lib_libraries_mail', 'mail');
    }

    public function index()
    {
        $this->page->load_javascript(site_url('js/register.js'));
        $this->page->render('registerIndex_view', array());
    }

    public function submit_register($ajax = 'false')
    {
        $post = $this->input->post_array(array('username', 'email', 'password', 'fullname', 'confirm_pass'));

        $errors = false;
        $error_msg = '<span class="failure">';

        foreach($post as $p)
        {
            if($p == '')
            {
                $errors = true;
                $error_msg .= 'Please fill in all fields<br>';
                break;
            }
        }

        if($post['password'] != $post['confirm_pass'])
        {
            $error_msg .= 'Passwords do not match<br>';

            $errors = true;
        }

        if($this->user_model->get_user_for_username($post['username']) != null)
        {
            $error_msg .= 'Username already exists<br>';
            $errors = true;
        }

        if($this->user_model->get_user_for_email($post['email']) != null)
        {
            $error_msg .= 'Email already exists<br>';
            $errors = true;
        }

        if($errors)
        {
            $error_msg .= '</span>';
            if($ajax == 'true')
            {
                echo json_encode(array('status' => 'false', 'msg' => $error_msg));
            }
            else
            {
                unset($post['password']);
                unset($post['confirm_pass']);
                
                $_SESSION['register'] = array('form_data' => $post, 'msg' => $error_msg);
                redirect(site_url('register'));
            }
        }
        else
        {
            // create user
            unset($post['confirm_pass']);
            $res = $this->user_model->insert_new_user($post);

            if($res === true)
            {
                $user = $this->user_model->login_user($post['username'], $post['password']);
                $_SESSION['loggedIn'] = $user;

                $registration_message = 'Thank you for joining BookmarkBucket!';

                $this->mail->send_mail($user['email'], 'Regsitration Successful', $registration_message);

                if($ajax == 'true')
                {
                    // do later

                    echo json_encode(array('status' => 'true', 'redirect' => site_url('')));
                }
                else
                {
                    redirect(site_url(''));
                }
            }
            else
            {
                $error_msg = '<span class="failure">'.$res.'</span>';
                if($ajax == 'true')
                {
                    // do later
                    echo json_encode(array('status' => 'false', 'msg' => $error_msg));
                }
                else
                {
                    unset($post['password']);
                    unset($post['confirm_pass']);

                    $_SESSION['register'] = array('form_data' => $post, 'msg' => $error_msg);
                    redirect(site_url('register'));
                }
            }
        }
    }


}