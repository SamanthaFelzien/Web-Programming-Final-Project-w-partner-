<?php
/*
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 */

function title($title){
    echo '<div class="buttonContainer">&nbsp;';
    echo '<a class="pageButton" href="index.php">Home</a>&nbsp;';
    echo '<a class="pageButton" href="register.php">Register</a>&nbsp;';
    echo '<a class="pageButton" href="login.php">Login</a>&nbsp;';
    echo '<a class="pageButton" href="showAllMemes.php">Show All Memes</a>&nbsp;';
    echo '<a class="pageButton" href="userDashboard.php">User Dashboard</a>&nbsp;';
    
    echo '</div>';
    
    if( isset($_SESSION['user_id']) ){
        echo '<button class="logoutButton" onclick="logout()" ' .
            '>Logout</button></form>';
    }
    //echo '<div class="container">';
   echo '<h1 id="header">' . $title . '</h1>';
}

?>
