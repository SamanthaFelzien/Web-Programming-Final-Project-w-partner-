<!DOCTYPE html>
<html>
<head>
<!--
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 * -->
<title>Login</title>
<meta charset="utf-8" />
<style>

body { 
display: grid; 
grid-template-columns: 25% 50% 25% ;
grid-template-rows: 15% auto;
background-image: url("images/grumpyCat.jpg");
background-size:cover;
}

</style>
<link rel="stylesheet" type="text/css" href="index.css" />
<script src="scripts.js"></script>
</head>
<body>


<?php
session_start ();
include 'top.php';
title("Login");
?>

<div class = "container">


	<form action="controller.php" method="POST">
        <!-- this first input type is hidden so that the user doesn't    -->
        <!-- see it but allows us to $_POST an action to the controller  -->
        <input type="hidden" name="action" value="login" />
    	<label>
            Username: <input type="text" name="username" />
        </label>
    	<label>
    		Password: <input type="password" name="pwdReg" />
    	</label>
    	<input type="submit" name="Login" value="Login" />
	</form>

    <?php
        if(isset($_SESSION['loginError'])){
            echo $_SESSION['loginError'] . '<br />';
            session_unset(); /* using session_unset() lets us refresh the page
             * and make the login error go away. Since nobody is logged in,
             * there isn't any data that requires saving in $_SESSION.
             */
        }
    ?>
</div>
</body>
</html>
