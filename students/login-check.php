<?php

$email = $_POST["email"];
$password = $_POST["password"];

include("../lib/connect.php");

$sql = "SELECT * FROM students WHERE email = :email";
$query_params = array(':email' => $email);

try{
    $stmt = $db->prepare($sql);
    $result = $stmt->execute($query_params);
}
catch(PDOException $ex){ die("Failed to run query: " . $ex->getMessage()); }
$login_ok = false;
$row = $stmt->fetch();

if($row){
    $check_password = hash('sha256', $password . $row['salt']);

    if($check_password === $row['password']){
        $login_ok = true;
    }
}

if($login_ok){
    unset($row['salt']);
    unset($row['password']);
    session_start();
    $_SESSION['student'] = $row;
    header("Location: index.php");
}
else{
    header("Location: index.php?err=Login failed...");
    print("Login Failed.");
}
