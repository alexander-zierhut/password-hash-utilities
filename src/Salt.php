<?php

    namespace ZubZet\Drivers\PasswordHash;

    abstract class Salt {

        //Salt generator
        public static function generate() {
            return hash("sha512", random_int(100000000, 999999999), true);
        }
        
    }

?>