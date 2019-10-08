<?php
Configure::load('assets');

if (Configure::read('debug') == 0 && file_exists(WWW_ROOT . 'css/app_build.css')) {
    echo $this->Html->css('app_build.css');
} else {
    echo $this->Html->css(Configure::read('assets.css_bs4'));
}
echo $this->fetch('css');

$DesignModule = OLD_APP . 'Plugin' . DS . 'DesignModule' . DS . 'webroot' . DS . 'css' . DS . 'style.css';
if (file_exists($DesignModule)) {

    if (!Cache::read('design_module_style_time', 'long')) {
        Cache::write('design_module_style_time', time(), 'long');
    }
    $suffix = Cache::read('design_module_style_time', 'long');

    printf('<link rel="stylesheet" type="text/css" href="/design_module/css/style.css?%s"/>', $suffix);

    //echo $this->Html->css('/design_module/css/style.css');
}

