<?php

    namespace ZubZet\Utilities\PasswordHash\Exceptions;

    use ZubZet\Utilities\PasswordHash\Exceptions\IException;
    use ZubZet\Utilities\PasswordHash\Exceptions\AbstractException;

    class UnmetInputRequirements extends AbstractException implements IException {

        public function errorMessage() {
            return "Input '{$this->value}' does not meet requirements.";
        }

    }

?>