<?php

$studentID = $_POST["sid"];
$gid = $_POST["groupIDedit"];

include("../lib/connect.php");

if ($studentID > 0) {
    $sql = "UPDATE students set groupID=? where studentID=?";
    $q = $db->prepare($sql);
    $q->execute(array($gid, $studentID));
    header("Location: student-detail.php?msg=Group updated");
} else {
    header("Location: student-detail.php?err=Cannot edit empty Group");
}


