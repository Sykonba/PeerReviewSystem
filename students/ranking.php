<?php
include_once('../lib/header.php');
include("../lib/connect.php");
include_once('is-student-login.php');
?>

 <div class="navbar navbar-fixed-top navbar-inverse navbar-default">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="../index.php">Virtual Learning Environment</a>
                <div class="nav-collapse">
                    <ul class="nav pull-right">
                        <li role="presentation"><a href="index.php">Dashboard</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="submission.php">Report Submission</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="reports-peer-assessment.php">Peer Assessment</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation" class="active"><a href="ranking.php">Ranking</a></li>
                        <li class="divider-vertical"></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['student']['firstName']." ". $_SESSION['student']['lastName']?>
                        <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="../logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                            </li>
                        </ul>
                        </li>
                      
                    </ul>
                </div>


            </div>
        </div>
    </div>

    <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-trophy"></i> Ranking
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['student']['firstName']?></h1>
    <hr/>

<?php

//get all report and groups
$sql = "select r.reportID, r.reportName, g.groupName
from reports r, students s, groups g
where r.studentID = s.studentID and g.groupID = s.groupID";

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$rowAll = $stmt->fetchAll();
$all = [];
foreach ($rowAll as $row) {
    $all[$row['reportID']] = array(
        'reportName' => $row['reportName'],
        'groupName' => $row['groupName']

    );
}

$sqlRank = "SELECT reportID, count(reportID) as gradeNo, sum(grade) as totalGrade, sum(grade)/count(reportID)*10 as percent FROM assessment group by reportID order by sum(grade)/count(reportID) desc";

try{
    $stmtR = $db->prepare($sqlRank);
    $resultR = $stmtR->execute();
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$rows = $stmtR->fetchAll();

if ($rows) {
    $counter=1;?>
    <table class="table table-striped">
        <tr>
            <th>Rank</th>
            <th>Report Name</th>
            <th>Group Name</th>
            <th>No. of comments</th>
            <th>Grade Total</th>
            <th>Percentage</th>
        </tr>
        <?php
        foreach($rows as $row) { ?>
            <tr>
                <td><?php echo $counter++; ?></td>
                <td><?php echo $all[$row['reportID']]['reportName']; ?></td>
                <td><?php echo $all[$row['reportID']]['groupName']; ?></td>
                <td><?php echo $row['gradeNo']; ?></td>
                <td><?php echo $row['totalGrade']; ?></td>
                <td><?php echo sprintf('%0.2f', $row['percent']); ?>%</td>
            </tr>
        <?php }
        ?>

    </table>


<?php } else { ?>
    <h5>No rank found yet</h5>
<?php } ?>

<?php include_once('../lib/footer.php'); ?>