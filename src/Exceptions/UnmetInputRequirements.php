<?php

    namespace ZubZet\Utilities\PasswordHash\Exceptions;

    use ZubZet\Utilities\PasswordHash\Exceptions\AbstractException;
    use ZubZet\Utilities\PasswordHash\Exceptions\IException;

    class UnmetInputRequirements extends AbstractException implements IException {

        public function errorMessage() {
            return "Input '{$this->value}' does not meet requirements.";
        }

    }

?>