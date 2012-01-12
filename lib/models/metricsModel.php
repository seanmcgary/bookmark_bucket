<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_models_metricsModel extends lib_models_baseModel
{
    public $geo;
    public function __construct()
    {
        parent::__construct();
        $this->geo = lib_libraries_simpleGeoFactory::get_inst();
    }

    /**
     * Keep track of how many people have clicked through links.
     *
     * @param  $id
     * @return void
     */
    public function log_bookmark_click($id)
    {
        $data = array();
        $data['bookmark_id'] = $id;
        if($this->session->session_exists())
        {
            $data['user_id'] = $this->session->get_session_attr('user_id');
        }
        else
        {
            $data['user_id'] = null;
        }

        $geo = $this->geo->getContextFromIPAddress($_SERVER['REMOTE_ADDR']);

        $data['geo'] = $geo->address;

        $data['date'] = time();

        $data['ua'] = $_SERVER['HTTP_USER_AGENT'];

        try
        {
            $this->click_collection->insert($data);
        }
        catch(MongoCursorException $e)
        {
            //
        }
    }

    /**
     * Log when a person bookmarks a link. This is to measure location (IP) and by date
     *
     * @param  $data
     * @return void
     */
    public function log_bookmark_insert($data)
    {
        try
        {
            $this->stats_collection->insert($data, true);
        }
        catch(MongoCursorException $e)
        {
            return false;
        }
    }
}