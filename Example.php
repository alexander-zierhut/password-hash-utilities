<?php

    require_once __DIR__."/vendor/autoload.php";

    use ZubZet\Drivers\PasswordHash\PasswordHash;

    //Set a custom CharUniverse
    PasswordHash::setCharacterUniverse(range("a", "z"));

    //Generate a new hash and salt for a password (min 3 chars)
    /*Returns: Array
                (
                    [hash] => string
                    [salt] => string
                )
    */
    $pw = PasswordHash::createPassword("password123");

    //Check a password using user Input, hash and salt
    //Returns: true for a correct password
    //         false for an incorrect password
    $check = PasswordHash::checkPassword("password123", $pw["hash"], $pw["salt"]);

    var_dump($check);

?>