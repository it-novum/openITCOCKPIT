<?php

class ClearCacheTask extends AppShell
{
    public function execute()
    {
        $this->out('Clearing cache files');
        CakePlugin::load('ClearCache');
        App::uses('ClearCache', 'ClearCache.Lib');
        Configure::write('Cache.disable', true);
        $cleaner = new ClearCache();
        $cleaner->run();
        $this->out('Done');
    }
}