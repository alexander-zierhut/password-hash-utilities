<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\CharacterUniverse;

    use ZubZet\Utilities\PasswordHash\Hash;
    use ZubZet\Utilities\PasswordHash\Salt;
    use ZubZet\Utilities\PasswordHash\Pepper;
    use ZubZet\Utilities\PasswordHash\CustomLogic;
    use ZubZet\Utilities\PasswordHash\CompatibilityDefinitions;

    use ZubZet\Utilities\PasswordHash\StorablePassword;
    use ZubZet\Utilities\PasswordHash\VerificationResult;

    use ZubZet\Utilities\PasswordHash\Exceptions\MissingParameter;
    use ZubZet\Utilities\PasswordHash\Exceptions\WrongCharacterset;
    use ZubZet\Utilities\PasswordHash\Exceptions\UnmetInputRequirements;

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
        /**
         * Registers a new hashing algorithm.
         *
         * @param string $name Name of the hashing algorithm. The same name must be given as a parameter in ::check() and ::create()
         * @param callable $hashingAlgorithm Hashing function. Takes a parameter as the user input and returns the hashed version.
         * @param callable|null $verifyAlgorithm Takes two parameters. The plainText and a hash. Returns a boolean whether the hash matched.
         * @return void
         */
        public static function setHashingAlgorithm(string $name, callable $hashingAlgorithm, ?callable $verifyAlgorithm = null) {
            self::checkCharacterMap($name);
            self::$hashingAlgorithms[$name] = [
                "forward" => $hashingAlgorithm,
                "verify" => $verifyAlgorithm
            ];
        }

        public static $customLogics = [];
        /**
         * Registers a new custom logic.
         *
         * @param string $name Name of the logic. The same name must be given as a parameter in ::check() and ::create()
         * @param callable $customLogic Custom logic function. The function takes three argument: $input, $salt, $pepper and should return the modified input as string.
         * @return void
         */
        public static function defineCustomLogic(string $name, callable $customLogic) {
            self::checkCharacterMap($name);
            self::$customLogics[$name] = $customLogic;
        }

        /**
         * Created a storable password from a user input
         *
         * @param string $userInput The user supplies password
         * @param string $hashingName The name of the algorithm used
         * @param string|null $customLogicName The name of the custom logic algorithm used
         * @return StorablePassword An instance of the resulting password
         */
        public static function create(
            string $userInput,
            string $hashingName = "bcrypt",
            ?string $customLogicName = null
        ): StorablePassword {
            if (empty($userInput)) throw new MissingParameter("userInput");
            if (strlen($userInput) < 3) throw new UnmetInputRequirements("userInput");

            $salt = Salt::generate();
            $pepper = Pepper::generate();

            // Generate the new permutation
            $permutation = new PermutationOption(
                $userInput,
                $salt,
                $pepper
            );

            // Run the custom Logic
            $permutation = CustomLogic::apply(
                $permutation,
                $customLogicName,
            );

            // Hash the permutation
            $hash = Hash::generate(
                $permutation,
                $hashingName,
            );

            return new StorablePassword(
                $hash,
                $salt,
                $hashingName,
                $customLogicName
            );
        }

        /**
         * Verifies if a user input matches.
         *
         * @param string $userInput Plain text password to check
         * @param string $hash Hash as stored in the database
         * @param string $salt Salt as stored in the database
         * @param string $hashingName Name of the hashing algorithm used
         * @param string|null $customLogicName Name of the custom logic used
         * @return boolean
         */
        public static function checkWithoutUpdate(
            string $userInput,
            string $hash,
            string $salt,
            string $hashingName = "bcrypt",
            ?string $customLogicName = null,
        ): bool {
            if (strlen($userInput) < 3) return false;
            if (empty($hash)) throw new MissingParameter('hash');
            if (empty($salt)) throw new MissingParameter('salt');

            // Generate all options based on all possible peppers
            $pepperBasedPermutations = CustomLogic::applyToAll(
                Pepper::generateAllOptions($userInput, $salt),
                $customLogicName
            );

            // If a different verification function is needed, run this one 
            if (is_callable(self::$hashingAlgorithms[$hashingName]["verify"])) {
                $match = false;
                foreach($pepperBasedPermutations as $permutation) {
                    if(self::$hashingAlgorithms[$hashingName]["verify"]($permutation, $hash)) {
                        // Run each option every time to avoid leaking
                        // data based on time used.
                        $match = true;
                    }
                }
                return $match;
            }

            // No verifications method is given, forward and
            // reverse are deterministic

            $hashPermutations = array_map(
                function($pepperBasedPermutation) use ($hashingName) {
                    return Hash::generate(
                        $pepperBasedPermutation,
                        $hashingName
                    );
                },
                $pepperBasedPermutations
            );

            // Do not reuse the same order for all peppers
            shuffle($hashPermutations);

            // Check all possibilities
            $match = false;
            foreach($hashPermutations as $hashPermutation) {
                if(hash_equals($hash, $hashPermutation)) {
                    $match = true;
                }
            }
            return $match;
        }

        /**
         * Verifies if a user input matches a hash and gives a new version of the hash if the algorithm should be changed.
         *
         * @param string $userInput Plain text password to check
         * @param string $hash Hash as stored in the database
         * @param string $salt Salt as stored in the database
         * @param string $hashingName Name of the hashing algorithm used
         * @param string|null $customLogicName Name of the custom logic used
         * @param string $hashingNameTarget Name of the hashing algorithm to update the hash to
         * @param string|null $customLogicNameTarget Name of the custom logic to update the hash to
         * @return VerificationResult
         */
        public static function check(
            string $userInput,
            string $hash,
            string $salt,
            string $hashingName,
            ?string $customLogicName = null,
            string $hashingNameTarget = "bcrypt",
            ?string $customLogicNameTarget = null,
        ): VerificationResult {
            $result = self::checkWithoutUpdate(
                $userInput,
                $hash,
                $salt,
                $hashingName,
                $customLogicName,
            );

            // Set hasUpdate, if a newer method of storing the password is set as target
            $hasUpdate = $hashingName !== $hashingNameTarget;
            $hasUpdate |= $customLogicName !== $customLogicNameTarget;

            // Only make an update available if the password matches
            if($hasUpdate && $result) {
                $password = self::create(
                    $userInput,
                    $hashingNameTarget,
                    $customLogicNameTarget,
                );
                $hash = $password->hash;
                $salt = $password->salt;
                $hashingName = $hashingNameTarget;
                $customLogicName = $customLogicNameTarget;
            }

            return new VerificationResult(
                $result,
                $hasUpdate && $result,
                $hash,
                $salt,
                $hashingName,
                $customLogicName,
            );
        }

    }

    CompatibilityDefinitions::addSupport09();
    CompatibilityDefinitions::default();
?>
