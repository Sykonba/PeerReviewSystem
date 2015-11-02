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
                        <li role="presentation"  class="active"><a href="submission.php">Report Submission</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="reports-peer-assessment.php">Peer Assessment</a></li>
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
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['student']['firstName']?></h1>

<?php

// print_r($_SESSION);
if (isset($_SESSION['student'])) {
    //check if report already uploaded
    $sql = "select * from reports where studentID=?";

    try{
        $stmt = $db->prepare($sql);
        $result = $stmt->execute(array($_SESSION['student']['studentID']));
    }
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    $row = $stmt->fetchAll();

    ?>
   
    
    <?php if(isset($_GET['msg'])) {?>
        <div class="alert alert-success">
            <?php echo $_GET['msg']; ?>
        </div>
    <?php } ?>
<hr/>
    <br/>
    <h4>Please submit or upload your report</h4>
    <br/>
    <?php
    if ($row) {?>
            <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Warning!!!</span>
                Please note that, you've already uploaded your report and if you upload a new one it will replace the existing report.
                <p>Report: <?php echo $row[0]['reportName']; ?></p>
                <p>Submitted on: <?php echo $row[0]['dateOfSubmission']; ?></p>
                <p><a href="../uploadedfiles/<?php echo $row[0]['fileName']; ?>" target="_blank" download>Download</a> to view</p>
            </div>
        <?php } ?>
    <form class="form-signin" action="upload-report-file.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="rid" value="<?php echo isset($row[0]['reportID'])?$row[0]['reportID']:''; ?>" />
        <input type="hidden" name="sid" value="<?php echo $_SESSION['student']['studentID']; ?>" />
        <table>
            <tr>
                <td>Report Name:</td>
                <td><input type="text" name="reportName" placeholder="Enter your report name" /></td>
            </tr>
            <tr>
                <td>Select report:</td>
                <td><input type="file" name="report" size="55" /></td>
            </tr>
            <tr><td>&nbsp;</td><td></td></tr>
            <tr>
                <td>&nbsp;</td>
                <td>        <button class="btn btn-primary btn-sm" type="submit">Upload</button></td>
            </tr>
        </table>
        <br/><br/>
    </form>
    <hr/>

    <?php 
            $sql = "SELECT s.studentID FROM students s, groups g WHERE s.groupID=g.groupID and g.groupID=?";
            $grpID=$_SESSION['student']['groupID'];
            $qs = array($grpID);
            try {
                $stmt = $db->prepare($sql);
                    $result = $stmt->execute($qs);
            }
            catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
            $row = $stmt->fetchAll();
            foreach($row as $student) {
                $sid[]=$student['studentID'];
            }
           $sids = implode(',', array_fill(0, count($sid), '?'));
            $sql1 = 'select reportID from reports where studentID IN(' . $sids . ')';
            $stmtR = $db->prepare($sql1);
            foreach ($sid as $k => $id)
                $stmtR->bindValue(($k+1), $id);

            $stmtR->execute();
            $rid=$stmtR->fetchAll();
           
    ?> 
    

    <h3>Comments and grades</h3>
    <?php
    $comments = "select a.grade, a.comments, a.dateOfComment, s.firstName, s.lastName
                from assessment a, students s
                where a.studentID = s.studentID
                and a.reportID = ?";

    try{
        $stmComment = $db->prepare($comments);
        $resultComment = $stmComment->execute(array($rid[0]['reportID']));
    }
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    $rowComment = $stmComment->fetchAll();

    if ($rowComment) {
        $tbl = '<table>';
        $cmtRows = '';
        echo '<h4>Total comments: ' . count($rowComment) . '</h4>';
        foreach ($rowComment as $comment) {
            $cmtRows .= '<tr><td>[' . $comment['dateOfComment'] . ']<br/>Grade: ' . $comment['grade'] . '<br/>' .
                $comment['comments'] . '<br/>By: <strong>' . $comment['firstName'] . ' ' . $comment['lastName'] .
                '</strong></td></tr>';
            $cmtRows .= '<tr><td>&nbsp;</td></tr>';
        }
        $tbl .= $cmtRows . '</table>';

        echo $tbl;

    } else { ?>
        <h5>This report does not have any comments yet</h5>
    <?php }  ?>

<?php } else {
    include_once('login.php');
}
?>



<?php include_once('../lib/footer.php'); ?>