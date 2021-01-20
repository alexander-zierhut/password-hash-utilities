<?php

    require_once __DIR__."/../vendor/autoload.php";

    use ZubZet\Drivers\PasswordHash\PasswordHash;

    //Set a custom CharUniverse
    PasswordHash::setCharacterUniverse(range("a", "z"));

    PasswordHash::setHashingAlgorithm("sha5125", function($input) {
        return hash('sha512', $input);
    });

    PasswordHash::defineCustomLogic("0.9", function($input) {
        $input .= str_repeat(substr($input, 2), 2);
        $input = strrev($input);
        $key = $input;
        $result = '';
        for($i = 0; $i < strlen ($input); $i++) {
            $char = substr($input, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    });

    $pw = PasswordHash::createPassword("password123");
    var_dump($pw);

    //Check a password using user Input, hash and salt
    //Returns: true for a correct password
    //         false for an incorrect password
    $check = PasswordHash::checkPassword("password123", $pw["hash"], $pw["salt"]);

    var_dump($check);

?>