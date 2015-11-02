<?php
$reportID = $_POST["rid"];
$grade = $_POST["grade"];
$comment = $_POST["comment"];
$sID = $_POST["sid"];
$doc = date("Y-m-d", strtotime("now"));

include("../lib/connect.php");

if ($reportID > 0) {

    //re-entry the latest selection
    $sql = "INSERT INTO assessment (grade, comments, dateOfComment, reportID, studentID) VALUES (?,?,?,?,?)";
    $q = $db->prepare($sql);
    $q->execute(array($grade, $comment, $doc, $reportID, $sID));

    header("Location: reports-details.php?rid=" . $reportID ."&msg=Assessment or comment added successfully");
} else {
    header("Location: reports-details.php?rid=" . $reportID . "&err=Cannot assign to empty Group");
}