<?php

namespace App\Exceptions\CRUD;

use App\Exceptions\BaseException;

class CreateException extends BaseException{
    protected $statusCode = 400;
    protected $message = 'Məlumat əlavə olunan zaman xəta meydana gəldi!';
}
