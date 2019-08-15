<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

use Statusengine\PerfdataParser;

class Rrd extends AppModel {
    var $useTable = false;
    public $rrd_path = null;

    const REDUCE_METHOD_STEPS = 1;
    const REDUCE_METHOD_AVERAGE = 2;
    const MAX_RESPONSE_GRAPH_POINTS = 1000;

    public function getPerfDataFiles($host_uuid, $service_uuid, $options = [], $service_value = null, $type = 'avg') {
        $result = [];

        App::uses('Folder', 'Utility');
        $rrd_path = Configure::read('rrd.path');

        if (empty($host_uuid) || empty($service_uuid)) {
            return $result;
        }

        $perfdata_dir = new Folder($rrd_path . $host_uuid);
        $perfdata_files = $perfdata_dir->find($service_uuid . '.(xml|rrd)', true);
        if (!isset($perfdata_files[0]) || !isset($perfdata_files[1])) {
            return $result;
        }
        $xml_data = ['xml_data' => $this->getPerfDataStructure($perfdata_dir->pwd() . '/' . $perfdata_files[1])];
        $perfdata_from_rrd = $this->getPerfDataFromRrd($perfdata_dir->pwd() . '/' . $perfdata_files[0], $options, $type);

        if (!empty($xml_data['xml_data']) && !is_null($service_value)) { // ignoring other values if $service_value is set
            $neededIndex = null;
            $notNeededIndexes = [];
            foreach ($xml_data['xml_data'] as $xmlIndex => $xmlArr) {
                if ($service_value === $xmlArr['ds']) {
                    $neededIndex = intval($xmlArr['ds']);
                    continue;
                }
                $notNeededIndexes[$xmlIndex] = $xmlArr['ds'];
            }
            if (isset($perfdata_from_rrd['data'][$neededIndex]) && !empty($notNeededIndexes)) { // we found needed value, we can unset the other values
                foreach ($notNeededIndexes as $notNeededIndex => $notNeededDS) {
                    unset($xml_data['xml_data'][$notNeededIndex]);
                    unset($perfdata_from_rrd['data'][$notNeededDS]);
                }

            }
        }

        return array_merge($xml_data, $perfdata_from_rrd);
    }

    public function getPerfDataStructure($xml_path) {
        App::uses('Xml', 'Utility');
        $rrd_structure = [];
        try {
            $xml_rrd = Xml::build($xml_path); // Here will throw a Exception
        } catch (XmlException $e) {
            return false;
        }
        $xml_rrd_as_array = Xml::toArray($xml_rrd);

        $xml_rrd_structure = ['ds', 'name', 'label', 'unit', 'act', 'warn', 'crit', 'min', 'max'];
        //debug($xml_rrd_as_array['NAGIOS']['DATASOURCE']);
        if (isset($xml_rrd_as_array['NAGIOS']['DATASOURCE'])) {
            if (count($xml_rrd_as_array['NAGIOS']['DATASOURCE']) ==
                count($xml_rrd_as_array['NAGIOS']['DATASOURCE'], COUNT_RECURSIVE)
            ) {
                array_push($rrd_structure, array_combine($xml_rrd_structure, [
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['DS'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['NAME'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['LABEL'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['UNIT'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['ACT'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['WARN'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['CRIT'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['MIN'],
                    $xml_rrd_as_array['NAGIOS']['DATASOURCE']['MAX'],
                ]));
            } else {
                foreach ($xml_rrd_as_array['NAGIOS']['DATASOURCE'] as $xml_rrd_info) {
                    array_push($rrd_structure, array_combine($xml_rrd_structure, [
                        $xml_rrd_info['DS'],
                        $xml_rrd_info['NAME'],
                        $xml_rrd_info['LABEL'],
                        $xml_rrd_info['UNIT'],
                        $xml_rrd_info['ACT'],
                        $xml_rrd_info['WARN'],
                        $xml_rrd_info['CRIT'],
                        $xml_rrd_info['MIN'],
                        $xml_rrd_info['MAX'],
                    ]));
                }
            }
        }

        return $rrd_structure;
    }


    /**
     * Return the performance data structure as array or an empty array, if no performance data structure was found.
     *
     * @param string $host_uuid
     * @param string $service_uuid
     *
     * @return array
     */
    public function getPerfDataStructureByHostAndServiceUuid($host_uuid, $service_uuid) {
        $perfdataStructure = [];
        if ($this->isValidHostAndServiceUuid($host_uuid, $service_uuid)) {
            $path = $this->rrd_path . $host_uuid . '/' . $service_uuid . '.xml';
            $perfdataStructure = $this->getPerfDataStructure($path);
        }

        return $perfdataStructure;
    }

    /**
     * @param $host_and_service_uuids
     *
     * @return array
     */
    public function getPerfDataStructures($host_and_service_uuids) {
        $result = [];
        foreach ($host_and_service_uuids as $host_uuid => $services) {
            foreach ($services as $service_uuid) {
                $perfDataStructure = $this->getPerfDataStructureByHostAndServiceUuid($host_uuid, $service_uuid);
                $result[$host_uuid][$service_uuid] = $perfDataStructure;
            }
        }

        return $result;
    }

    public function getPerfDataFromRrd($rrd_path = null, $options = [], $type = 'avg') {
        switch ($type) {
            case 'MIN':
            case 'min':
                $type = 'MIN';
                break;

            case 'MAX':
            case 'max':
                $type = 'MAX';
                break;

            default:
                $type = 'AVERAGE';
                break;
        }

        $_options = [
            'start'     => time() - (24 * 3600 * 31),
            'end'       => time(),
            'step_size' => 60,
        ];
        $_options = Hash::merge($_options, $options);

        list($start, $end) = $this->fixResolution($_options['start'], $_options['end'], $_options['step_size']);

        $options = [
            $type,
            '--resolution',
            $_options['step_size'],
            '--start',
            $start,
            '--end',
            $end,
        ];


        $perfdata = rrd_fetch($rrd_path, $options);


        if (empty($perfdata) || empty($perfdata['data'])) {
            return [];
        }


        $dataSources = array_keys($perfdata['data']); //save memory
        foreach ($dataSources as $dsIndex) {
            foreach (array_keys($perfdata['data'][$dsIndex]) as $timestamp) {
                if (is_nan($perfdata['data'][$dsIndex][$timestamp])) {
                    $perfdata['data'][$dsIndex][$timestamp] = null;
                }
            }
        }

        return $perfdata;

    }

    public function convert($size) {
        $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    public function isValidHostUuid($host_uuid) {
        App::uses('Folder', 'Utility');
        if (!isset($this->rrd_path)) {
            $this->rrd_path = Configure::read('rrd.path');
        }

        return \itnovum\openITCOCKPIT\Core\UUID::is_valid($host_uuid) && is_dir($this->rrd_path . $host_uuid);
    }

    public function isValidHostAndServiceUuid($host_uuid, $service_uuid) {
        App::uses('Folder', 'Utility');
        if (!isset($this->rrd_path) || $this->rrd_path == null) {
            $this->rrd_path = Configure::read('rrd.path');
        }

        $base_path = $this->rrd_path . $host_uuid . '/' . $service_uuid;
        $xml_file_name = $base_path . '.xml';
        $rrd_file_name = $base_path . '.rrd';

        if (\itnovum\openITCOCKPIT\Core\UUID::is_valid($service_uuid) && \itnovum\openITCOCKPIT\Core\UUID::is_valid($host_uuid) &&
            file_exists($xml_file_name) && file_exists($rrd_file_name)
        ) {

            return true;
        }

        return false;
    }

    public function createRrdGraph($rrd_structure_datasource, $options, $rrd_options = [], $showThreshold = false) {
        putenv('TZ=' . AuthComponent::user('timezone'));
        $unit = ' ';
        if (!empty($rrd_structure_datasource['unit'])) {
            $unit = $rrd_structure_datasource['unit'];
        }

        if ($unit == '%%') {
            $unit = '%';
        }

        $width = (isset($options['width'])) ? $options['width'] : 850;
        $color = (isset($options['color'])) ? $options['color'] : 'BACK#FFFFFF';
        $legend = (isset($options['legend'])) ? $options['legend'] : '';
        $height = (isset($options['height'])) ? $options['height'] : '';

        $_rrd_options = [
            '--slope-mode',
            '--start', $options['start'],
            '--end', $options['end'],
            '--width', $width,

            '--border', 1,
            '--title=' . (isset($options['label']) ? $options['label'] : $rrd_structure_datasource['label']),
            '--vertical-label=' . $unit,
            '--imgformat', 'PNG',
            'DEF:var0=' . $options['path'] . $options['host_uuid'] . DS . $options['service_uuid'] . '.rrd:' . $rrd_structure_datasource['ds'] . ':AVERAGE',
            'AREA:var0#5CB85C90:' . preg_replace('/[^a-zA-Z^0-9\-\.]/', ' ', $rrd_structure_datasource['label']),
            'LINE1:var0#5CB85C',
            'VDEF:ds' . $rrd_structure_datasource['ds'] . 'avg=var0,AVERAGE',
            'GPRINT:ds' . $rrd_structure_datasource['ds'] . 'avg:' . __('Average') . '\:%6.2lf %S',
            'VDEF:ds' . $rrd_structure_datasource['ds'] . 'min=var0,MINIMUM',
            'GPRINT:ds' . $rrd_structure_datasource['ds'] . 'min:' . __('Minimum') . '\:%6.2lf %S',
            'VDEF:ds' . $rrd_structure_datasource['ds'] . 'max=var0,MAXIMUM',
            'GPRINT:ds' . $rrd_structure_datasource['ds'] . 'max:' . __('Maximum') . '\:%6.2lf %S',
            'CDEF:predict=' . (3600 * 24) . ',-31,900,var0,PREDICT',
            'LINE2:predict#337AB7:Trend Prediction',
        ];

        if (!empty($legend)) {
            array_push($_rrd_options, $legend);
        }

        if (!empty($height)) {
            array_push($_rrd_options, '--height', $height);
        }

        if (is_array($color)) {
            foreach ($color as $c) {
                array_push($_rrd_options, '--color');
                array_push($_rrd_options, $c);
            }
        } else {
            array_push($_rrd_options, '--color');
            array_push($_rrd_options, $color);
        }

        if ($showThreshold) {
            if (isset($rrd_structure_datasource['warn']) && $rrd_structure_datasource['warn'] > 0) {
                $__rrd_options = [
                    'LINE1:' . $rrd_structure_datasource['warn'] . '#FFFF00:' . __('Warning')
                ];
                $_rrd_options = Hash::merge($_rrd_options, $__rrd_options);
            }
            if (isset($rrd_structure_datasource['crit']) && $rrd_structure_datasource['crit'] > 0) {
                $__rrd_options = [
                    'LINE1:' . $rrd_structure_datasource['crit'] . '#FF0000:' . __('Critical')
                ];
                $_rrd_options = Hash::merge($_rrd_options, $__rrd_options);
            }
        }

        if (empty($rrd_options)) {
            $rrd_options = $_rrd_options;
        }

        $targetPath = WWW_ROOT . 'img' . DS . 'graphs';

        if (!is_dir($targetPath)) {
            mkdir($targetPath);
        }
        $fileName = md5(rand() . time() . rand()) . '.png';
        $ret = rrd_graph($targetPath . DS . $fileName, $rrd_options);
        if ($ret) {
            return [
                'webPath'  => DS . 'img' . DS . 'graphs' . DS . $fileName,
                'diskPath' => $targetPath . DS . $fileName,
            ];
        } else {
            return rrd_error();
        }

        return false;
    }

    public function createRrdGraphFromTemplate($rrdOptions) {
        $targetPath = WWW_ROOT . 'img' . DS . 'graphs';

        putenv('TZ=' . AuthComponent::user('timezone'));

        if (!is_dir($targetPath)) {
            mkdir($targetPath);
        }
        $fileName = md5(rand() . time() . rand()) . '.png';
        $ret = rrd_graph($targetPath . DS . $fileName, $rrdOptions);
        if ($ret) {
            return [
                'webPath'  => DS . 'img' . DS . 'graphs' . DS . $fileName,
                'diskPath' => $targetPath . DS . $fileName,
            ];
        } else {
            return rrd_error();
        }

        return false;
    }

    /**
     * The start and end time have to be a multiple of the given resolution.
     * This function fixes the given start and end time based on the given resolution.
     *
     * @param int $start Timestamp
     * @param int $end Timestamp
     * @param int $resolution
     *
     * @return Array The fixed start and end values for the given resolution.
     */
    protected function fixResolution($start, $end, $resolution) {
        $fixed_start = $this->fixValueForResolution($start, $resolution);
        $fixed_end = $this->fixValueForResolution($end, $resolution);

        return [$fixed_start, $fixed_end];
    }

    private function fixValueForResolution($value, $resolution) {
        return ((int)($value / $resolution)) * $resolution;
    }

    public function parsePerfData($perfdata_string) {
        $StatusenginePerformanceDataParser = new PerfdataParser($perfdata_string);
        return $StatusenginePerformanceDataParser->parse();
    }

    public function reduceData($data, $limit = 500, $technique = self::REDUCE_METHOD_AVERAGE) {
        switch ($technique) {
            case self::REDUCE_METHOD_STEPS:
                return $this->reduceDataBySteps($data, $limit);
            case self::REDUCE_METHOD_AVERAGE:
                return $this->reduceDataByAverage($data, $limit);
            default:
                return $data;
        }
    }

    private function reduceDataByAverage($data, $limit = 500) {
        $data_count = count($data);
        if ($data_count <= $limit) {
            return $data;
        }

        $percent = $data_count / $limit;
        $step_size = ceil($percent);

        $i = 1;
        $result = [];
        $average_value_of_last_step = 0;
        $average_time_of_last_step = 0;
        foreach ($data as $timestamp => $value) {
            $average_value_of_last_step += $value;
            $average_time_of_last_step += $timestamp;
            if ($i % $step_size == 0) {
                $result[(int)($average_time_of_last_step / $step_size)] = $average_value_of_last_step / $step_size;

                $average_value_of_last_step = 0;
                $average_time_of_last_step = 0;
            }
            $i++;
        }

        return $result;
    }

    private function reduceDataBySteps($data, $limit = 500) {
        $data_count = count($data);
        if ($data_count <= $limit) {
            return $data;
        }

        $percent = $data_count / $limit;
        $steps = ceil($percent);

        $i = 0;
        $result = [];
        foreach ($data as $key => $value) {
            if ($i % $steps == 0) {
                $result[$key] = $value;
            }
            $i++;
        }

        return $result;
    }
}
