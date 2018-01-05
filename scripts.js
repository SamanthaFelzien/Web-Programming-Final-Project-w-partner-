/*
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 *
 * All JavaScripts that don't include PHP hacks are in this file.
 */

function logout() {
    //console.log("logout called");
	var anObj = new XMLHttpRequest();
    anObj.open("GET", "controller.php?action=logout", true);
    anObj.send();
    location.reload();
    anObj.onreadystatechange = function () {
        location.reload();
     }
}

function notLoggedIn(){
    var str = '<div class = message>YOU NEED TO BE LOGGED IN IF YOU WANT TO FAVORITE A MEME!<br />';
    str += "Click on <a href=\"register.php\">Register</a> to create " +
         "an account or click <a href=\"login.php\">Login</a> to login.<br /><div>";
    document.getElementById("toChange").innerHTML = str;
}

function randomMeme() {
	
    var anObj = new XMLHttpRequest();
    anObj.open("GET", "controller.php?action=random", true);
    anObj.send();

    anObj.onreadystatechange = function () {
        if(anObj.readyState == 4 && anObj.status == 200){
            var array = JSON.parse(anObj.responseText);
            var url = array[0]['url'];
            var str = "";

            if(url.substr(0, 4) == 'http') //youtube video
                str = '<iframe width="720" height="540" src="' + url +
                      '?autoplay=1" frameborder="0" allowfullscreen></iframe>';
            else
                str = '<img src="images/' + url +
                      '" width="720" height="540" onclick="rickRoll()" />';

            str += '<p class = "message"><br />(Images/Videos are chosen randomly)</p>';

        	document.getElementById("toChange").innerHTML = str;
       	 	document.getElementById("favButton").innerHTML = '<button class = "favButton" onclick="rickRoll()">Don\'t Click Me!!</button>';
       	 	
        }
    }
}

function rickRoll() {

	var w = window.innerWidth * .8;
    var h = window.innerHeight * .8;
    document.getElementById("header").innerHTML = 'You\'ve been rick rolled';
    document.getElementById("favButton").innerHTML = '<button class = "favButton" onclick="randomMeme()">Make it stop!</button>';
	document.getElementById("toChange").innerHTML = '<iframe width="' + w + ' " height=" ' + h + '" src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1"></iframe>';
    
}
