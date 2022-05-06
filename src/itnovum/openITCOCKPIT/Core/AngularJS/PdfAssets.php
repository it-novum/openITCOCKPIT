<?php


namespace App\itnovum\openITCOCKPIT\Core\AngularJS;


class PdfAssets {

    /**
     * @return array
     */
    static public function getCssFiles() {
        return [
            '/node_modules/bootstrap/dist/css/bootstrap.css',
            '/smartadmin4/dist/css/vendors.bundle.css',

            // Attention !!!
            // If Font Awesome in the PDF is not working, most likely someone updated the file app.bundle.css
            // search for "@media print" and remove the font-family line !!
            // Line 7675 /*font-family: Arial, Helvetica, sans-serif !important;*/ /* This kills Font Awesome in PDF generation! */
            '/smartadmin4/dist/css/app.bundle.css',

            '/node_modules/font-awesome/css/font-awesome.min.css', // font-awesome 4.x
            '/node_modules/@fortawesome/fontawesome-free/css/all.min.css',
            '/css/openitcockpit-colors.css',
            '/css/openitcockpit-utils.css',
            '/css/openitcockpit.css',
            '/css/openitcockpit-pdf.css',

        ];
    }

}
