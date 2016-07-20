<?php

use itnovum\openITCOCKPIT\ApiShell\OptionParser;
use itnovum\openITCOCKPIT\ApiShell\ApiExtensionLoader;

class ApiShell extends AppShell{

    public function main(){
        $optionParser = new OptionParser();
        $optionParser->parse($this->params, $this->args);
        $plugin = $optionParser->getPlugin();
        $model = $optionParser->getModel();

        $apiExtensionLoader = new ApiExtensionLoader($this, $model, $plugin);
        if($apiExtensionLoader->isAvailable() === false){
            $this->err(sprintf('Model %s is not supported by this shell', $model));
            exit(1);
        }

        $api = $apiExtensionLoader->getApi();
        $api->setOptionsFromOptionParser($optionParser);
        $api->dispatchRequest($optionParser);

    }

    public function getOptionParser(){
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'plugin' => ['short' => 'p', 'help' => 'Name of the plugin to modify. If empty the request gets routet to the core'],
            'model' => ['short' => 'm', 'help' => 'Name of the model', 'requried' => true],
            'action' => ['short' => 'a', 'help' => "add|update|delete"],
            'data' => ['short' => 'd', 'help' => "String of data"],

        ]);
        return $parser;
    }

    public function _welcome(){

    }

}


