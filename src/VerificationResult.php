<?php

    namespace ZubZet\Utilities\PasswordHash;

    use ZubZet\Utilities\PasswordHash\StorablePassword;

    class VerificationResult extends StorablePassword {
        public function __construct(
            public bool $matches,
            public bool $hasUpdate,
            string $hash,
            string $salt,
            ?string $hashingName,
            ?string $customLogicName,
        ) {
            parent::__construct($hash, $salt, $hashingName, $customLogicName);
        }
    }

?>