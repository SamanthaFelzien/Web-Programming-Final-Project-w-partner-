<!DOCTYPE html>
<html>
<head>
<!--
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 * -->
<title>Show all memes</title>
<meta charset="utf-8" />

<style>
body { 
display: grid; 
grid-template-columns: 20% 60% 20% ;
grid-template-rows: 15% 15% auto 15%;
background-image: url("images/doge.jpg");
background-size:repeat;
}

.message{
    grid-row-start: 4; 
    grid-column-start: 3; 
    grid-column-end: 6; 
    justify-self: center; 
    text-align: center;
}

#toChange{
    grid-column-start: 2;
}

</style>
<link rel="stylesheet" type="text/css" href="index.css" />
<script src="scripts.js"></script>
</head>
<body>

<?php
session_start ();
include 'top.php';
title("Look at all these memes!");
?>

    <div id="toChange"></div>


<script type='text/javascript'>

showAllMemes();

/*
 * This function is called during page loading in order to generate the
 * boxes containing the different memes.
 */
function showAllMemes() {
    var anObj = new XMLHttpRequest();
    anObj.open("GET", "controller.php?action=getAll", true);
    anObj.send();
	
    anObj.onreadystatechange = function () {
        if (anObj.readyState == 4 && anObj.status == 200) {
            var str = "";
            var array = JSON.parse(anObj.responseText);

            for (var i = 0; i < array.length; i++) {
                var meme_id = array[i]['meme_id'];
                //console.log(meme_id);
                str += '<div class="favMemes">';
                str += '<p class = "memeName">' + array[i]['name'] + '</p>';
                str += '<p class = "description">' + array[i]['description'] + '</p>';
                str += '<div class="img">';

                if(array[i]['url'].substr(0, 4) == 'http'){
                   str += '<iframe width="420" height="315" src="' +
                          array[i]['url'] + '"></iframe>';
                }
                else {
                    str += '<img src="images/' + array[i]['url'] +
                           '" width="420" height="315" />';
                }

                str += '</div>' +
                       '<button class="favButton" onclick="favorite(' + meme_id + ')">' +
                       'Favorite</button><br />' +
                       '<span class = "message" id="infoBox' + meme_id + '"></span></div><br />';
            } /* end for loop */
        } /* end if(readystate...) */

        document.getElementById("toChange").innerHTML = str;
    }
};


/*
 * This function is accessed whenever a user clicks on the Favorite button.
 * PARAMS: meme_id -- automatically generated by showAllMemes(), it is the
 * 					  id of the specigic meme liked by the user
 */
function favorite(meme_id){
    <?php
        /* create a variable sessionIsSet that represents whether the user_id
         * is set in the SESSION. This will determine whether the user should be
         * prompted to register/login, or if we need access the database.
         */
        if( isset($_SESSION['user_id']) )
            echo "var sessionIsSet = true;";
        else
            echo "var sessionIsSet = false;";
    ?>

    if (sessionIsSet){
       var anObj = new XMLHttpRequest();
       anObj.open("GET", "controller.php?action=addFavorite&meme_id=" + meme_id, true);
       anObj.send();
    
        anObj.onreadystatechange = function (){
            if(anObj.readyState == 4 && anObj.status == 200){
                var response = anObj.responseText;
                	document.getElementById("infoBox" + meme_id).innerHTML = response;
            }
        }
    }
	else {
        //alert('Gotta be signed in');
        var str = "YOU NEED TO BE LOGGED " +
                  "IN IF YOU WANT TO FAVORITE A MEME!<br />" +
                  "Click on <a href=\"register.php\">Register</a> to create an " +
                  "account or click <a href=\"login.php\">Login</a> to login.<br />";
        document.getElementById("infoBox" + meme_id).innerHTML = str;
   }
};
</script>

<?php
include 'footer.php';
?>
</body>
</html>
