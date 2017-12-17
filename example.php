<?php

   include("passwordHandler.php");

   passwordHandler::setCharUniverse("abcdefghijklmnopqrstuvwxyz");

  //Generate a new hash and salt for a password (min 3 chars)
  /*Returns: Array
             (
                 [hash] => string
                 [salt] => string
             )
  */
  $pw = passwordHandler::createPassword("password123");

  //Check a password using user Input, hash and salt
  //Returns: true for a correct password
  //         false for an incorrect password
  $check = passwordHandler::checkPassword("password123", $pw["hash"], $pw["salt"]);

?>
