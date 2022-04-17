<?php
    namespace ZubZet\Utilities\PasswordHash;

    /**
     * A data model of A resulting password
     */
    class StorablePassword {
        public function __construct(
            public string $hash,
            public string $salt,
            public ?string $hashingName,
            public ?string $customLogicName,
        ) {}
    }

?>