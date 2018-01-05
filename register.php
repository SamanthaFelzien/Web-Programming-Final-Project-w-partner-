<!DOCTYPE html>
<html>
<head>
<!--
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 * -->
<title>Register</title>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="index.css" />
<script src="scripts.js"></script>

<style>

body { 
display: grid; 
grid-template-columns: 25% 50% 25% ;
grid-template-rows: 15% auto;
background-image: url("images/heman2.png");
background-size:cover;
}

</style>



</head>
<body>


<?php
session_start ();
include 'top.php';
echo title("Register");
?>

<div class = "container">

	<form action="controller.php" method="POST">
        <!-- this first input type is hidden so that the user doesn't    -->
        <!-- see it but allows us to $_POST an action to the controller  -->
        <input type="hidden" name="action" value="register" />
    	<label>
            Username: <input type="text" name="username">
        </label>
    	<label>
    		Password: <input type="password" name="pwdReg">
    	</label>
    	<input type="submit" name="Register" value="Register">
	</form>
    <?php
    if( isset(  $_SESSION['regError'])){
      echo   $_SESSION['regError'];
      session_unset(); /* using session_unset() lets us refresh the page
       * and make the registration error go away. Since nobody is logged in,
       * there isn't any data that requires saving in $_SESSION.
       */
    }
    ?>
</div>



</body>
</html>
