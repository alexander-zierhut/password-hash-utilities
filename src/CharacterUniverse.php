<?php

    namespace ZubZet\Utilities\PasswordHash;

    trait CharacterUniverse {

        // The current character universe
        private static array $characterUniverse;

        /**
         * Set a custom character universe This will be
         * used to generate the pepper
         *
         * @param string[] $characterUniverseParam An array including characters that will be used
         * @return void
         */
        public static function setCharacterUniverse(array $characterUniverseParam) {
            self::$characterUniverse = $characterUniverseParam;
        }

        /**
         * Get the current custom character universe
         *
         * @return string[] The character universe, if none exists a-z will be used
         */
        public static function getCharacterUniverse() : array {
            if(empty(self::$characterUniverse)) {
                self::$characterUniverse = range("a", "z");
            }
            return self::$characterUniverse;
        }

    }

?>