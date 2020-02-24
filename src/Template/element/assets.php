<?php
Configure::load('assets');


// Run frontend plugin
$this->Frontend->init($frontendData);
if (Configure::read('debug') == 0 && file_exists(WWW_ROOT . 'js/app_build.js') && file_exists(WWW_ROOT . 'css/app_build.css')) {
    echo $this->Html->css('app_build.css');
    echo $this->Frontend->getAppDataJs();
    echo $this->Html->script('app_build.js');
} else {
    $this->Frontend->addAllControllers();
    echo $this->Frontend->run();
    echo $this->Html->css(Configure::read('assets.css'));
    echo $this->Html->script(Configure::read('assets.js'));
} ?>

    <!--[if lt IE 9]>
    <script src="/js/vendor/html5shiv.js"></script>
    <script src="/js/vendor/respond.min.js"></script>
    <![endif]-->

<?php
#if(in_array(Inflector::underscore($this->name), array('system_contents', 'articles', 'galleries'))) {
/*echo $this->Html->script(array(
    '/ck_editor/js/ckeditor/ckeditor.js',
    '/ck_editor/js/ckeditor/adapters/jquery.js',
    'vendor/ckfinder/ckfinder.js'
)); */
#}

echo $this->fetch('meta');
echo $this->fetch('css');
echo $this->fetch('script');

$DesignModule = OLD_APP . 'Plugin' . DS . 'DesignModule' . DS . 'webroot' . DS . 'css' . DS . 'style.css';
if (file_exists($DesignModule)) {
    if (!Cache::read('design_module_style_time', 'long')) {
        Cache::write('design_module_style_time', time(), 'long');
    }
    $suffix = Cache::read('design_module_style_time', 'long');

    printf('<link rel="stylesheet" type="text/css" href="/design_module/css/style.css?%s"/>', $suffix);
    //echo $this->Html->css('/design_module/css/style.css');
}

