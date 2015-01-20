<?php

class config
{
    // mysql data
    public $hostname = "localhost";
    public $username = "root";
    public $password = "pass";
    // database name
    public $dbname = "mylib";
    public $file = "mylib.conf";
}

$config = new config;
if (file_exists($config->file))
{
    $ini_array = parse_ini_file($config->file, true);
    print_r($ini_array);
}

?>
