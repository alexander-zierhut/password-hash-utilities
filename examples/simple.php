<?php

    require_once __DIR__."/../vendor/autoload.php";

    use ZubZet\Utilities\PasswordHash\PasswordHash;

    $password = PasswordHash::createPassword("password123");
    $check = PasswordHash::checkPassword("password123", $password["hash"], $password["salt"]);

    var_dump($check);

?>