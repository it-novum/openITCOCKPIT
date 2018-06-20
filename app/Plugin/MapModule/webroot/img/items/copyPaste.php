<?php 


$dir = new DirectoryIterator(dirname('.'));
foreach ($dir as $fileinfo) {
    if($fileinfo->isDot()){
        continue;
    }
    if(!$fileinfo->isDir()){
        continue;
    }
    $folder = $fileinfo->getFilename();
    copy($folder.'/ok.png', $folder.'/downtime_ack.png');
}

?>