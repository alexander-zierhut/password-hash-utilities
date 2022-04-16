<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PasswordHash;

    class CompatibilityDefinitions {

        public static function default() {
            PasswordHash::setHashingAlgorithm(
                "bcrypt",
                    function($input) {
                    return password_hash($input, PASSWORD_BCRYPT, [
                        "cost" => 6
                    ]);
                },
                function($input, $hash) {
                    return password_verify($input, $hash);
                }
            );
        }

        public static function addSupport09() {
            PasswordHash::setHashingAlgorithm("sha512", function($input) {
                return hash('sha512', $input);
            });

            PasswordHash::defineCustomLogic("0.9", function($input, $salt, $pepper) {
                $input = base64_encode($input) . $salt . $pepper;
                $input .= str_repeat(substr($input, 2), 2);
                $input = strrev($input);
                $key = $input;
                $result = '';
                for($i = 0; $i < strlen ($input); $i++) {
                    $char = substr($input, $i, 1);
                    $keyCharacter = substr($key, ($i % strlen($key))-1, 1);
                    $char = chr(ord($char) + ord($keyCharacter));
                    $result .= $char;
                }
                return base64_encode($result);
            });
        }

    }

?>