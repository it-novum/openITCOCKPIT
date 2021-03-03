<?php


namespace itnovum\openITCOCKPIT\ConfigGenerator;


use Cake\Core\Configure;

class PhpFpmOitc extends ConfigGenerator implements ConfigInterface {
    /** @var string */
    protected $templateDir = 'PhpFpmOitc';
    /** @var string */
    protected $template = 'oitc.conf.tpl';
    /** @var string */
    protected $realOutfile = '';
    /** @var string */
    protected $linkedOutfile = '';
    /** @var string */
    protected $commentChar = ';';
    /** @var array */
    protected $defaults = [
        'int' => [
            'max_children' => 5,
        ],
    ];

    /**
     * PhpFpmOitc constructor.
     * determines the current php version as the file gets written in the latest php-fpm pool.d directory
     * this changes with every php update (7.4 to 7.5 to 7.6 and so on)
     */
    public function __construct() {
        try {
            $version = phpversion();
            preg_match('/[^.]*.[^.]*/', $version, $match);
            if (is_array($match)) {
                $version = $match[0];
                if (file_exists('/etc/php/' . $version)) {
                    $this->realOutfile = '/etc/php/' . $version . '/fpm/pool.d/oitc.conf';
                    $this->linkedOutfile = '/etc/php/' . $version . '/fpm/pool.d/oitc.conf';
                }else{
                    throw new \Exception('/etc/php/' . $version. ' directory does not exists');
                }
            }else{
                throw new \Exception('PHP version could not be determined');
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }
    }

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
            'max_children' => __('Number of child processes.')
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
        return $this->mergeDbResultWithDefaultConfiguration($dbRecords);
    }
}