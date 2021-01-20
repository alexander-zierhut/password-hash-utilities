<?php

    namespace ZubZet\Drivers\PasswordHash\Exceptions;

    use ZubZet\Drivers\PasswordHash\Exceptions\AbstractException;
    use ZubZet\Drivers\PasswordHash\Exceptions\IException;

    class UnmetInputRequirements extends AbstractException implements IException {

        public function errorMessage() {
            return "Input '{$this->value}' does not meet requirements.";
        }

    }

?>