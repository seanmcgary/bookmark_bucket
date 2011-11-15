<?php
/**
 * @author Sean McGary <sean@seanmcgary.com>
 */
require_once("Mail.php");

class lib_libraries_mail
{

    private $username;
    private $password;

    private $from;

    private $host;
    private $port;
    
    public function __construct()
    {
        $this->username = 'no-reply@bookmarkbucket.com';
        $this->password = 'summertimerave';

        $this->from = $this->username;

        $this->host = "ssl://smtp.gmail.com";
        $this->port = "465";
    }

    public function send_mail($to, $subject, $message)
    {
        $smtp = $this->construct_smtp();

        $headers = $this->construct_header($this->from, $to, $subject);

        $mail = $smtp->send($to, $headers, $message);

    }

    public function construct_header($from, $to, $subject)
    {
        return array('From' => $from, 'To' => $to, 'Subject' => $subject);
    }

    public function construct_smtp()
    {
        return Mail::factory('smtp',
                              array ('host' => $this->host,
                                'port' => $this->port,
                                'auth' => true,
                                'username' => $this->username,
                                'password' => $this->password));
    }
}