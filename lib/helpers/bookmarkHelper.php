<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 11/30/11
 * Time: 4:59 PM
 * To change this template use File | Settings | File Templates.
 */

class lib_helpers_bookmarkHelper extends lib_helpers_baseHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_bookmark($bookmark)
    {
        if($bookmark['privacy'] != 'private')
        {
            $bookmark['privacy'] = 'public';
        }


        $bookmark['tags'] = json_decode($bookmark['tag'], true);
        $bookmark['buckets'] = json_decode($bookmark['buckets']);

        unset($bookmark['tag']);

        foreach($bookmark['tags'] as &$tag)
        {
            $tag = strtolower($tag);
        }

        if($bookmark['title'] == '')
        {
            $bookmark['title'] = $bookmark['url'];
        }

        if(!preg_match("/http(s)?:\/\//", $bookmark['url']))
        {
            $bookmark['url'] = 'http://' . $bookmark['url'];
        }

        $is_valid = $this->is_valid_url($bookmark['url']);


        // if its a valid URL, continue processing
        if($is_valid)
        {


            $bookmark['title'] = utf8_encode($bookmark['title']);

            // insert the bookmark for the user
            $res = $this->bookmark_model->insert_new_bookmark($bookmark, $_SESSION['loggedIn']['user_id']);


            if(!empty($bookmark['tags']))
            {
                foreach($bookmark['tags'] as $tag)
                {
                    $this->tag_model->create_new_tag($tag);
                }

            }

            if($res != false)
            {
                if($res === true)
                {
                    echo json_encode(array('status' => 'ok', 'msg' => '<span class="success">You already bookmarked that URL</span>'));
                }
                else
                {
                    if($bookmark['privacy'] == 'public')
                    {
                        $user = $this->user_model->get_user_for_id($_SESSION['loggedIn']['user_id'], true);
                        $user_bookmarks = $this->get_user_bookmarks();

                        //$main_controller = core_loadFactory::get_inst('lib_controllers_main', 'main');

                        $other_bookmarks = $this->get_bookmarks();

                        echo json_encode(array('status' => 'true', 'user_bookmarks' => $user_bookmarks['data']['your_bookmarks'], 'global_bookmarks' => $other_bookmarks['data']));
                    }
                    else
                    {
                        echo json_encode(array('status' => 'private'));
                    }

                }

            }
            else
            {
                echo json_encode(array('status' => 'false'));
            }
        }
        else
        {
            echo json_encode(array('status' => 'false', 'msg' => '<span class="failure">Invalid URL</span>'));
        }
    }

    public function is_valid_url($url)
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        $headers = curl_getinfo($ch);

        curl_close($ch);
        //echo $headers['http_code'];
        if($headers['http_code'] != 0)
        {

            return true;
        }
        else
        {
            return false;
        }
    }

    public static function bookmark_in_list($bookmark_id, $bookmarks)
    {
        foreach($bookmarks as $bookmark)
        {
            if($bookmark['bookmark_id'] == $bookmark_id)
            {
                return true;
            }
        }

        return false;
    }

    public function get_user_bookmarks()
    {
        $data = array();
        $page_data['user_bookmarks'] = array();

        if(isset($_SESSION['loggedIn']))
        {
            $bookmarks = $this->bookmark_model->get_public_bookmarks_for_user($_SESSION['loggedIn']['user_id']);

            //printr($bookmarks);

            $user = $this->user_model->get_user_for_id($_SESSION['loggedIn']['user_id'], true);

            $page_data['user_bookmarks'] = $bookmarks;

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

        $new = $this->bookmark_model->get_recent_public_bookmarks();
        $data['new_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $new, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        $popular = $this->bookmark_model->get_popular_bookmarks();
        $data['popular_bookmarks'] = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => $popular, 'user_bookmarks' => $page_data['user_bookmarks']), true);

        return array('data' => $data, 'page_data' => $page_data);
    }
}
