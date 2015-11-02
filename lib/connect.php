<?php
$mysql_host = "localhost";
$mysql_database = "VirtualLearningEnvironment";
$mysql_user = "admin";
$mysql_password = "admin";


$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
try {
    $db = new PDO("mysql:host=localhost;dbname=VirtualLearningEnvironment;charset=utf8", $mysql_user, $mysql_password, $options);
}
catch(PDOException $ex){
    die("Failed to connect to the database: " . $ex->getMessage());
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


