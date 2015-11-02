<?php
$reportID = $_POST["rid"];
$groupsToAssignTo = $_POST["groupsVisibility"];

include("../lib/connect.php");

if ($reportID > 0) {
    //check if already exist then remove the entry
    $sqlRemove = "DELETE FROM visibility where reportID=?";
    $qC = $db->prepare($sqlRemove);
    $res = $qC->execute(array($reportID));

    //re-entry the latest selection
    $sql = "INSERT INTO visibility (reportID, groupID) VALUES (?,?)";
    $q = $db->prepare($sql);
    foreach($groupsToAssignTo as $gid) {
        $q->execute(array($reportID, $gid));
    }

    header("Location: report-visible-to.php?rid=" . $reportID ."&msg=This report assigned to total " . count($groupsToAssignTo) . " group(s) now");
} else {
    header("Location: report-visible-to.php?rid=" . $reportID . "&err=Cannot assign to empty Group");
}