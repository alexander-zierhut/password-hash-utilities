<?php

    namespace ZubZet\Drivers\PasswordHash\Exceptions;

    class UnmetInputRequirements extends \Exception {

        public function errorMessage() {
            return "User input '{$this->getMessage()}' does not meet requirements.";
        }

    }

?>