<?php


namespace itnovum\openITCOCKPIT\ConfigGenerator;


use Cake\Core\Configure;

class PhpFpmOitc extends ConfigGenerator implements ConfigInterface {
    /** @var string */
    protected $templateDir = 'PhpFpmOitc';
    /** @var string */
    protected $template = 'oitc.conf.tpl';
    /** @var string */
    protected $realOutfile = '/etc/php/7.4/fpm/pool.d/oitc.conf';
    /** @var string */
    protected $linkedOutfile = '/etc/php/7.4/fpm/pool.d/oitc.conf';
    /** @var string */
    protected $commentChar = ';';
    /** @var array */
    protected $defaults = [
        'int'    => [
            'max_children'   => 5,
        ],
    ];

    /**
     * @return string
     */
    public function getAngularDirective() {
        return 'php-fpm-oitc';
    }

    protected $dbKey = 'PhpFpmOitc';

    /**
     * @param array $data
     * @return array|bool|true
     */
    public function customValidationRules($data) {
        return true;
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public function getHelpText($key) {
        $help = [
            'max_children'    => __('Number of child processes.')
        ];

        if (isset($help[$key])) {
            return $help[$key];
        }

        return '';
    }

    /**
     * @param array $dbRecords
     * @return mixed
     */
    public function writeToFile($dbRecords) {
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);
        $configToExport = [];
        foreach ($config as $type => $fields) {
            foreach ($fields as $key => $value) {
                $configToExport[$key] = $value;
            }
        }
        return $this->saveConfigFile($configToExport, $this->realOutfile);
    }


    /**
     * @param array $dbRecords
     * @return bool
     */
    public function migrate($dbRecords) {
        if (!file_exists($this->linkedOutfile)) {
            return false;
        }
        $config = $this->mergeDbResultWithDefaultConfiguration($dbRecords);
        /*
                Configure::load('gearman');
                $configFromFile = Configure::read('gearman');

                foreach ($config['string'] as $field => $value) {
                    if (isset($configFromFile['SSH'][$field])) {
                        if ($config['string'][$field] != $configFromFile['SSH'][$field]) {
                            $config['string'][$field] = $configFromFile['SSH'][$field];
                        }
                    }
                }
                if (isset($configFromFile['SSH']['port'])) {
                    if ($config['int']['remote_port'] != $configFromFile['SSH']['port']) {
                        $config['int']['remote_port'] = $configFromFile['SSH']['port'];
                    }
                }
        */
        return $config;
    }
}