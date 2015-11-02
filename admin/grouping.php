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
                        <li role="presentation" class="active"><a href="grouping.php">Group Info</a></li>
                        <li class="divider-vertical"></li>
                        <li role="presentation"><a href="student-detail.php">Student Info</a></li>
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
                                <i class="fa fa-users"></i> Group Info</a>
                            </li>
                        </ol>
                    </div>
                </div>

    <h1>Welcome <?php echo $_SESSION['admin']['firstName']?></h1>

<?php
$sql = "SELECT * FROM groups where groupID !=0 order by groupName";

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$row = $stmt->fetchAll();

if ($row) { ?>

        <hr/>

    <?php if(isset($_GET['msg'])) {?>
        <div class="alert alert-success">
            <?php echo $_GET['msg']; ?>
        </div>
    <?php } ?>
    <form name = "groupsInfo" method = "post" action = "add-edit-group.php" class="form-signin">
        <h2 class="form-signin-heading">Add/Edit Group</h2>
        <br/>
        <input type="hidden" name="groupID" value="<?php echo isset($_GET['gid'])?$_GET['gid']:''; ?>"/>
        <label for="inputEmail" class="sr-only">Group Name</label>
        <input type="text" value="<?php echo isset($_GET['gn'])?$_GET['gn']:'';  ?>" maxlength="30" name="groupName" id="groupName" class="form-control" placeholder="Group name" required autofocus>

        <br/>
        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i>  Save</button>
    </form>

    <table class="table table-striped">
        <tr>
            <th>Group Name</th>
            <th>Edit</th>
            <th>Group Members</th>
        </tr>
        <?php
        foreach($row as $row) { ?>
            <tr>
                <td><?php echo $row['groupName']; ?></td>
                <td>
                    <a href="grouping.php?gid=<?php echo $row['groupID'];?>&gn=<?php echo $row['groupName'];?>"><i class="fa fa-pencil"></i> edit</a>
                </td>
                <td>
                    <a href="student-detail.php?gid=<?php echo $row['groupID'];?>"><i class="fa fa-eye"></i> view</a>
                </td>
            </tr>
        <?php }
        ?>

    </table>


<?php } else { ?>
    <h5>No groups found</h5>
<?php } ?>



    

<?php include_once('../lib/footer.php'); ?>