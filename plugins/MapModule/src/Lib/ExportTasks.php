<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace MapModule\Lib;


use Alchemy\Zippy\Zippy;
use App\Lib\PluginExportTasks;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use MapModule\Model\Table\MapsTable;
use MapModule\Model\Table\RotationsTable;

class ExportTasks implements PluginExportTasks {

    public function __construct() {
        Configure::load('nagios');
        $this->conf = Configure::read('nagios.export');
    }

    public function beforeExport(): bool {
        if (!\Cake\Core\Plugin::isLoaded('DistributeModule')) {
            return true;
        }

        $zippy = Zippy::load();

        //Create zip file with all maps per satellite if DistributeModule is loaded

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');
        /** @var RotationsTable $RotationsTable */
        $RotationsTable = TableRegistry::getTableLocator()->get('MapModule.Rotations');
        /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
        $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

        $json = [
            'maps'      => [],
            'rotations' => []
        ];
        foreach ($SatellitesTable->getSatellitesAsList() as $satelliteId => $satelliteName) {
            $files = [];

            $maps = $MapsTable->getMapsBySatelliteId($satelliteId, false);
            foreach ($maps as $map) {
                $mapForSatellite = $MapsTable->getMapForSatelliteExport($map['id'], $satelliteId);
                $json['maps'][] = $mapForSatellite;
                $files = Hash::merge($files, $this->getFilesForZipArchive($mapForSatellite));
            }

            $json['rotations'] = $RotationsTable->getRotationsWithMapsBySatelliteId(1);
        }


        if (!is_dir($this->conf['satellite_path'] . $satelliteId)) {
            mkdir($this->conf['satellite_path'] . $satelliteId);
        }

        $mapZipArchive = $this->conf['satellite_path'] . $satelliteId . DS . 'maps.zip';
        $mapJson = $this->conf['satellite_path'] . $satelliteId . DS . 'maps.json';
        if (file_exists($mapZipArchive)) {
            unlink($mapZipArchive);
        }
        $archive = $zippy->create($mapZipArchive, $files);

        if (file_exists($mapJson)) {
            unlink($mapJson);
        }

        $fd = fopen($mapJson, 'w+');
        fwrite($fd, json_encode($json, JSON_PRETTY_PRINT));
        fclose($fd);

        return true;
    }

    public function afterExport(): bool {
        return true;
    }

    /**
     * @param array $mapForSatellite
     * @return array
     */
    private function getFilesForZipArchive($mapForSatellite) {
        $basePath = APP . '../' . 'plugins' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS;

        $files = [];

        if (!empty($mapForSatellite['background'])) {
            if (file_exists($basePath . 'backgrounds' . DS . $mapForSatellite['background'])) {
                $files['backgrounds' . DS . $mapForSatellite['background']] = $basePath . 'backgrounds' . DS . $mapForSatellite['background'];
            }
        }

        //Add stateless icons
        foreach ($mapForSatellite['mapicons'] as $mapicon) {
            if (file_exists($basePath . 'icons' . DS . $mapicon['icon'])) {
                $files['icons' . DS . $mapicon['icon']] = $basePath . 'icons' . DS . $mapicon['icon'];
            }
        }

        //Add missing icon
        $files['items/missing.png'] = $basePath . 'items' . DS . 'missing.png';

        //Add iconsets
        $iconsets = array_unique(Hash::extract($mapForSatellite, 'mapitems.{n}.iconset'));
        foreach ($iconsets as $iconset) {
            if (is_dir($basePath . 'items' . DS . $iconset)) {
                $files['items' . DS . $iconset] = $basePath . 'items' . DS . $iconset;
            }
        }

        return $files;
    }
}
