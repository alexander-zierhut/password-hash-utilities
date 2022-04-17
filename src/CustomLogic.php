<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\PasswordHash;
    use ZubZet\Utilities\PasswordHash\PermutationOption;

    use ZubZet\Utilities\PasswordHash\Exceptions\AlgorithmNotFound;
    use ZubZet\Utilities\PasswordHash\Exceptions\AlgorithmNotCallable;

    abstract class CustomLogic {
        public static function applyToAll(array $permutations, ?string $logicName = null): array {
            $results = [];
            foreach($permutations as $permutation) {
                $results[] = self::apply($permutation, $logicName);
            }
            return $results;
        }

        public static function apply(PermutationOption $permutation, ?string $logicName = null): string {
            // If no custom logic name is supplied, no algorithm is applied
            if(is_null($logicName)) {
                return (string) $permutation;
            }

            if(!isset(PasswordHash::$customLogics[$logicName])) {
                throw new AlgorithmNotFound($logicName);
            }

            $customLogic = PasswordHash::$customLogics[$logicName];

            if(!is_callable($customLogic)) {
                throw new AlgorithmNotCallable($logicName);
            }

            // Run the custom logic
            return $customLogic(
                $permutation->userInput,
                $permutation->salt,
                $permutation->pepper
            );
        }

    }

?>