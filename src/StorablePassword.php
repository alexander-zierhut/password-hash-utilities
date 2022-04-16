<?php
    namespace ZubZet\Utilities\PasswordHash;

    class StorablePassword {
        public function __construct(
            public string $hash,
            public string $salt,
            public ?string $hashingName,
            public ?string $customLogicName,
        ) {}
    }

?>