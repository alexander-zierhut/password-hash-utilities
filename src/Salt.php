<?php

    namespace ZubZet\Utilities\PasswordHash;

    abstract class Salt {

        // Salt generator
        public static function generate($length = 32) {
            return substr(
                bin2hex(random_bytes($length)),
                0, $length,
            );
        }

    }

?>