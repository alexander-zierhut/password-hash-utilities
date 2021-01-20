<?php

    namespace ZubZet\Drivers\PasswordHash;

    abstract class Hash {

        //sha512 str function
        public static function hashStr($str) {
            return hash('sha512', $str);
        }
        
    }

?>