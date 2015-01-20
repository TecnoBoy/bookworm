<?php
    session_start();
    include("config.php");
    include("connect.php");
?>

<html>
<head>
<title><?php $config->title ?></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div id="page-wrap">

<?php 
    include("menu.php") 
?>
