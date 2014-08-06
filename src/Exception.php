<?php

namespace Puffer;

class Exception extends \Exception
{

    protected $friendly_message = null;

    public function __construct($message, $code = 0, $friendly_message = null)
    {
        $this->friendly_message = $friendly_message;

        return parent::__construct($message, $code);
    }

    public function getFriendlyMessage()
    {
        return $this->friendly_message;
    }

}
