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
                            <li>
                            <a href="reports-peer-assessment.php">
                                <i class="fa fa-check"></i> Peer Assessment</a>
                            </li>
                            <i class="fa fa-arrow-circle-right"></i>
                            <li class="active">
                                 Grades
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['student']['firstName']?></h1>

<?php
$rid = $_GET['rid'];
if (empty($rid)) {
    header("Location: reports-peer-assessment.php");
}

$sql = "select r.reportID, r.fileName, r.reportName, s.studentID, r.dateOfSubmission, s.groupID, g.groupName
from reports r, students s, groups g
where r.studentID = s.studentID and g.groupID = s.groupID and r.reportID = ?
order by r.dateOfSubmission DESC ";

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute(array($rid));
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$row = $stmt->fetchAll();

if ($row) {
    $row = $row[0]; ?>


    <hr/>
    <br/>

    <?php if(isset($_GET['msg'])) {?>
        <div class="alert alert-success">
            <?php echo $_GET['msg']; ?>
        </div>
    <?php } ?>

   
    <a href="reports-peer-assessment.php">
        <button class="btn btn-primary btn-sm" type="submit"><< Back</button>
    </a>
    <h3>Report Information</h3>
    <table class="table table-striped">
        <tr>
            <td>Report Name</td>
            <td>
                <?php echo $row['reportName']; ?>
                &nbsp;&nbsp;&nbsp;
                <a target="_blank" href="../uploadedfiles/<?php echo $row['fileName'];?>">Download to read</a>
            </td>
        </tr>
        <tr>
            <td>Submitted on</td>
            <td><?php echo $row['dateOfSubmission']; ?></td>
        </tr>
        <tr>
            <td>Submitted Group</td>
            <td><?php echo $row['groupName']; ?></td>
        </tr>
        <tr>
            <td>Group members</td>
            <td>
                <?php
                $std = "select studentID, firstName, lastName, email, dateOfBirth from students where groupID =?";
                $stmpStud = $db->prepare($std);
                $resultStudents = $stmpStud->execute(array($row['groupID']));
                $rowStudents = $stmpStud->fetchAll();

                if ($rowStudents) {
                    $tbl = '<table>';
                    $reporter = '';
                    $rsid = $row['studentID'];

                    foreach ($rowStudents as $stud) {
                        if ($rsid == $stud['studentID']) {
                            $reporter = '*';
                        } else {
                            $reporter = '';
                        }

                        $tbl .= '<tr><td>' . $stud['firstName'] . ' ' . $stud['lastName'] . '</td><td>' . $stud['email'] . '</td><td>' . $stud['dateOfBirth'] . $reporter . '</td></tr>';
                    }
                    $tbl .= '</table>';

                    echo $tbl;
                }
                ?></td>
        </tr>


    </table>

    <hr/>
    <br/>
    <h3>Your Assessment please</h3>
    <form name = "submitComment" method = "post" action = "submit-comment.php" class="form-signin">
        <input type="hidden" name="rid" value="<?php echo $rid; ?>" />
        <input type="hidden" name="sid" value="<?php echo $_SESSION['student']['studentID']; ?>" />
    <table>
        <tr>
            <td>Grade:</td>
            <td>
                <input type="radio" name="grade" value="1"> 1 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="2"> 2 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="3"> 3 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="4"> 4 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="5"> 5 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="6"> 6 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="7"> 7 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="8"> 8 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="9"> 9 &nbsp;&nbsp;&nbsp;
                <input type="radio" name="grade" value="10"> 10 &nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr><td>&nbsp;</td><td></td></tr>
        <tr>
            <td>Comment:</td>
            <td>
                <input type="text" name="comment" style="width:518px; height:120px; " placeholder="Your comment please" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td><button class="btn btn-primary btn-sm" type="submit">Submit Comment</button></td>
        </tr>
    </table>

    </form>
    <hr/>
    <br/>
    <h3>Comments and grades</h3>
    <?php
    $comments = "select a.grade, a.comments, a.dateOfComment, s.firstName, s.lastName
                from assessment a, students s
                where a.studentID = s.studentID
                and a.reportID = ?";

    try{
        $stmComment = $db->prepare($comments);
        $resultComment = $stmComment->execute(array($row['reportID']));
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

<?php } else { ?>
    <h5>No reports submitted yet</h5>
<?php } ?>


<?php include_once('../lib/footer.php'); ?>