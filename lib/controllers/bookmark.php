<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_controllers_bookmark extends lib_controllers_baseController
{
    
    public function __construct()
    {
        parent::__construct();

        $this->bookmark_helper = core_loadFactory::get_inst('lib_helpers_bookmarkHelper', 'bookmark_helper');

    }

    public function add_bookmark()
    {
        if(!isset($_SESSION['loggedIn']))
        {
            echo json_encode(array('status' => 'false', 'msg' => 'Must be logged in'));
            exit();
        }

        $bookmark = $this->input->post_array(array('url', 'privacy', 'title', 'tag'));

        $this->bookmark_helper->create_bookmark($bookmark);

    }

    public function suggest_title()
    {
        $url = $this->input->post('url');

        $url = preg_replace("/http[s]:\/\//", "", $url);

        $url = 'http://'.$url;

        $page = @file_get_contents($url);

        preg_match_all('/title>(.+?)<\//', $page, $matches);

        $title = '';

        if(!empty($matches))
        {
            if(!empty($matches[1]))
            {
                $title = $matches[1][0];
            }
        }

        echo json_encode(array('status' => 'true', 'title' => $title));
    }

    public function log_click()
    {
        $bookmark_id = $this->input->post('bookmark_id');

        $this->bookmark_model->increment_clicked_count($bookmark_id);

        $this->metrics_model->log_bookmark_click($bookmark_id);
    }

    public function bookmark_existing()
    {
        $bookmark_id = $this->input->post('bookmark_id');

        $bookmark = $this->bookmark_model->get_bookmark_for_id($bookmark_id);

        if($bookmark != null)
        {
            $this->bookmark_model->increment_bookmarked_count($bookmark_id);

            $user_bookmark = array(
                'user_id' => $_SESSION['loggedIn']['user_id'],
                'user_tags' => $bookmark['tags'],
                'privacy' => 'public',
                'date_bookmarked' => time(),
                'bookmark_id' => $bookmark_id
            );

            $res = $this->bookmark_model->add_user_bookmark($user_bookmark);

            if($res == true)
            {

                $user_bookmarks = $this->bookmark_helper->get_user_bookmarks();

                echo json_encode(array('status' => 'true', 'user_bookmarks' => $user_bookmarks['data']['your_bookmarks']));
            }
            else
            {
                echo json_encode(array('status' => 'false'));
            }
        }
        else
        {
            echo json_encode(array('status' => 'false'));
        }
    }

}