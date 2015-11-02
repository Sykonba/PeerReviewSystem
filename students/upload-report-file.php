<?php

$err=$msg='';
$valid_file = true;
$uploadOk = 1;

include("../lib/connect.php");


$target_dir = "../uploadedfiles/";
$target_file = $target_dir . basename($_FILES["report"]["name"]);

// Check file size
if ($_FILES['report']["size"] > 1024000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    $err = 'Report upload error:  '.$_FILES['photo']['error'] . ' please report to Admin';
} else {
    if (move_uploaded_file($_FILES["report"]["tmp_name"], $target_file)) {
        $msg = "The file ". basename( $_FILES["report"]["name"]). " has been uploaded.";
    } else {
        $err = "Sorry, there was an error uploading your file.";
    }
}

if ($uploadOk) {
    $rn = $_POST['reportName'];
    $dos = date("Y-m-d", strtotime("now"));
    $rid = $_POST['rid'];
    $sid = $_POST['sid'];
    $fn = basename( $_FILES["report"]["name"]);

    if($rid) {
        //if report present then delete it before uploading the new one
        $sqlRemove = "UPDATE reports set reportName='$rn', dateOfSubmission='$dos', fileName='$fn' where reportID=?";
        $qC = $db->prepare($sqlRemove);
        $res = $qC->execute(array($rid));
    } else {
        //re-entry the latest selection
        $sql = "INSERT INTO reports (reportName, dateOfSubmission, fileName, studentID) VALUES (?,?,?,?)";
        $q = $db->prepare($sql);
        $q->execute(array($rn, $dos, $fn, $sid));
    }

    header("Location: submission.php?msg=" . $msg);
} else {
    header("Location: submission.php?err=" . $err);
}