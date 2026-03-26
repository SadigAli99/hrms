<?php

namespace App\Exceptions\CRUD;

use App\Exceptions\BaseException;

class EditException extends BaseException
{
    protected $statusCode = 400;
    protected $message = 'Məlumat yenilənən zaman xəta meydana gəldi!';
}
