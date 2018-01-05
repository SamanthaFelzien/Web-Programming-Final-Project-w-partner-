<!DOCTYPE html>
<html>
<head>
<!--
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 * -->
<title>User Dashboard</title>
<meta charset="utf-8" />
<style>

body { 
  display: grid; 
  grid-template-columns: 20% 60% 20% ;
  grid-template-rows: 15% 15% auto 15%; 
}

#toChange{
    grid-column-start: 2;
}

</style>
<link rel="stylesheet" type="text/css" href="index.css" />
<script src="scripts.js"></script>
</head>

<?php
session_start();
include 'top.php';

/* the following if-else sets a background image featuring
 * the Hampster Dance if the user is logged in.
 */
if( isset($_SESSION['user_id']) )
    echo '<body background="http://www.hamsterdance.org/hamsterdance/index-Dateien/hamster.gif">';
else
    echo '<body>';

title("User Dashboard");
?>

	<div id="toChange"> </div>
</div>

<script>
var array = [];
<?php
    /*
     * show favorites ONLY if the user is logged in. Otherwise, let the user
     * know that he or she must be logged in.
     */
    if( isset($_SESSION['user_id']) )
        echo 'showFavorites();';
    else
        echo 'notLoggedIn();';
?>
function showFavorites() {
    var anObj = new XMLHttpRequest();
    anObj.open("GET", "controller.php?action=showFavorites", true);
    anObj.send();
    anObj.onreadystatechange = function () {
    if (anObj.readyState == 4 && anObj.status == 200) {
        array = JSON.parse(anObj.responseText);

        if(array.length == 0){
            var str = '<div class = "message" ><br />You have no memes favorited! :( <br /></div>';
            document.getElementById("toChange").innerHTML = str;
            return;
        }

        /*this replaces the standard <h1> output with a custom user greeting */
        <?php
        /*
         * if the user is logged in, this overwrites the header sent to top.php
         * with a personalized greeting featuring the user's username.
         */
        if( isset($_SESSION['user_id']) ){
            echo 'document.getElementById("header").innerHTML = "Hello, ' .
                 $_SESSION['username'] . '!";';
        }
        ?>
        var str = "";
        for (var i = 0; i < array.length; i++) {
            var meme_id = array[i]['meme_id'];
            str += '<div class = "favMemes">';
            str += '<div class = "memeName">' + array[i]['name'] + '</div>';
            str += ' <div class = "description">' + array[i]['description'] + '</div>';
            str += '<div class="img">';

            if(array[i]['url'].substr(0, 4) == 'http'){ //youtube video
                str += '<iframe width="420" height="315" src="' + array[i]['url'] + '></iframe>';
            }
            else {
                str += '<img src="images/' + array[i]['url'] + '" width="420" height="315" />';
            }

            str += '</div><button class="favButton" onclick="removeFavorite(' +
                   meme_id +')">Remove Favorite</button> </div>';
        }

    }

        document.getElementById("toChange").innerHTML = str;
    }
}


function removeFavorite(meme_id){

	var anObj = new XMLHttpRequest();
    anObj.open("GET", "controller.php?action=removeFavorite&meme_id=" + meme_id, true);
    anObj.send();
    anObj.onreadystatechange = function () {
        if (anObj.readyState == 4 && anObj.status == 200) {
            showFavorites();
            //alert("un-favorited" + name + "!");
        }
    }

};

</script>

<?php
include 'footer.php';
?>
</body>
</html>
