<?php

    require_once __DIR__."/../vendor/autoload.php";

    use ZubZet\Drivers\PasswordHash\PasswordHash;

    $password = PasswordHash::createPassword("password123");
    $check = PasswordHash::checkPassword("password123", $password);

    var_dump($check);

?>