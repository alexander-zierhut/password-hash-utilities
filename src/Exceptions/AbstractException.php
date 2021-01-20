<?php

    namespace ZubZet\Drivers\PasswordHash\Exceptions;

    class AbstractException extends \Exception {

        protected string $value;
        public function __construct($value) {
            $this->value = $value;
            throw new \Exception($this->errorMessage($value));
        }

    }

?>