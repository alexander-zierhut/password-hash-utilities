<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PermutationOption;

    abstract class Pepper {

        // Generate a pepper using the $charUniverse
        public static function generate() {
            $universe = PasswordHash::getCharacterUniverse();
            $charOffset = random_int(0, count($universe) - 1);
            return $universe[$charOffset];
        }

        // Generate all options after applying the pepper
        public static function generateAllOptions(string $userInput, string $salt) {
            $passwordOptions = [];
            foreach(PasswordHash::getCharacterUniverse() as $pepper) {
                $passwordOptions[] = new PermutationOption($userInput, $salt, $pepper);
            }
            return $passwordOptions;
        }

    }

?>