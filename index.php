<!DOCTYPE html>
<html>
<head>
<!--
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 * -->
<title>Memes</title>
<meta charset="utf-8" />
<style>

body{
    display: grid; 
    grid-template-columns: 12.5% 75% 12.5% ;
    grid-template-rows: 25% 15% 15% auto 15%;
    background: url("images/flame.gif"); 
    background-repeat: repeat; 
}
#favButton{
    align-self: start;
    margin-bottom: 10px; 
    grid-row-start: 1; 
    grid-column-start: 1;
    grid-column-end: span last-line; 
}


#toChange {
    justify-self: center; 
    grid-area: 4 / 1 / 5 / last-line
}

.buttonContainer{
align-self: end !important;
margin-top: 10px !important; 
}





</style>
<link rel="stylesheet" type="text/css" href="index.css" />
<script src="scripts.js"></script>
</head>
<body >


<?php
session_start ();
include 'top.php';
title("Fire Memes");
?>	
  
  <marquee id = "favButton" ><button class = "favButton" onclick="rickRoll()">Don't click this</button></marquee>
  <div id="toChange"></div>


<script>
randomMeme();
</script>

<?php
include 'footer.php';
?>
</body>
</html>
