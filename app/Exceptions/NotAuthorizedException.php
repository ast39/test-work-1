<?php

namespace App\Exceptions;


class NotAuthorizedException extends \Exception {

    protected $message;

    protected $code = 403;


    public function __construct()
    {
        parent::__construct();

        $this->message = __('error.global.403');
    }
}
