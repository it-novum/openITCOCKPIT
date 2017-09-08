<?php

namespace Ddeboer\Transcoder\Exception;

class UndetectableEncodingException extends \RuntimeException
{
    public function __construct($string, $error)
    {
        parent::__construct(sprintf('Encoding for %s is undetectable: %s', $string, $error));
    }
}
