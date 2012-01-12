<?php
/**
 * Created by JetBrains PhpStorm.
 * User: seanmcgary
 * Date: 11/9/11
 * Time: 7:32 PM
 * To change this template use File | Settings | File Templates.
 *
 * Requires - https://github.com/nicolasff/phpredis
 */
 
class lib_libraries_libRedis
{

    public $conn;

    public function __construct()
    {
        $this->conn = new Redis();

        $this->conn->connect('seanmcgary.com', 6379);
        $this->conn->auth('summertimerave');
        $this->conn->select(5);
    }
    
}