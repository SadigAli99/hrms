<?php

namespace App\Exceptions\Auth;

use App\Exceptions\BaseException;

class InvalidCredentialsException extends BaseException
{
    protected int $statusCode = 401;
    protected $message = 'Email və ya parol yanlışdır';
}
