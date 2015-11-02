<?php 
    require("../lib/connect.php");
    include_once('../lib/header.php');
    if(!empty($_POST)) 
    { 
        // Ensure that the user fills out fields 
        if(empty($_POST['name'])) 
            { header("Location: register.php?err=Please enter your name"); 
                die("Incomplete mandatory fields"); } 
        elseif(empty($_POST['surname']))
            { header("Location: register.php?err=Please enter your surname "); 
                die("Incomplete mandatory fields"); } 
        elseif(empty($_POST['password']))
            { header("Location: register.php?err=Password field left blank"); 
                die("Incomplete mandatory fields"); 
                 } 
        elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
            { header("Location: register.php?err=Email Incorrect");
                die("Invalid E-Mail Address"); } 
        else {
       
       //Check whether the email address exists
        $query = " 
            SELECT 
                1 
            FROM students
            WHERE 
                email = :email 
        "; 
        $query_params = array( 
            ':email' => $_POST['email'] 
        ); 
        try { 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage());} 
        $row = $stmt->fetch(); 
        if($row){ header("Location: register.php?err=Email already exists");
                    die("This email address is already registered"); } 
        
        
        $dateOfBirth = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
        
        // Add row to database 
        $query = " 
            INSERT INTO students ( 
                firstName, 
                lastName,
                email,
                password, 
                salt, 
                groupID,
                dateOfBirth
            ) VALUES ( 
                :name, 
                :surname, 
                :email,
                :password,
                :salt, 
                :groupID,
                :dateOfBirth 
            ) 
        "; 
         
        // Security measures
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
        $password = hash('sha256', $_POST['password'] . $salt); 
        for($round = 0; $round < 65536; $round++){ $password = hash('sha256', $password . $salt); } 
        $query_params = array( 
            ':name' => $_POST['name'],
            ':surname' => $_POST['surname'], 
            ':password' => $password, 
            ':salt' => $salt, 
            ':dateOfBirth' => $dateOfBirth,
            ':groupID' => 1,
            ':email' => $_POST['email'] 
        ); 
        try {  
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); } 
        header("Location: register.php?msg=Successfully Registered. Return Home Screen to Log in"); 
        // die("Redirecting to index.php"); 
                }
    } 
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
   <div align="center">
   
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
    <?php } ?>
    <div id="page-wrapper">

            <div class="container-fluid">    
    <form name="register" action="register.php" method="post" class="form-signin"> 
        <h2 class="form-signin-heading">Register New User</h2> <br />

        <label for="inputName" class="sr-only">Name:</label> 
        <input type="text" name="name" id="inputName" class="form-control" placeholder="Name" required autofocus/> 
        <label for="inputSurName" class="sr-only">Surname:</label> 
        <input type="text" name="surname" id="inputSurName" class="form-control" placeholder="Surname" required /> 
        <label for="inputEmail" class="sr-only">Email:</label> 
        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" required /> 
        <label for="inputPassword" class="sr-only">Password:</label> 
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required/> 
        <label for="inputDoB" class="sr-only">Date Of Birth:</label> 
        <input type="text" name="day" style= "width: 42px" id="inputDoB" class="form-control" placeholder="dd" > 
        <input type="text" name="month" style= "width: 42px" id="inputDoB" class="form-control" placeholder="MM" > 
        <input type="text" name="year" style= "width: 85px" id="inputDoB" class="form-control" placeholder="YYYY" > <br /><br />
        <button class="btn btn-primary btn-sm" type="submit">Register</button>

        <a href="index.php" title="Signin">Log in</a>
    </form>

<?php include_once('../lib/footer.php'); ?>