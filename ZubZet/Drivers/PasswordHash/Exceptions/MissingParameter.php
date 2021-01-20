<?php

    namespace ZubZet\Drivers\PasswordHash\Exceptions;

    class MissingParameter extends \Exception {

        public function errorMessage() {
            return "The parameter '{$this->getMessage()}' is required and so has to be specified.";
        }
        
    }

?>