<?php

    require_once __DIR__."/../vendor/autoload.php";

    use ZubZet\Utilities\PasswordHash\PasswordHash;

    $password = PasswordHash::create("password123");
    $check = PasswordHash::check(
        "password123",
        $password->hash,
        $password->salt,
        $password->hashingName
    );

    var_dump($check->matches);

?>