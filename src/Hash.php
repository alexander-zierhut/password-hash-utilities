<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PasswordHash;

    use ZubZet\Utilities\PasswordHash\Exceptions\HashingAlgorithmNotFound;
    use ZubZet\Utilities\PasswordHash\Exceptions\HashingAlgorithmNotCallable;

    abstract class Hash {

        //any str function
        public static function generate(string $str, string $hashName = "bcrypt") {
            if(!isset(PasswordHash::$hashingAlgorithms[$hashName])) {
                throw new HashingAlgorithmNotFound($hashName);
            }

            $hashAlgorithm = PasswordHash::$hashingAlgorithms[$hashName]["forward"];

            if(!is_callable($hashAlgorithm)) {
                throw new HashingAlgorithmNotCallable($hashName);
            }

            return $hashAlgorithm($str);
        }

    }

?>