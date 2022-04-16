<?php

    namespace ZubZet\Utilities\PasswordHash\Exceptions;

    use ZubZet\Utilities\PasswordHash\Exceptions\AbstractException;
    use ZubZet\Utilities\PasswordHash\Exceptions\IException;

    class MissingParameter extends AbstractException implements IException {

        public function errorMessage() {
            return "The parameter '{$this->value}' is required and so has to be specified.";
        }
        
    }

?>