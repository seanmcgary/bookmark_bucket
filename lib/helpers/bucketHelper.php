<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 12/1/11
 * Time: 2:15 PM
 * To change this template use File | Settings | File Templates.
 */
class lib_helpers_bucketHelper extends lib_helpers_baseHelper
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_bucket_list()
    {
        $buckets = $this->bucket_model->get_all_user_buckets($_SESSION['loggedIn']['user_id']);;

        $bucket_list = $this->load->view('presenters/account/bucket_list', array('buckets' => $buckets, 'account' => true), true);

        return $bucket_list;
    }
}