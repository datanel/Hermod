<?php

namespace AppBundle\Http\Exception;


class UnauthorizedException extends HttpErrorException
{
    public function __construct($errorMessages, $type = 'unauthorized')
    {
        parent::__construct($errorMessages, $type);
    }

    public function getHttpStatusCode()
    {
        return 401;
    }
}
