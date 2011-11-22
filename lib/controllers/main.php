<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
 
class lib_controllers_main extends lib_controllers_baseController
{
    public $page;

    public function __construct()
    {
        parent::__construct();

        $this->page->load_javascript(site_url('js/bookmarks.js'));
        $this->page->load_javascript(site_url('js/tags.js'));
    }

    public function index()
    {
        $data['page'] = '';


        $bookmark_data = $this->get_bookmarks();

        $data = $bookmark_data['data'];
        $page_data = $bookmark_data['page_data'];

        /*$page_data['user_bookmarks'] = array();

        if(isset($_SESSION['loggedIn']))
        {
            $bookmarks = $this->user_model->get_user_bookmarks($_SESSION['loggedIn']['user_id']);

            $user = $this->user_model->get_user_for_id($_SESSION['loggedIn']['user_id']);

            $page_data['user_bookmarks'] = $user['bookmarks'];

            $data['your_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => array_reverse($bookmarks), 'user_bookmarks' => $page_data['user_bookmarks']), true);
        }


        $all = $this->bookmark_model->get_public_bookmarks();
        $data['all_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $all, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        $new = $this->bookmark_model->get_new_public_bookmarks();
        $data['new_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $new, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        $popular = $this->bookmark_model->get_popular_bookmarks();
        $data['popular_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $popular, 'user_bookmarks' => $page_data['user_bookmarks']), true);
        */
        
        $bookmark_container = $this->load->view('presenters/main/bookmark_container', $data, true);

        $page = 'presenters/main/main_default';

        if(isset($_SESSION['loggedIn']))
        {
            $page = 'presenters/main/main_logged_in';
        }

        $page_data['page'] = $this->load->view($page, array('bookmark_container' => $bookmark_container), true);

        $this->page->render('mainIndex_view', $page_data, 'template/leftCol_bookmarks_view');
    }

    public function get_user_bookmarks()
    {
        $data = array();
        $page_data['user_bookmarks'] = array();

        if(isset($_SESSION['loggedIn']))
        {
            $bookmarks = $this->user_model->get_user_bookmarks($_SESSION['loggedIn']['user_id']);

            $user = $this->user_model->get_user_for_id($_SESSION['loggedIn']['user_id']);

            $page_data['user_bookmarks'] = $user['bookmarks'];

            $data['your_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => array_reverse($bookmarks), 'user_bookmarks' => $page_data['user_bookmarks']), true);
        }

        return array('page_data' => $page_data, 'data' => $data);
    }

    public function get_bookmarks()
    {
        $data = array();

        $user_data = $this->get_user_bookmarks();

        $page_data = $user_data['page_data'];
        $data = $user_data['data'];

        $all = $this->bookmark_model->get_public_bookmarks();
        $data['all_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $all, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        $new = $this->bookmark_model->get_new_public_bookmarks();
        $data['new_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $new, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        $popular = $this->bookmark_model->get_popular_bookmarks();
        $data['popular_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $popular, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        return array('data' => $data, 'page_data' => $page_data);
    }
}