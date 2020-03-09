<?php


namespace App\itnovum\openITCOCKPIT\Core\AngularJS;


class PdfAssets {

    /**
     * @return array
     */
    static public function getCssFiles() {
        return [
            '/smartadmin4/dist/css/vendors.bundle.css',
            '/smartadmin4/dist/css/app.bundle.css',
            '/node_modules/@fortawesome/fontawesome-free/css/all.css',
            '/css/openitcockpit-colors.css',
            '/css/openitcockpit-utils.css',
            '/css/openitcockpit.css',
        ];
    }

}
