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


        $bookmark_data = $this->bookmark_helper->get_bookmarks();

        $data = $bookmark_data['data'];
        $page_data = $bookmark_data['page_data'];
        
        $bookmark_container = $this->load->view('presenters/main/bookmark_container', $data, true);

        if(isset($_SESSION['loggedIn']))
        {
            $user_buckets = $this->bucket_model->get_all_user_buckets($_SESSION['loggedIn']['user_id']);

            $bucket_dropdown_select = $this->load->view('presenters/bucket_dropdown_select', array('buckets' => $user_buckets), true);

            $new_bookmark_form = $this->load->view('presenters/new_bookmark_form', array('bucket_dropdown_select' => $bucket_dropdown_select), true);
            $page_data['page'] = $this->load->view('presenters/main/main_logged_in', array('new_bookmark_form' => $new_bookmark_form, 'bookmark_container' => $bookmark_container), true);
        }
        else
        {
            $page_data['page'] = $this->load->view('presenters/main/main_default', array('bookmark_container' => $bookmark_container), true);
        }

        $this->page->render('mainIndex_view', $page_data, 'template/leftCol_bookmarks_view');
    }


}