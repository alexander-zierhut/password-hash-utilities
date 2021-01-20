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

        public static function setHashingAlgorithm(callable $HashingAlgorithm) {

        }

        public static function defineCustomLogic(callable $customLogic) {
            
        }

        //TODO: allow for passwords to be merged
        //PasswordHandler to generate a new hash and salt for a password
        public static function createPassword(string $userInput) {
            if (empty($userInput)) throw new MissingParameter('Please specify the userInput parameter');
            if (strlen($userInput) < 3) throw new UnmetInputRequirements('A password must at least have 3 chars');
            
            // TODO: Allow for custom logic here

            $userInput = Hash::hashStr($userInput);
            $salt = Salt::generate();
            return [
                "hash" => $userInput . $salt . Pepper::generate(),
                "salt" => $salt
            ];
        }
        
        //PasswordHandler to check if a password is correct
        public static function checkPassword(string $userInput, string $hash, string $salt) {
            if (empty($userInput)) throw new MissingParameter('Please specify the userInput parameter');
            if (empty($hash)) throw new MissingParameter('Please specify the hash parameter');
            if (empty($salt)) throw new MissingParameter('Please specify the salt parameter');
            if (strlen($userInput) < 3) return false;

            $userInput = Hash::hashStr($userInput);
            return in_array($hash, Pepper::generateAllOptions($userInput, $salt));
        }
        
    }

?>
