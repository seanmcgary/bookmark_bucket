<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 11/25/11
 * Time: 12:17 PM
 * To change this template use File | Settings | File Templates.
 *
 *
 * Status Codes
 *
 * - 1xx
 *
 *
 * - 2xx
 *
 *
 * - 3xx
 *
 *
 * - 4xx
 *      400: Authentication require
 *      401: Bad username and password
 */
class lib_controllers_api extends core_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load_model('lib_models_userModel', 'user_model');
        $this->load_model('lib_models_bookmarkModel', 'bookmark_model');
        $this->load_model('lib_models_metricsModel', 'metrics_model');
        $this->load_model('lib_models_tagModel', 'tag_model');
        $this->load_model('lib_models_bucketModel', 'bucket_model');
    }

    /**
     * Get a bookmark by its ID
     *
     * If its private, then authorization is required and it has to be
     * owned by the authenticating user
     *
     *
     * @return void
     */
    public function get_bookmark($bookmark_id)
    {

    }

    /**
     * Add a bookmark to a users list
     *
     * Fields:
     *  - auth_username
     *  - auth_password
     *  - url
     *  - privacy <private || public>
     *  - title
     *  - tag   <[tag1, tag1, tag3,...tagn]>
     *
     * AUTH REQUIRED
     *
     * @return void
     */
    public function add_bookmark()
    {
        $auth_username = $this->input->post('auth_username');
        $auth_password = $this->input->post('auth_password');
        if($auth_password == null || $auth_username == null)
        {
            $this->send_response(array('status' => 401, 'text' => 'Bad username and password'));
            return;
        }

        $user = $this->auth_user($auth_username, $auth_password);

        if($user != null)
        {

        }
        else
        {
            $this->send_response(array('status' => 400, 'text' => 'Authentication Required'));
        }
    }

    /**
     * Delete bookmark
     *
     * Fields:
     *  - username
     *  - password
     *  - bookmark_id
     *
     * AUTH REQUIRED
     *
     * @return void
     */
    public function delete_bookmark()
    {

    }

    public function get_user_data()
    {
        
    }

    /**
     * Fields:
     *  - username <string>
     *
     *  Only required if fetching your own with privat bookmarks
     *  - auth_username
     *  - auth_password
     *
     * @return void
     */
    public function get_user_bookmarks($user_id)
    {
        $user = $this->user_model->get_user_for_id($user_id);

        $auth_username = $this->input->post('auth_username');
        $auth_password = $this->input->post('auth_password');

        if($auth_password == null || $auth_username == null)
        {
            $bookmarks = $this->bookmark_model->get_public_bookmarks_for_user($user_id);

            printr($bookmarks);
            return;
        }
        else
        {

            $authed_user = $this->auth_user($auth_username, $auth_password);

            if($authed_user != null && $authed_user['username'] == $user['username'])
            {
                $bookmarks = $this->bookmark_model->get_all_bookmarks_for_user($user_id);

                printr($bookmarks);
                return;
            }
        }
    }

    /**
     * Fields:
     *  - user_id <int>
     *  - username <string>
     *
     *  Only required if fetching your own with privat bookmarks
     *  - auth_username
     *  - auth_password
     *
     * @return void
     */
    public function get_user_buckets()
    {

    }

    public function send_response($data)
    {
        //header("Content-Type: text/json");
        echo json_encode($data);
    }

    private function auth_user($username, $password)
    {
        $user = $this->user_model->api_auth_user($username, $password);

        return $user;
    }


}
