<?php

namespace App\Exceptions;


class ImageNotFoundException extends \Exception {

    protected $message;

    protected $code = 404;


    public function __construct()
    {
        parent::__construct();

        $this->message = __('error.image.404');
    }
}
