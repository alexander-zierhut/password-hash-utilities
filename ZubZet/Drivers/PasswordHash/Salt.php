<?php

    namespace ZubZet\Drivers\PasswordHash;

    abstract class Salt {

        //Salt generator
        private static function generateSalt() {
            return hash("sha512", random_int(10000, 99999), true);
        }
        
    }

?>