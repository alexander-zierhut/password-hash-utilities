<?php

    require_once __DIR__."/../vendor/autoload.php";

    use ZubZet\Utilities\PasswordHash\PasswordHash;

    // Set a custom CharUniverse
    PasswordHash::setCharacterUniverse(range("a", "z"));

    PasswordHash::setHashingAlgorithm(
        "bcryptExpensive",
        function($input) {
            return password_hash($input, PASSWORD_BCRYPT, [
                "cost" => 12
            ]);
        },
        function($input, $hash) {
            return password_verify($input, $hash);
        }
    );

    PasswordHash::defineCustomLogic("reverse", function($input, $salt, $pepper) {
        $input .= $salt.$pepper;
        return strrev($input);
    });

    $password = PasswordHash::create(
        "password123",
        hashingName: "bcryptExpensive",
        customLogicName: "reverse",
    );

    $check = PasswordHash::check(
        "password123",
        $password->hash,
        $password->salt,
        $password->hashingName,
        $hashingNameTarget = "bcrypt",
        $password->customLogicName,
        $customLogicNameTarget = "0.9",
    );

    // This will only be true if the password also matches
    if($check->hasUpdate) {
        // Replace
        $password->hash;
        $password->salt;
        $password->hashingName;
        $password->customLogicName;
    }

    if($check->matches) {
        // Access granted
    } else {
        // Password is wrong
    }

    var_dump($check);

?>