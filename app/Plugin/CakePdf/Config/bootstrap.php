<?php
App::build(['Pdf' => ['%s'.'Pdf'.DS]], App::REGISTER);
App::build(['Pdf/Engine' => ['%s'.'Pdf/Engine'.DS]], App::REGISTER);
App::uses('PdfView', 'CakePdf.View');