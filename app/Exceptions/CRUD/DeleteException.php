<?php

namespace App\Exceptions\CRUD;

use App\Exceptions\BaseException;

class DeleteException extends BaseException
{
    protected $statusCode = 400;
    protected $message = 'Məlumat silinən zaman xəta meydana gəldi!';
}
