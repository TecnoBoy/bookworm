<?php

$mysqli = new mysqli($config->hostname, $config->username,
                     $config->password, $config->dbname);
if (mysqli_connect_errno())
{
    die("Error: " . mysqli_connect_error());
   // header("Location: fatal.php");
}

?>

