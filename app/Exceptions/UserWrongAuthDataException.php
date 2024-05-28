<?php

namespace App\Exceptions;


class UserWrongAuthDataException extends \Exception {

    protected $message;

    protected $code = 404;


    public function __construct()
    {
        parent::__construct();

        $this->message = __('error.global.400');
    }
}
