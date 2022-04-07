<?php

namespace App\Lib;



use Endroid\QrCode\QrCode;

class QrCodeGenerator {

    /** @var string */
    private $content = '';

    /** @var string */
    private $encoding = 'UTF-8';

    /** @var int */
    private $size = 244;

    /**
     * @return string
     */
    public function getQrCodeAsBase64(): string {
        if(empty($this->content)){
            return '';
        }

        try {
            $result = new QrCode($this->content);
            $result->setEncoding($this->encoding);
            $result->setSize($this->size);

            return $result->writeDataUri();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        return '';
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void {
        $this->content = $content;
    }

    /**
     * @param string $encoding
     */
    public function setEncoding(string $encoding): void {
        $this->encoding = $encoding;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void {
        $this->size = $size;
    }


}
