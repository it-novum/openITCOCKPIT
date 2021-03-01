<?php


namespace itnovum\openITCOCKPIT\ConfigGenerator;


use Cake\Core\Configure;

class Gearman extends ConfigGenerator implements ConfigInterface {
    /** @var string */
    protected $templateDir = 'config';
    /** @var string */
    protected $template = 'gearman.php.tpl';
    /** @var string */
    protected $realOutfile = CONFIG . 'gearman.php';
    /** @var string */
    protected $linkedOutfile = CONFIG . 'gearman.php';
    /** @var string */
    protected $commentChar = '//';
    /** @var array */
    protected $defaults = [
        'string' => [
            'address'  => '127.0.0.1',
            'password' => '3c3b0be215c95321c30de1ab908364dfbf0ed440',
            'pidfile'  => '/var/run/oitc_gearmanworker.pid',
        ],
        'int'    => [
            'port'   => 4730,
            'worker' => 5
        ],
        'bool'   => [
            'encryption' => true
        ]
    ];

    /**
     * @return string
     */
    public function getAngularDirective() {
        return 'gearman-cfg';
    }

    protected $dbKey = 'Gearman';

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
            'address'    => __('The Host where gearman is running.'),
            'password'   => __('Password for Gearman.'),
            'pidfile'    => __('Process id file of gearman.'),
            'port'       => __('Portnumber of gearman.'),
            'encryption' => __('Use encryption.'),
            'worker'     => __('Number of gearman workers.')
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