<?php
Configure::load('assets');

if (Configure::read('debug') == 0  && file_exists(WWW_ROOT.'css/app_build.css')) {
    echo $this->Html->css('app_build.css');
} else {
    echo $this->Html->css(Configure::read('assets.css'));
}
echo $this->fetch('css');

$DesignModule = APP.'Plugin'.DS.'DesignModule'.DS.'webroot'.DS.'css'.DS.'style.css';
if(file_exists($DesignModule)){
    echo $this->Html->css('/design_module/css/style.css');
}

