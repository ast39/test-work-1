<?php

namespace App\Exceptions;


class UserNotFoundException extends \Exception {

    protected $message;

    protected $code = 404;


    public function __construct()
    {
        parent::__construct();

        $this->message = __('error.user.404');
    }
}
