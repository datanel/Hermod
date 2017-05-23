<?php

namespace AppBundle\Http\Exception;

class BadRequestException extends HttpErrorException
{
    public function __construct($errorMessages, $type = 'bad_request')
    {
        parent::__construct($errorMessages, $type);
    }

    public function getHttpStatusCode()
    {
        return 400;
    }
}
