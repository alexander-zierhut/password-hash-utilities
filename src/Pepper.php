<?php

    namespace ZubZet\Drivers\PasswordHash;

    abstract class Pepper {

        //Pepper generator using $charUniverse
        public static function generate() {
            $index = array_rand(PasswordHash::getCharacterUniverse());
            return PasswordHash::getCharacterUniverse()[$index];
        }
        
    }

?>