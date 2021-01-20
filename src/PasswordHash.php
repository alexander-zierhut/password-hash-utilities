<?php

    namespace ZubZet\Drivers\PasswordHash;

    use ZubZet\Drivers\PasswordHash\CharacterUniverse;

    use ZubZet\Drivers\PasswordHash\Hash;
    use ZubZet\Drivers\PasswordHash\Salt;
    use ZubZet\Drivers\PasswordHash\Pepper;

    use ZubZet\Drivers\PasswordHash\Exceptions\MissingParameter;
    use ZubZet\Drivers\PasswordHash\Exceptions\UnmetInputRequirements;

    abstract class PasswordHash {

        use CharacterUniverse;
        
        //Custom spagetti logic
        private static function customAlg($str) {
            $str .= str_repeat(substr($str, 2), 2);
            $str = strrev($str);
            $str = self::customAlgEnc($str);
            return $str;
        }
        
        //Encryption function: uses str as key
        private static function customAlgEnc($string) {
            $key = $string;
            $result = '';
            for($i=0; $i<strlen ($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key))-1, 1);
                $char = chr(ord($char)+ord($keychar));
                $result .= $char;
            }
            return base64_encode($result);
        }
        
        //Function to process a password using hash and custom logic
        private static function processStr($str) {
            return Hash::hashStr(self::customAlg($str));
        }
        
        //Function to generate all Pepper options
        private static function generateCheckOptions($userInput, $salt) {
            $passwordOptions = [];
            foreach(self::getCharacterUniverse() as $pepper) {
                array_push($passwordOptions, self::processStr($userInput.$salt.$pepper));
            }
            return $passwordOptions;
        }
        
        //PasswordHandler to generate a new hash and salt for a password
        public static function createPassword(string $userInput) {
            if (empty($userInput)) throw new MissingParameter('Please specify the userInput parameter');
            if (strlen($userInput) < 3) throw new UnmetInputRequirements('A password must at least have 3 chars');
            $salt = Salt::generate();
            return array("hash" => self::processStr(base64_encode($userInput) . $salt . Pepper::generate()), "salt" => $salt);
        }
        
        //PasswordHandler to check if a password is correct
        public static function checkPassword(string $userInput, string $hash, string $salt) {
            if (empty($userInput)) throw new MissingParameter('Please specify the userInput parameter');
            if (empty($hash)) throw new MissingParameter('Please specify the hash parameter');
            if (empty($salt)) throw new MissingParameter('Please specify the salt parameter');
            if (strlen($userInput) < 3) return false;
            $pwInputOptions = self::generateCheckOptions(base64_encode($userInput), $salt);
            return in_array($hash, $pwInputOptions);
        }
        
    }

?>
