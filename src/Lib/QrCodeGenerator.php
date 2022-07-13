<?php

namespace App\Lib;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;


class QrCodeGenerator {

    /** @var string */
    private $content = '';

    /**
     * @param $content
     */
    public function __construct($content) {
        $this->content = $content;
    }


    /**
     * @return string
     */
    public function getQrCodeAsBase64(): string {
        if (empty($this->content)) {
            return '';
        }
        try {
            $options = new QROptions([
            ]);
            return (new QRCode($options))->render($this->content);

        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return '';
    }
}
