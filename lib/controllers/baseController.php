<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
 
class lib_controllers_baseController extends core_controller
{
    public $page;
    public $bookmark_helper;
    public $bucket_helper;
    public $session;

    public function __construct()
    {
        parent::__construct();

        $this->page = core_loadFactory::get_inst('lib_libraries_pageFramework', 'page');

        $this->session = core_loadFactory::get_inst('lib_libraries_session', 'session');

        $this->bookmark_helper = core_loadFactory::get_inst('lib_helpers_bookmarkHelper', 'bookmark_helper');
        $this->bucket_helper = core_loadFactory::get_inst('lib_helpers_bucketHelper', 'bucket_helper');

        $this->load_model('lib_models_userModel', 'user_model');
        $this->load_model('lib_models_bookmarkModel', 'bookmark_model');
        $this->load_model('lib_models_metricsModel', 'metrics_model');
        $this->load_model('lib_models_tagModel', 'tag_model');
        $this->load_model('lib_models_bucketModel', 'bucket_model');
    }

    public function clear_all()
    {
        $this->user_model->clear_all();
    }
}