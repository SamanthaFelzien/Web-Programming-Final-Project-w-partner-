<?php
/*
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 */

session_start ();
include 'DatabaseAdaptor.php';
$theDBA = new DatabaseAdaptor();
//include $_SERVER['DOCUMENT_ROOT'] . '/finalProject/WebContent/DatabaseAdaptor.php';

/*******************************************************************************
 *** Possible $_SESSION elements:
 *** ----------------------------------
 *** user_id == the id of the logged in user
 *** username -- the username of the user
 *** regError -- the user tried registering existing username
 *** loginError -- the user used wrong login credentials
 *******************************************************************************/

/*
 * The if is for all actions involving GET.
 * The else if's are for login and logout using POST.
 * The ['action'] dictates which method (or "action") is called
 */
if( isset($_GET['action']) )
{
    switch($_GET['action']){
        case "addFavorite":
            addFavorite($theDBA);
            break;
        case "getAll":
            getAll($theDBA);
            break;
        case "logout":
            logout($theDBA);
            break;
        case "random":
            random($theDBA);
            break;
        case "showFavorites":
            getFavorites($theDBA);
            break;
        case "removeFavorite":
            removeFavorite($theDBA);
            break;
    }
}
else if( isset($_POST['action']) && $_POST['action'] == 'login' ){
    login($theDBA);
}

else if( isset($_POST['action']) && $_POST['action'] == 'register' ){
    register($theDBA);
}


/*******************************************************************************
 *** The functions below are the helper functions to retrieve data from the model
 *** They are listed alphabetically.
 *** addFavorite()
 *** getAll()
 *** getFavorites()
 *** login()
 *** logout()
 *** random()
 *** register()
 *** unFavorite()
 *******************************************************************************/

/*
 * If the meme_id is set in GET, then add it to the user's list of favorites
 * PARAMS: $meme_id -- the id of the meme we are adding
 * RETURN: echoes an empty string for the AJAX call to work
 */
function addFavorite($theDBA) {
    if( isset($_GET['meme_id']) && isset($_SESSION['user_id'])){
        $arr = $theDBA->getFavorites($_SESSION['user_id']);
        $notFound = true;
        for($i = 0; $i < count($arr); $i++){
            if($arr[$i]['meme_id'] == $_GET['meme_id']){
                $notFound = false;
                break;
            }
        }
        
        if($notFound){
            $theDBA->addFavorite($_SESSION['user_id'], $_GET['meme_id']);
            echo "You successfully added this meme to your list of favorites!<br />";
        }
        else 
            echo "You already have this meme set as a favorite!";
    }
}

/*
 * Returns an array of all memes in the database.
 */
function getAll($theDBA){
    echo json_encode( $theDBA->getAllMemes() );
}

/*
 * Returns an array of the current user's favorite memes.
 */
function getFavorites($theDBA){
    if( isset($_SESSION['user_id']) )
        echo json_encode($theDBA->getFavorites($_SESSION['user_id']));
}

/*
 * The function attempts to verify user credentials. If the credentials are
 * valid, the user returns to index. Otherwise, return to the login page
 * to try again.
 * ASSUME: you can $_POST usernameLog and pwd
 */
function login($theDBA){
    $user = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['pwdReg']);
    $arr = $theDBA->getUser($user);

    if(count($arr) > 0 && password_verify($password, $arr[0]['hash'])){
        $_SESSION['username'] = $user;
        $_SESSION['user_id'] = $arr[0]['user_id'];
        unset($_SESSION['loginError']);
        header('Location: index.php');
    }
    else{
        $_SESSION['loginError'] = 'Username or password is incorrect';
        header('Location: login.php');
    }
}

/*
 * logout() destroys the session and returns the user to index.php
 * ASSUME: this function was called because a logged-in user clicked on
 *         the "Logout" option
 */
function logout($theDBA){
    session_unset();
    session_destroy();
    header('Location: index.php');
}

/*
 * random() finds a random meme's id and returns the respective meme's
 * entry in the memes table as a JSON encoded array
 * ASSUME: meme id's always start with 1 and are ordered sequentially
 *         with no gaps
 * RETURN: a json encoded associative array of the meme and its data
 */
function random($theDBA){
    $allMemes = $theDBA->getAllMemes();
    $numOfEntries = count($allMemes);
    $id = rand(1, $numOfEntries);

    echo json_encode($theDBA->getMeme($id));
}

/*
 * When a user tries to register, the controller GET's a username
 * and password. It then attempts to add that data to the database
 * for future use. If the registration is successful, return to index.
 * Otherwise, return to the registration page to try again.
 * ASSUME: you can $_POST the username and pwdReg
 */
function register($theDBA){
    $username = htmlspecialchars($_POST['username']);
    $pwd = htmlspecialchars($_POST['pwdReg']);
    $hash = password_hash($pwd, PASSWORD_DEFAULT);

    $arr = $theDBA->register($username, $hash);
    if (count($arr) > 0){
        $_SESSION['username'] = $username;
        $_SESSION['user_id']  = $arr[0]['user_id'];
        unset($_SESSION['regError']);
        header('Location: index.php');
    } else {
        $_SESSION['regError'] = 'Username already exists';
        header('Location: register.php');
    }
}

/*
 * This function removes a favorited meme from the user dashboard.
 */
function removeFavorite($theDBA){
    if( isset($_GET['meme_id']) && isset($_SESSION['user_id']) )
        $theDBA->removeFavorite($_SESSION['user_id'], $_GET['meme_id']);
}

?>
