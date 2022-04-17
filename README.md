# ZubZet Password Hash Utilities
Best enjoyed with `zubzet/framework`, this utility adds support for custom hashing algorithms, the default being `bcrypt`, and logic implementations. Keep your passwords safe and resistant to rainbow tables.

> zubzet/password-hash-utilities changed my life
>
> — Adrian

## System requirements
- PHP version has to be at least 8.0.0

## Installation
Using Composer:
```
composer require zubzet/password-hash-utilities
```

## Examples

### Basic usage
``` PHP
require_once __DIR__."/../vendor/autoload.php";

use ZubZet\Utilities\PasswordHash\PasswordHash;

$password = PasswordHash::create("password123");
$check = PasswordHash::check(
    "password123",
    $password->hash,
    $password->salt,
    $password->hashingName
);

var_dump($check->matches);
```

### Advanced usage
``` PHP
require_once __DIR__."/../vendor/autoload.php";

use ZubZet\Utilities\PasswordHash\PasswordHash;

// Set a custom CharUniverse
PasswordHash::setCharacterUniverse(range("a", "z"));

PasswordHash::setHashingAlgorithm(
    "bcryptExpensive",
    function($input) {
        return password_hash($input, PASSWORD_BCRYPT, [
            "cost" => 12
        ]);
    },
    function($input, $hash) {
        return password_verify($input, $hash);
    }
);

PasswordHash::defineCustomLogic("reverse", function($input, $salt, $pepper) {
    $input .= $salt.$pepper;
    return strrev($input);
});

$password = PasswordHash::create(
    "password123",
    hashingName: "bcryptExpensive",
    customLogicName: "reverse",
);

$check = PasswordHash::check(
    "password123",
    $password->hash,
    $password->salt,
    $password->hashingName,
    $hashingNameTarget = "bcrypt",
    $password->customLogicName,
    $customLogicNameTarget = "0.9",
);

// This will only be true if the password also matches
if($check->hasUpdate) {
    // Replace
    $password->hash;
    $password->salt;
    $password->hashingName;
    $password->customLogicName;
}

if($check->matches) {
    // Access granted
} else {
    // Password is wrong
}

var_dump($check);
```
