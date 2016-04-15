<?php
class CsvComponent extends Component{
    var $delimiter = ';';
    var $enclosure = ' ';
    var $filename = 'Export.csv';
    var $line = array();
    var $buffer;

    function CsvComponent(){
        $this->clear();
    }

    function setPath($path){
        $this->buffer = fopen($path, 'w');
    }

    function saveFile(){
        fclose($this->buffer);
    }

    function readZip($zipPath, $files, $render){
        if(file_exists($zipPath)){
            unlink($zipPath);
        }
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE)!==TRUE) {
            exit('Cant create zip file');
        }
        foreach($files as $file){
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        foreach($files as $file){
            unlink($file);
        }        
        if($render){

            $zipName = basename($zipPath);

            header("Content-Type: application/zip");
            header("Content-Disposition: attachment; filename=$zipName");
            header("Content-Length: " . filesize($zipPath));

            readfile($zipPath);
        }
    }
    
    function clear() {
        $this->line = array();
        $this->buffer = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
    }

    function addField($value){
        $this->line[] = $value;
    }

    function endRow() {
        $this->addRow($this->line);
        $this->line = array();
    }

    function addRow($row) {
        fputcsv($this->buffer, $row, $this->delimiter, $this->enclosure);
    }

    function renderHeaders() {
        header('Content-Type: text/csv');
        header("Content-type:application/vnd.ms-excel");
        header("Content-disposition:attachment;filename=".$this->filename);
    }

    function setFilename($filename) {
        $this->filename = $filename;
        if (strtolower(substr($this->filename, -4)) != '.csv') 
        {
            $this->filename .= '.csv';
        }
    }

    // function render($outputHeaders = true, $to_encoding = null, $from_encoding ="auto") {
    //     if ($outputHeaders) 
    //     {
    //         if (is_string($outputHeaders)) 
    //         {
    //             $this->setFilename($outputHeaders);
    //         }
    //         $this->renderHeaders();
    //     }
    //     rewind($this->buffer);
    //     $output = stream_get_contents($this->buffer);

    //     if ($to_encoding) 
    //     {
    //         $output = mb_convert_encoding($output, $to_encoding, $from_encoding);
    //     }
    //     return $this->output($output);
    // }
}
