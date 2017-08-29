<?php

namespace Ddeboer\Transcoder;

use Ddeboer\Transcoder\Exception\UnsupportedEncodingException;

interface TranscoderInterface
{
    /**
     * Transcode a string from one into another encoding
     *
     * @param string $string String
     * @param string $from   From encoding (optional)
     * @param string $to     To encoding (optional)
     *
     * @return string
     * 
     * @throws UnsupportedEncodingException
     */
    public function transcode($string, $from = null, $to = null);
}
