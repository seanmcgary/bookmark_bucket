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

        $all = $this->bookmark_model->get_public_bookmarks();
        $data['all_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $all), true);

        $new = $this->bookmark_model->get_new_public_bookmarks();
        $data['new_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $new), true);

        $popular = $this->bookmark_model->get_popular_bookmarks();
        $data['popular_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $popular), true);


        if(isset($_SESSION['loggedIn']))
        {
            $bookmarks = $this->user_model->get_user_bookmarks($_SESSION['loggedIn']['user_id']);

            //printr($bookmarks);

            $data['your_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => array_reverse($bookmarks)), true);

            $bookmark_container = $this->load->view('presenters/main/bookmark_container', $data, true);

            $page_data['page'] = $this->load->view('presenters/main/main_logged_in', array('bookmark_container' => $bookmark_container), true);
        }
        else
        {

            $bookmark_container = $this->load->view('presenters/main/bookmark_container', $data, true);
            
            $page_data['page'] = $this->load->view('presenters/main/main_default', array('bookmark_container' => $bookmark_container), true);
        }

        $this->page->render('mainIndex_view', $page_data, 'template/leftCol_bookmarks_view');
    }

    


}