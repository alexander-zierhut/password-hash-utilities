<?php

    namespace ZubZet\Utilities\PasswordHash;

    class PermutationOption {

        public function __construct(
            public string $userInput,
            public string $salt,
            public string $pepper
        ) {}

        public function __toString() {
            return $this->userInput . $this->salt . $this->pepper;
        }

    }

?>