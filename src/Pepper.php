<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PermutationOption;

    abstract class Pepper {

        //Pepper generator using $charUniverse
        public static function generate() {
            $index = array_rand(PasswordHash::getCharacterUniverse());
            return PasswordHash::getCharacterUniverse()[$index];
        }

        //Function to generate all Pepper options
        public static function generateAllOptions(string $userInput, string $salt) {
            $passwordOptions = [];
            foreach(PasswordHash::getCharacterUniverse() as $pepper) {
                $passwordOptions[] = new PermutationOption($userInput, $salt, $pepper);
            }
            return $passwordOptions;
        }

    }

?>