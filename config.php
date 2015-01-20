<?php

class config
{
    // mysql data
    public $hostname = "localhost";
    public $username = "root";
    public $password = "pass";
    // database name
    public $dbname = "bookworm";
    public $file = "bookworm.conf";
}

$config = new config;
if (file_exists($config->file))
{
    $ini_array = parse_ini_file($config->file, true);
    $config->hostname = $ini_array["hostname"];
    $config->username = $ini_array["username"];
    $config->password = $ini_array["password"];
    $config->dbname = $ini_array["dbname"];
}

?>
