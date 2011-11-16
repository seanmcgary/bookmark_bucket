<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
class lib_models_inviteModel extends lib_models_baseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function generate_invites($num_invites = 5)
    {
        $invites = array();

        while(count($invites) < 6)
        {
            $invites[] = $this->generate_invite_code();
        }
    }

    private function key_exists($key)
    {
        $results = $this->invite_collection->find(array('invite_code' => $key));

        return $this->get_one($results);
    }
    
    private function generate_invite_code()
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $id = '';
        for($i = 0; $i < 16; $i++)
        {
            $id .= $chars[rand(0, 61)];
        }

        $result = $this->key_exists($id);

        if($result != null)
        {
            // if it already exists, run it again
            return $this->generate_invite_code();
        }
        else
        {
            return $id;
        }
    }


}
