<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_controllers_bookmark extends lib_controllers_baseController
{
    
    public function __construct()
    {
        parent::__construct();

    }

    public function add_bookmark()
    {
        if(!isset($_SESSION['loggedIn']))
        {
            echo json_encode(array('status' => 'false', 'msg' => 'Must be logged in'));
            exit();
        }

        $bookmark = $this->input->post_array(array('url', 'privacy', 'title', 'tag'));

        if($bookmark['privacy'] != 'private')
        {
            $bookmark['privacy'] = 'public';
        }


        $bookmark['tags'] = json_decode($bookmark['tag'], true);
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
        
        if($is_valid)
        {

            $bookmark['title'] = utf8_encode($bookmark['title']);


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
                        $user = $this->user_model->get_user_for_id($_SESSION['loggedIn']['user_id']);
                        $formatted_url = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => array($res), 'user_bookmarks' => $user['bookmarks']), true);

                        echo json_encode(array('status' => 'true', 'bookmark' => $formatted_url));
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

        $this->bookmark_model->increment_bookmarked_count($bookmark_id);

        $user = $this->user_model->get_user_for_id($_SESSION['loggedIn']['user_id']);

        $user['bookmarks'][] = $bookmark_id;

        $res = $this->user_model->update_user($user);

        if($res != false)
        {
            $bookmarks = $this->user_model->get_user_bookmarks($_SESSION['loggedIn']['user_id']);
            $user_bookmarks = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => array_reverse($bookmarks), 'user_bookmarks' => $user['bookmarks']), true);
            //printr($user_bookmarks);
            echo json_encode(array('status' => 'true', 'user_bookmarks' => $user_bookmarks));
        }
        else
        {
            echo json_encode(array('status' => 'false'));
        }
    }

}