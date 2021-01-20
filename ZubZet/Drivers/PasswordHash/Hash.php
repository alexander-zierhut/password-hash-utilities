<?php

    namespace ZubZet\Drivers\PasswordHash;

    class Hash {

        //sha512 str function
        private static function hashStr($str) {
            return hash('sha512', $str);
        }
        
    }

?>