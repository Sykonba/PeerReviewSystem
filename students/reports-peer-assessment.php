<?php
include_once('../lib/header.php');
include("../lib/connect.php");
include_once('is-student-login.php');
?>
<div class="navbar navbar-fixed-top navbar-inverse">
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
                        <li role="presentation" class="active"><a href="reports-peer-assessment.php">Peer Assessment</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="ranking.php">Ranking</a></li>
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
                                <i class="fa fa-check"></i> Peer Assessment
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['student']['firstName']?></h1>
<?php

$sql = "select r.reportID, r.reportName, r.dateOfSubmission, s.groupID, g.groupName
from reports r, students s, groups g
where r.studentID = s.studentID and g.groupID = s.groupID
and r.reportID in (select reportID from visibility where groupID=?)
order by r.dateOfSubmission DESC ";

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute(array($_SESSION['student']['groupID']));
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$row = $stmt->fetchAll();

if ($row) { ?>
    <hr/>
    <table class="table table-striped">
        <tr>
            <th>Report Name</th>
            <th>Submitted by Group</th>
            <th>Date of Submission</th>
            <th>Assessment</th>
        </tr>
        <?php
        foreach($row as $row) { ?>
            <tr>
                <td><?php echo $row['reportName']; ?></td>
                <td><?php echo $row['groupName']; ?></td>
                <td><?php echo $row['dateOfSubmission']; ?></td>
                <td><a href="reports-details.php?rid=<?php echo $row['reportID']; ?>">Add or View - comments</a></td>
            </tr>
        <?php }
        ?>

    </table>


<?php } else { ?>
    <h5>No reports submitted yet</h5>
<?php } ?>


    <hr/>
    <br/>

<?php if(isset($_GET['msg'])) {?>
    <div class="alert alert-success">
        <?php echo $_GET['msg']; ?>
    </div>
<?php } ?>

<?php include_once('../lib/footer.php'); ?>