<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PasswordHash;

    use ZubZet\Utilities\PasswordHash\Exceptions\AlgorithmNotFound;
    use ZubZet\Utilities\PasswordHash\Exceptions\AlgorithmNotCallable;

    abstract class Hash {

        //any str function
        public static function generate(string $str, string $hashName = "bcrypt") {
            if(!isset(PasswordHash::$hashingAlgorithms[$hashName])) {
                throw new AlgorithmNotFound($hashName);
            }

            $hashAlgorithm = PasswordHash::$hashingAlgorithms[$hashName]["forward"];

            if(!is_callable($hashAlgorithm)) {
                throw new AlgorithmNotCallable($hashName);
            }

            // Ruh the hashing algorithm
            return $hashAlgorithm($str);
        }

    }

?>