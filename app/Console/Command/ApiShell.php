<?php

use itnovum\openITCOCKPIT\ApiShell\ApiExtensionLoader;
use itnovum\openITCOCKPIT\ApiShell\OptionParser;
use itnovum\openITCOCKPIT\ApiShell\RootUser;

class ApiShell extends AppShell {

    public function main() {
        $rootUser = new RootUser();
        if ($rootUser->isRootUser() === false) {
            $this->err('This shell only can be used by root user');
            exit(1);
        }


        $optionParser = new OptionParser();
        $optionParser->parse($this->params, $this->args);

        $apiExtensionLoader = new ApiExtensionLoader($this, $optionParser);
        if ($apiExtensionLoader->isAvailable() === false) {
            $this->err(sprintf('Model %s is not supported by this shell', $optionParser->getModel()));
            exit(1);
        }

        $api = $apiExtensionLoader->getApi();
        $api->setOptionsFromOptionParser($optionParser);

        if ($optionParser->getIgnoreErrors() === true) {
            try {
                $api->dispatchRequest($optionParser);
            } catch (\Exception $e) {
                echo $e->getMessage();
                echo PHP_EOL;
            }
        } else {
            $api->dispatchRequest($optionParser);
        }

    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'plugin'        => ['short' => 'p', 'help' => 'Name of the plugin to modify. If empty the request gets routet to the core'],
            'model'         => ['short' => 'm', 'help' => 'Name of the model', 'requried' => true],
            'action'        => ['short' => 'a', 'help' => "add|update|delete"],
            'ignore-errors' => ['help' => 'Try to ignore errors'],
            'data'          => ['short' => 'd', 'help' => "String of data"],

        ]);

        return $parser;
    }

    public function _welcome() {

    }

}


