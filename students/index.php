<?php 
include_once('../lib/header.php'); 
include("../lib/connect.php");
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
              </div>
            </div>
          </div>
    

<!-- <h1 align = "center">Welcome to Students section</h1> -->

<?php 

// print_r($_SESSION);
if (isset($_SESSION['student'])) { ?>
   <!--  <hr/> -->
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
      <div class="container-fluid" align="center">

 <h2>Welcome <?php echo $_SESSION['student']['firstName']?>. Please select the above menu item...</p>

        <?php 
            $sql = "SELECT s.firstName, s.lastName, s.groupID, g.groupID, g.groupName FROM students s, groups g WHERE s.groupID=g.groupID and g.groupID=?";
            $grpID=$_SESSION['student']['groupID'];
            $qs = array($grpID);
            try {
                $stmt = $db->prepare($sql);
                    $result = $stmt->execute($qs);
            }
            catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
            $row = $stmt->fetchAll();
            // echo '<pre>'; print_r($row);
        ?>

<img src="../img/student.gif" alt="student" />
        <div class="panel panel-primary">
            <div class="panel-heading">
                Group:  <?php echo $row[0]['groupName'];?><br> 
            </div>
                 <div class="panel-body">
                Group Members:<br>
                 <?php foreach($row as $student) {
                echo $student['firstName']. ' ' . $student['lastName']. '<br/>';

                }?> 

            </div>
        </div>
    
<?php } else {
    include_once('login.php');
}
?>



<?php include_once('../lib/footer.php'); ?>