<?php

namespace App\Exceptions;

use App\Exceptions\ApiBaseException;

class ObjectNotExists extends ApiBaseException
{
    public $status = 404;
}