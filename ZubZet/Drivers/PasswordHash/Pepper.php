<?php

    namespace ZubZet\Drivers\PasswordHash;

    abstract class Pepper {

        //Pepper generator using $charUniverse
        private static function generatePepper() {
            $randCharID = rand(0, strlen(self::$charUniverse) - 1);
            return self::$charUniverse[$randCharID];
        }
        
    }

?>