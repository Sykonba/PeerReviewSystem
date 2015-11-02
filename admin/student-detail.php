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
                        <li role="presentation" class="active"><a href="student-detail.php">Student Info</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="reports-bank.php">Report Bank</a></li>
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
                                <i class="fa fa-users"></i> Students</a>
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['admin']['firstName']?></h1>
    <hr/>


<?php
//get all groups
$sqlGrp = "SELECT * FROM groups order by groupName";
$stmtGrp = $db->prepare($sqlGrp);
$resultGrp = $stmtGrp->execute();
$rowGrp = $stmtGrp->fetchAll();

$sGrpBox = '<select name="groupIDedit">';
$opt = '<option value="0">Select...</option>';

foreach($rowGrp as $grps) {
    $opt .= '<option value="' . $grps['groupID'] . '"> ' . $grps['groupName'] . '</option>';
}
$sGrpBox .= $opt . '</select>';

$grpId = isset($_GET['gid'])?$_GET['gid']:'';

if (!$grpId) {
    $sql = "SELECT s.studentID, s.firstName, s.lastName, s.groupID, s.dateOfBirth, g.groupName FROM students s, groups g WHERE s.groupID = g.groupID";
    $qs = '';
} else {
    $sql = "SELECT s.studentID, s.firstName, s.lastName, s.groupID, s.dateOfBirth, g.groupName FROM students s, groups g WHERE s.groupID = g.groupID AND s.groupID = ?";
    $qs = array($grpId);
}

try {
    $stmt = $db->prepare($sql);
    if ($grpId > 0) {
        $result = $stmt->execute($qs);
    } else {
        $result = $stmt->execute();
    }
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$row = $stmt->fetchAll();
?>
<form style="margin:0;" name = "fltGrp" method = "get" action = "filter-student.php">
        <?php echo $sGrpBox; ?>
        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-filter"></i> Filter</button>
    </form>
<?php
if ($row) { ?>
    <?php if(isset($_GET['err'])) {?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>
            <?php echo $_GET['err']; ?>
        </div>
    <?php } ?>

    <?php if(isset($_GET['msg'])) {?>
        <div class="alert alert-success">
            <?php echo $_GET['msg']; ?>
        </div>
    <?php } ?>

    <hr/>
    <table class="table table-striped">
        <tr>
            <th>Student Name</th>
            <th>Date of Birth</th>
            <th>Assigned Group</th>
            <th>Edit Group</th>
        </tr>
        <?php
        foreach($row as $row) { ?>
            <tr>
                <td><?php echo $row['firstName'] ." " . $row['lastName']; ?></td>
                <td>
                    <?php echo $row['dateOfBirth']; ?>
                </td>
                <td>
                    <?php echo $row['groupName']; ?>
                </td>
                <td>
                    <form style="margin:0;" name = "editGrp" method = "post" action = "edit-student-group.php">
                        <input type="hidden" name="sid" value="<?php echo $row['studentID']; ?>" />
                        <?php echo $sGrpBox; ?>
                        <button class="btn btn-primary btn-sm" type="submit"> <i class="fa fa-edit"></i> Edit</button>
                    </form>
                </td>
            </tr>
        <?php }
        ?>

    </table>


<?php } else { ?>
    <h5>No Student found</h5>
<?php } ?>

<?php include_once('../lib/footer.php'); ?>