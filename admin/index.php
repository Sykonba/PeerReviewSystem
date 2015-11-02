<?php include_once('../lib/header.php'); 
      include("../lib/connect.php");?>
 
 <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="../index.php">Virtual Learning Environment</a>
              </div>
            </div>
          </div>
<!-- <h1 align = "center">Welcome to Admin Section</h1> -->

<?php if (isset($_SESSION['admin'])) { ?>
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
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

    <?php

    $sql = "SELECT s.studentID, s.firstName, s.lastName, s.groupID, s.dateOfBirth, g.groupName FROM students s, groups g WHERE s.groupID = g.groupID AND s.groupID = 1";
    try{
        $stmt = $db->prepare($sql);
        $result = $stmt->execute();
    }
    catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
    $row = $stmt->fetchAll();
    ?>


    <h2 align="center">Welcome <?php echo $_SESSION['admin']['firstName']?>. Please select the above menu item...</h2>
    <div align="center">
        <img src="../img/admin.gif" alt="admin" align="center" />
    </div>
                <div class="row ">
                    
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h2 class="panel-title" style="margin-left:75px"> New Registration</h2>
                                <p style="margin-left:75px"> Click <a href="student-detail.php">here </a>to assign new students into a group
                                
                            </div>
                            <hr/>
                                <table class="table table-striped" align="center" style="margin-left:75px">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Date of Birth</th>
                                    </tr>
                                    <?php
                                    foreach($row as $row) { ?>
                                    <tr>
                                    <td><?php echo $row['firstName'] ." " . $row['lastName']; ?></td>
                                    <td>
                                        <?php echo $row['dateOfBirth']; ?>
                                    </td> 
                                    <?php } ?>
                                </table>
                        </div>
                    </div>
                </div>
                    <!-- row-->


<?php } else {
    include_once('login.php');
}
?>


<?php include_once('../lib/footer.php'); ?>