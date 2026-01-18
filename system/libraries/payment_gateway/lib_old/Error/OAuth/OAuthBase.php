<?php

namespace lib\Error\OAuth;

class OAuthBase extends \lib\Error\Base
{
    public function __construct(
        $code,
        $description,
        $httpStatus = null,
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null
    ) {
        parent::__construct($description, $httpStatus, $httpBody, $jsonBody, $httpHeaders);
        $this->code = $code;
    }

    public function getErrorCode()
    {
        return $this->code;
    }
}
