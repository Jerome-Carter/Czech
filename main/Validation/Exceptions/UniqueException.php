<?php

namespace Czech\Validation\Exceptions;

class UniqueException extends \Respect\Validation\Exceptions\ValidationException {

    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'This value is already in use'
        ]
    ];

}