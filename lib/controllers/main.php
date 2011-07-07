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
    }

    public function index()
    {
        $data['page'] = '';

        $page_data['user_bookmarks'] = array();

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

        $bookmark_container = $this->load->view('presenters/main/bookmark_container', $data, true);

        $page = 'presenters/main/main_default';

        if(isset($_SESSION['loggedIn']))
        {
            $page = 'presenters/main/main_logged_in';
        }

        $page_data['page'] = $this->load->view($page, array('bookmark_container' => $bookmark_container), true);

        $this->page->render('mainIndex_view', $page_data, 'template/leftCol_bookmarks_view');
    }
}