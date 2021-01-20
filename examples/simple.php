<?php

    require_once __DIR__."/vendor/autoload.php";

    use ZubZet\Drivers\PasswordHash\PasswordHash;

    $pw = PasswordHash::createPassword("password123");
    $check = PasswordHash::checkPassword("password123", $pw["hash"], $pw["salt"]);

    var_dump($check);

?>