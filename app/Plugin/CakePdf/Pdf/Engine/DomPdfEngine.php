<?php
App::uses('AbstractPdfEngine', 'CakePdf.Pdf/Engine');
App::uses('Multibyte', 'I18n');

class DomPdfEngine extends AbstractPdfEngine
{

    /**
     * Constructor
     *
     * @param $Pdf CakePdf instance
     */
    public function __construct(CakePdf $Pdf)
    {
        parent::__construct($Pdf);
        if (!defined('DOMPDF_FONT_CACHE')) {
            define('DOMPDF_FONT_CACHE', OLD_TMP);
        }
        if (!defined('DOMPDF_TEMP_DIR')) {
            define('DOMPDF_TEMP_DIR', OLD_TMP);
        }

        App::import('Vendor', 'CakePdf.DomPDF', ['file' => 'dompdf'.DS.'dompdf_config.inc.php']);
    }

    /**
     * Generates Pdf from html
     * @return string raw pdf data
     */
    public function output()
    {
        $DomPDF = new DOMPDF();
        $DomPDF->set_paper($this->_Pdf->pageSize(), $this->_Pdf->orientation());
        $DomPDF->load_html($this->_Pdf->html());
        $DomPDF->render();

        return $DomPDF->output();
    }

}