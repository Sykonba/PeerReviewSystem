<?php

$groupName = $_POST["groupName"];
$gid = $_POST["groupID"];

include("../lib/connect.php");

if (!empty($gid)) {
    $sql = "UPDATE groups set groupName=? where groupID=?";
    $q = $db->prepare($sql);
    $q->execute(array($groupName,$gid));
    $msg= "Group edited successfully";
} else {

    $sql = "INSERT INTO groups (groupName) VALUES (:gn)";
    $q = $db->prepare($sql);
    $q->execute(array(':gn'=>$groupName));
    $msg = "New Group saved successfully";
}

header("Location: grouping.php?msg=" . $msg);

