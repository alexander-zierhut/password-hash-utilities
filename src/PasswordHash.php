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
        public static function setHashingAlgorithm(string $name, callable $hashingAlgorithm, ?callable $verifyAlgorithm = null) {
            self::checkCharacterMap($name);
            self::$hashingAlgorithms[$name] = [
                "forward" => $hashingAlgorithm,
                "verify" => $verifyAlgorithm
            ];
        }

        public static $customLogics = [];
        public static function defineCustomLogic(string $name, callable $customLogic) {
            self::checkCharacterMap($name);
            self::$customLogics[$name] = $customLogic;
        }

        //PasswordHandler to generate a new hash and salt for a password
        public static function create(
            string $userInput,
            string $hashingName = "bcrypt",
            ?string $customLogicName = null
        ): StorablePassword {
            if (empty($userInput)) throw new MissingParameter("userInput");
            if (strlen($userInput) < 3) throw new UnmetInputRequirements("userInput");

            $salt = Salt::generate();
            $pepper = Pepper::generate();

            $permutation = new PermutationOption(
                $userInput,
                $salt,
                $pepper
            );

            // Custom Logic
            $permutation = CustomLogic::apply(
                $permutation,
                $customLogicName,
            );

            // Hash
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

        //PasswordHandler to check if a password is correct
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

            $pepperBasedPermutations = CustomLogic::applyToAll(
                Pepper::generateAllOptions($userInput, $salt),
                $customLogicName
            );

            if (is_callable(self::$hashingAlgorithms[$hashingName]["verify"])) {
                $found = false;
                foreach($pepperBasedPermutations as $permutation) {
                    if(self::$hashingAlgorithms[$hashingName]["verify"]($permutation, $hash)) {
                        $found = true;
                    }
                }
                return $found;
            }

            // No verifications method is given, forward and reverse are determenistic

            $hashPermutations = array_map(function($pepperBasedPermutation) use ($hashingName) {
                return Hash::generate(
                    $pepperBasedPermutation,
                    $hashingName
                );
            }, $pepperBasedPermutations);

            shuffle($hashPermutations);

            foreach($hashPermutations as $hashPermutation) {
                if(hash_equals($hash, $hashPermutation)) {
                    return true;
                }
            }

            return false;
        }

        public static function check(
            string $userInput,
            string $hash,
            string $salt,
            string $hashingName,
            string $hashingNameTarget = "bcrypt",
            ?string $customLogicName = null,
            ?string $customLogicNameTarget = null,
        ): VerificationResult {
            $result = self::checkWithoutUpdate(
                $userInput,
                $hash,
                $salt,
                $hashingName,
                $customLogicName,
            );

            $hasUpdate = $hashingName !== $hashingNameTarget;
            $hasUpdate |= $customLogicName !== $customLogicNameTarget;

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
