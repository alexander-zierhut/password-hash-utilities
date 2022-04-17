<?php

    namespace ZubZet\Utilities\PasswordHash;

    abstract class Salt {

        // Generate a cryptographically secure salt for a given length
        public static function generate($length = 32) {
            return substr(
                bin2hex(random_bytes($length)),
                0, $length,
            );
        }

    }

?>