<?php
/*
 * Authors: Sam Felzien, Peter Mahon
 * Course:  CSC 337, Fall 2017
 * Final Project
 */

class DatabaseAdaptor {
    // The instance variable used in every one of the functions in class DatbaseAdaptor
    private $DB;

    // Make a connection to an existing database named 'first' that has table customer
    public function __construct() {
        $db = 'mysql:dbname=memes; charset=utf8; host=127.0.0.1';
        $user = 'root';
        $password = '';

        try {
            $this->DB = new PDO ( $db, $user, $password );
            $this->DB->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch (PDOException $e ) {
            echo ('Error establishing Connection');
            exit ();
        }
    }

    /* 
     * If th user_id is set in $_SESSION, then add the given
     * $meme_id to the user's list of favorites
     * ASSUME: a user_id is set
     * PARAMS: $meme_id -- the id of the meme we are adding
     * RETURN: none
     */
    public function addFavorite($user_id, $meme_id) {
        $cmd = 'INSERT INTO favorites (user_id, meme_id) ' .
               'VALUES (:user_id, :meme_id);';
        $stmt = $this->DB->prepare($cmd);
        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('meme_id', $meme_id);
        $stmt->execute();
    }

    /* 
     * Returns an array of all memes from the database
     */
    public function getAllMemes() {
        $stmt = $this->DB->prepare ("SELECT * FROM memes;");
        $stmt->execute ();
        return $stmt->fetchAll (PDO::FETCH_ASSOC);
    }

    /* 
     * Uses a join to colect all favorites by one particular user
     */
    public function getFavorites($user_id) {
        $cmd = 'SELECT users.username, memes.name, memes.description, memes.url, memes.meme_id '.
               'FROM users '.
               'JOIN favorites ON favorites.user_id = users.user_id '.
               'JOIN memes ON favorites.meme_id = memes.meme_id '.
               'WHERE users.user_id =:user_id';
        $stmt = $this->DB->prepare ($cmd);
        $stmt->bindParam('user_id', $user_id);
        $stmt->execute ();
        return $stmt->fetchAll (PDO::FETCH_ASSOC);
    }

    /* 
     * Receives a random number and returns that particular
     */
    public function getMeme($meme_id) {
        $stmt = $this->DB->prepare ( 'SELECT * FROM memes ' .
                                     'WHERE meme_id=:meme_id;');
        $stmt->bindParam('meme_id', $meme_id);
        $stmt->execute ();
        return $stmt->fetchAll (PDO::FETCH_ASSOC);
    }

    /* 
     * The function gets a username and hashed password and returns
     * the entire table entry of the associated user
     * PARAMS: $username -- the user's requested username
     * RETURN: the [users] table entry for that given user
     */
    public function getUser($username){
        $cmd = 'SELECT * FROM users WHERE username=:username;';
        $stmt = $this->DB->prepare($cmd);
        $stmt->bindParam('username', $username);
        $stmt->execute();
        return $stmt->fetchAll (PDO::FETCH_ASSOC);
    }

    /* 
     * register() attempts to register a new user.
     * If the user doesn't exist, it is added to the database with its
     * data returned to the controller as an array.
     * If the user exists, it returns an empty array
     * PARAMS: $username -- the user's requested username
     *         $hash -- a hashed password
     * RETURN: Array() if username exists
     *         Array(...), if username is newly added
     */
    public function register($username, $hash){
        $stmt = $this->DB->prepare('SELECT * FROM users '.
                                    'WHERE username=:username;');
        $stmt->bindParam('username', $username);
        $stmt->execute();
        $arr = $stmt->fetchAll (PDO::FETCH_ASSOC);

        /*
         * if count($arr) == 0, then there are no users by that username in
         * the users table. If there is a user by that name, return an empty
         * Array()
         */
        if(count($arr) == 0){
            $cmd = "INSERT into users (username, hash) " .
                "VALUES (:username, :hash);";
            $stmt = $this->DB->prepare($cmd);
            $stmt->bindParam('username', $username);
            $stmt->bindParam('hash', $hash);
            $stmt->execute();

            return $this->getUser($username);
        }
        else{
            return Array();
        }
    }

    /* 
     * Removes the $meme_id from the list of $user_id's favorites
     */
    public function removeFavorite($user_id, $meme_id) {
        $cmd = 'DELETE FROM favorites ' .
               'WHERE user_id=:userId AND meme_id=:memeId;';
        $stmt = $this->DB->prepare ($cmd);        
        $stmt->bindParam('userId', $user_id);
        $stmt->bindParam('memeId', $meme_id);
        $stmt->execute ();
    }

} /* end DatabaseAdapter class */

?>