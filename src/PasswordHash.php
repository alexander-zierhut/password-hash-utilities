<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\CharacterUniverse;

    use ZubZet\Utilities\PasswordHash\Hash;
    use ZubZet\Utilities\PasswordHash\Salt;
    use ZubZet\Utilities\PasswordHash\Pepper;

    use ZubZet\Utilities\PasswordHash\Exceptions\MissingParameter;
    use ZubZet\Utilities\PasswordHash\Exceptions\UnmetInputRequirements;
    use ZubZet\Utilities\PasswordHash\Exceptions\WrongCharacterset;

    abstract class PasswordHash {

        use CharacterUniverse;

        private static function checkCharacterMap(string $parameter) {
            $charList = ["_", "-", ".", ...range("a", "z"), ...range("0", "9")];
            foreach(str_split(strtolower($parameter)) as $char) {
                if(!in_array($char, $charList)) {
                    throw new WrongCharacterset(implode("", $charList));
                }
            }
        }

        public static $hashingAlgorithms = [];
        public static function setHashingAlgorithm(string $name, callable $hashingAlgorithm) {
            self::checkCharacterMap($name);
            self::$hashingAlgorithms[$name] = $hashingAlgorithm;
        }

        public static $customLogics = [];
        public static function defineCustomLogic(string $name, callable $customLogic) {
            self::checkCharacterMap($name);
            self::$hashingAlgorithms[$name] = $customLogic;
        }

        //PasswordHandler to generate a new hash and salt for a password
        public static function createPassword(string $userInput, array $options = []) {
            if (empty($userInput)) throw new MissingParameter("userInput");
            if (strlen($userInput) < 3) throw new UnmetInputRequirements("userInput");
            
            $salt = Salt::generate();
            $result = [
                "hash" => Hash::generate($userInput . $salt . Pepper::generate()),
                "salt" => $salt
            ];

            if($options["mergedMode"] ?? false) return implode(";", $result);
            return $result;
        }
        
        //PasswordHandler to check if a password is correct
        //, bool $compactMode = true
        public static function checkPassword(string $userInput, string $hash, string $salt) {
            if (strlen($userInput) < 3) return false;
            if (empty($hash)) throw new MissingParameter('hash');
            if (empty($salt)) throw new MissingParameter('salt');

            $hashOptions = array_map(function($hashOption) {
                return Hash::generate($hashOption);
            }, Pepper::generateAllOptions($userInput, $salt));
            return in_array($hash, $hashOptions);
        }
        
    }

?>
