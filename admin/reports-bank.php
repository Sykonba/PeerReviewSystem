<?php
include_once('../lib/header.php');
include("../lib/connect.php");
include_once('is-admin-login.php');
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
                        <li role="presentation"><a href="grouping.php">Group Info</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="student-detail.php">Student Info</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation" class="active"><a href="reports-bank.php">Report Bank</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="ranking.php">Rank</a></li>
                        <li class="divider-vertical"></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-secret"></i> <?php echo $_SESSION['admin']['firstName']?>
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
                                <i class="fa fa-file"></i> Report Bank</a>
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['admin']['firstName']?></h1>


<?php
$sql = "select r.reportID, r.reportName, r.dateOfSubmission, s.groupID, g.groupName
from reports r, students s, groups g
where r.studentID = s.studentID and g.groupID = s.groupID
order by r.dateOfSubmission DESC ";

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$row = $stmt->fetchAll();

if ($row) { ?>
    <hr/>
    <table class="table table-striped">
        <tr>
            <th>Report Name</th>
            <th>Submitted by (Group)</th>
            <th>Date of Submission</th>
            <th>Visibility</th>
            <th>Details</th>
        </tr>
        <?php
        foreach($row as $row) { ?>
            <tr>
                <td><?php echo $row['reportName']; ?></td>
                <td><?php echo $row['groupName']; ?></td>
                <td><?php echo $row['dateOfSubmission']; ?></td>
                <td><a href="report-visible-to.php?rid=<?php echo $row['reportID']; ?>">Assign visibility</a></td>
                <td><a href="reports-details.php?rid=<?php echo $row['reportID']; ?>">Details</a></td>
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