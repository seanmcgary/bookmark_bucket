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

        $is_valid = $this->is_valid_url('http://' . $bookmark['url']);
        
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
                        $formatted_url = $this->load->view('presenters/main/bookmarks_list', array('bookmarks' => array($res)), true);
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

}