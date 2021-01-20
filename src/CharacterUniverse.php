<?php

    namespace ZubZet\Drivers\PasswordHash;

    trait CharacterUniverse {

        /** The current character universe */
        private static array $characterUniverse;

        /**
         * Set a custom character universe
         * 
         * Will be used to generate the pepper
         * 
         * @param array|string $characterUniverseParam
         * @return void
         */
        public static function setCharacterUniverse($characterUniverseParam) {
            self::$characterUniverse = $characterUniverseParam;
        }

        /**
         * Get the current custom character universe
         *
         * @return array
         */
        public static function getCharacterUniverse() : array {
            if(empty(self::$characterUniverse)) {
                self::$characterUniverse = range("a", "z");
            }
            return self::$characterUniverse;
        }

    }

?>