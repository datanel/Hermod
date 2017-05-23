<?php

namespace AppBundle\Http\Exception;

/**
 * Base class for every http exception that can be thrown by the app
 * All exceptions derived from this class will be caught by a Kernel exception listener
 * and displayed as an human readable error
 */
abstract class HttpErrorException extends \Exception implements \JsonSerializable
{
    public $messages = ['An error occurred'];
    public $type;

    public function __construct($errorMessages, $type = 'error_occurred')
    {
        $this->messages = (array)$errorMessages;
        $this->type = $type;
    }

    abstract public function getHttpStatusCode();

    public function jsonSerialize()
    {
        return [
            'error' => $this->type,
            'messages' => $this->messages
        ];
    }
}
