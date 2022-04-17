<?php

    namespace ZubZet\Utilities\PasswordHash\Exceptions;

    use ZubZet\Utilities\PasswordHash\Exceptions\IException;
    use ZubZet\Utilities\PasswordHash\Exceptions\AbstractException;

    class AlgorithmNotCallable extends AbstractException implements IException {

        public function errorMessage() {
            return "The algorithm '{$this->value}' is not callable.";
        }

    }

?>