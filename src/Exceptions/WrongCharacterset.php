<?php

    namespace ZubZet\Drivers\PasswordHash\Exceptions;

    use ZubZet\Drivers\PasswordHash\Exceptions\AbstractException;
    use ZubZet\Drivers\PasswordHash\Exceptions\IException;

    class WrongCharacterset extends AbstractException implements IException {

        public function errorMessage() {
            return "Please only use the following characters: '{$this->value}'.";
        }
        
    }

?>