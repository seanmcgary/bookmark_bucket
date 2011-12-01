<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
 
class lib_helpers_baseHelper extends core_controller
{
    public $page;

    public function __construct()
    {
        parent::__construct();

        $this->load_model('lib_models_userModel', 'user_model');
        $this->load_model('lib_models_bookmarkModel', 'bookmark_model');
        $this->load_model('lib_models_metricsModel', 'metrics_model');
        $this->load_model('lib_models_tagModel', 'tag_model');
        $this->load_model('lib_models_bucketModel', 'bucket_model');
    }
}