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
                            <a href="reports-bank.php"
                            <li>
                                <i class="fa fa-file"></i> Report Bank</a>
                            </li>
                            <i class="fa fa-arrow-circle-right"></i> 
                            <li class="active">
                                Assign Reports 
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['admin']['firstName']?></h1>
<?php
$rid = $_GET['rid'];
if (empty($rid)) {
    header("Location: reports-bank.php");
}

$sql = "select r.reportID, r.fileName, r.reportName, s.studentID, s.groupID, g.groupName
from reports r, students s, groups g
where r.studentID = s.studentID and g.groupID = s.groupID and r.reportID = ?";

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute(array($rid));
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$row = $stmt->fetchAll();
?>
    <hr/>
<?php
    if(isset($_GET['err'])) {?>
    <div class="alert alert-danger" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>
        <?php echo $_GET['err']; ?>
    </div>
<?php }
if(isset($_GET['msg'])) {?>
    <div class="alert alert-success">
        <?php echo $_GET['msg']; ?>
    </div>
<?php }

if ($row) {
    $row = $row[0]; ?>

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
            <td>Submitted Group</td>
            <td><?php echo $row['groupName']; ?></td>
        </tr>
    </table>

    <h4>Visibility status</h4>
    <?php
    //check if this report has already assigned to groups
    $sqlVis = "select groupID from visibility where reportID = ?";

    try{
        $vis = $db->prepare($sqlVis);
        $resultVis = $vis->execute(array($rid));
    }
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    $rowVis = $vis->fetchAll();
    $existingGrps=array();
    foreach($rowVis as $grp) {
        $existingGrps[]=$grp['groupID'];
    }

    $sqlGrp = "SELECT * FROM groups where groupID !=0 order by groupName";

    try{
        $stmt = $db->prepare($sqlGrp);
        $result = $stmt->execute();
    }
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    $rowsGrp = $stmt->fetchAll();

    if ($rowsGrp) {
        $grpTable = '<table class="table table-striped">';
        $grpRow = '';
        foreach ($rowsGrp as $rowG) {
            $chk = (in_array($rowG['groupID'], $existingGrps)?'CHECKED="CHECKED"':'');
            $grpRow .= '<tr><td>' .
                '<input type="checkbox" ' . $chk . ' name="groupsVisibility[]" value="' . $rowG['groupID'] . '" />&nbsp;&nbsp;&nbsp;' .
                $rowG['groupName'] .
                '</td></tr>';
        }
        $grpTable .= $grpRow . '</table>';
        ?>
        <form name = "setVisibility" method = "post" action = "assign-visibility.php">
            <input type="hidden" name="rid" value="<?php echo $row['reportID']; ?>" />
            <?php echo $grpTable; ?>
        <br/>
        <button class="btn btn-primary btn-sm" type="submit">Assign visibility</button>
        </form>

    <?php } ?>

<?php } else { ?>
    <h5>No reports available</h5>
<?php } ?>

<?php include_once('../lib/footer.php'); ?>