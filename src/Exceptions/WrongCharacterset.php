<?php

    namespace ZubZet\Utilities\PasswordHash\Exceptions;

    use ZubZet\Utilities\PasswordHash\Exceptions\AbstractException;
    use ZubZet\Utilities\PasswordHash\Exceptions\IException;

    class WrongCharacterset extends AbstractException implements IException {

        public function errorMessage() {
            return "Please only use the following characters: '{$this->value}'.";
        }
        
    }

?>