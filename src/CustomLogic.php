<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PasswordHash;
    use ZubZet\Utilities\PasswordHash\PermutationOption;

    use ZubZet\Utilities\PasswordHash\Exceptions\HashingAlgorithmNotFound;
    use ZubZet\Utilities\PasswordHash\Exceptions\HashingAlgorithmNotCallable;

    abstract class CustomLogic {

        public static function applyToAll($permutations, ?string $logicName = null) {
            $results = [];
            foreach($permutations as $permutation) {
                $results[] = self::apply($permutation, $logicName);
            }
            return $results;
        }

        public static function apply(PermutationOption $permutation, ?string $logicName = null) {
            if(is_null($logicName)) {
                return (string) $permutation;
            }

            if(!isset(PasswordHash::$customLogics[$logicName])) {
                throw new HashingAlgorithmNotFound($logicName);
            }

            $customLogic = PasswordHash::$customLogics[$logicName];

            if(!is_callable($customLogic)) {
                throw new HashingAlgorithmNotCallable($logicName);
            }

            return $customLogic(
                $permutation->userInput,
                $permutation->salt,
                $permutation->pepper
            );
        }

    }

?>