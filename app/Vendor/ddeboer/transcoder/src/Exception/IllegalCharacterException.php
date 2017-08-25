<?php

namespace Ddeboer\Transcoder\Exception;

class IllegalCharacterException extends \RuntimeException
{
    public function __construct($string, $warning)
    {
        parent::__construct(
            sprintf(
                'String "%s" contains an illegal character: %s',
                $string,
                $warning
            )
        );
    }
}
