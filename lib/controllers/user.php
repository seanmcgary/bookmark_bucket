<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 11/22/11
 * Time: 2:09 PM
 * To change this template use File | Settings | File Templates.
 */
class lib_controllers_user extends lib_controllers_baseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function _remap()
    {
        $segments = $this->uri->segment_array();

        if(count($segments) == 2)
        {
            $this->index($segments[1]);
        }
        else if(count($segments) == 3 && $segments[2] == 'buckets')
        {

        }
    }

    public function index($username)
    {
        $user = $this->user_model->get_detailed_user_for_username($username);

        if($_SESSION['loggedIn']['user_id'] != $user['user_id'])
        {
            $this->user_model->increment_user_view($user['user_id']);
        }
        
        //printr($user);

        if($user == null)
        {
            // user doesnt exist
        }
        else
        {
            $data = array();
            $page_data = array();
            
            // show the users page
            $data['all_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $user['bookmarks'], 'user_bookmarks' => $user['bookmarks']), true);
            $data['buckets'] = array();

            foreach($user['buckets'] as $bucket)
            {
                $data['buckets'][] = array('bucket_id' => $bucket['bucket_id'], 'bucket' => $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $bucket['bookmarks'], 'user_bookmarks' => $user['bookmarks']), true));
            }

            //printr($user['buckets']);
            
            $data['user'] = $user;

            $page_data['user_profile'] = $this->load->view('presenters/user/user_profile', array('user' => $user), true);
            //echo $data['all_bookmarks'];
            $page_data['page'] = $this->load->view('presenters/user/user_bookmarks', $data, true);

            $this->page->load_javascript(site_url('js/user.js'));
            $this->page->render('userIndex_view', $page_data, 'template/leftCol_user', array('buckets' => $user['buckets']));
        }
    }
}