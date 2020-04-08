<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Perfdata;

/*
// Test Examples:
$gauge = [
    'datasource' => [
        'ds'    => 'ms',
        'name'  => 'ms',
        'label' => 'ms',
        'unit'  => 'ms',
        'warn'  => 1000,
        'crit'  => 5000,
        'min'   => 0,
        'max'   => null
    ],
    'data'       => [
        0.001,
        0.002,
        0.003,
        1000,
        100000000000
    ]
];

$gauge = [
    'datasource' => [
        'ds'    => 'd',
        'name'  => 'd',
        'label' => 'd',
        'unit'  => 'd',
        'warn'  => 1000,
        'crit'  => 5000,
        'min'   => 0,
        'max'   => null
    ],
    'data'       => [
        //0.001694444,  // 2,43999936 minuten
        //0.000694444,  // 1 minute
        //0.003694444,  // 5,31999936 minuten,
        1.1574e-6
    ]
];


$UnitScaler = new UnitScaler($gauge);
*/

/**
 * Class UnitScaler
 * @package itnovum\openITCOCKPIT\Perfdata
 *
 */
class UnitScaler {

    /**
     * @var array
     */
    private $gauge;

    /**
     * @var string
     */
    private $unit;

    /**
     * Max value in perfdata array
     * we use to know if scaling is required or not
     * @var mixed
     */
    private $maxValue = null;

    public function __construct($gauge) {
        $this->gauge = $gauge;
        $this->unit = $gauge['datasource']['unit'];
        if (!empty($gauge['data'])) {
            $this->maxValue = max($gauge['data']);
        }
    }

    public function scale() {
        if ($this->maxValue === null) {
            return $this->gauge;
        }

        $targetUnit = $this->getTargetUnit();
        if ($targetUnit === false) {
            return $this->gauge;
        }

        if ($targetUnit['factor'] === 1) {
            //Nothing to scale
            return $this->gauge;
        }

        foreach ($this->gauge['data'] as $timestamp => $value) {
            $this->gauge['data'][$timestamp] = $this->shiftValue($value, $targetUnit);
        }

        foreach (['min', 'max', 'warn', 'crit'] as $key) {
            if (!empty($this->gauge['datasource'][$key])) {
                $this->gauge['datasource'][$key] = $this->shiftValue($this->gauge['datasource'][$key], $targetUnit);
            }
        }


        $this->gauge['datasource']['unit'] = $targetUnit['unit'];
        return $this->gauge;
    }

    private function getTargetUnit() {
        $unitDetails = $this->getUnitDetails();
        if ($unitDetails === false) {
            return false;
        }

        $maxValue = $this->maxValue;
        $targetUnit = [
            'factor' => 1,
            'type'   => 'division',
            'unit'   => $unitDetails['units'][$unitDetails['index']]['unit'][0]
        ];

        if ($maxValue >= 1 && $maxValue > -1) {
            // Scales 100000ms to 69.444444 days
            while (isset($unitDetails['units'][$unitDetails['index'] + 1]) && $maxValue >= $unitDetails['units'][$unitDetails['index'] + 1]['factor']) {
                $currentUnit = $unitDetails['units'][$unitDetails['index']];

                $unitDetails['index']++; //shift to the next greater unit
                $nextUnit = $unitDetails['units'][$unitDetails['index']];

                $maxValue = $maxValue / $currentUnit['factor'];
                $unit = $nextUnit['unit'][0];

                $targetUnit['factor'] = $targetUnit['factor'] * $currentUnit['factor'];
                $targetUnit['unit'] = $unit;
                $targetUnit['type'] = 'division';
            }
        } else {
            // Scales 0.000694444 days to 1 minute
            while (isset($unitDetails['units'][$unitDetails['index'] - 1]) && $maxValue < 1 && $maxValue > -1) {
                $currentUnit = $unitDetails['units'][$unitDetails['index']];

                $unitDetails['index']--; //shift to the next smaller unit
                $previousUnit = $unitDetails['units'][$unitDetails['index']];

                $maxValue = $maxValue * $previousUnit['factor'];


                $unit = $previousUnit['unit'][0];

                $targetUnit['factor'] = $targetUnit['factor'] * $previousUnit['factor'];
                $targetUnit['unit'] = $unit;
                $targetUnit['type'] = 'multiplication';
            }
        }

        return $targetUnit;
    }

    private function shiftValue($value, array $targetUnit) {
        if ($targetUnit['type'] === 'multiplication') {
            return $value * $targetUnit['factor'];
        }

        return $value / $targetUnit['factor'];
    }

    /**
     * @return array|false
     */
    public function getUnitDetails() {
        if (empty($this->unit)) {
            $numbers = [
                0 => [
                    'unit'   => [''],
                    'factor' => 1000
                ],
                1 => [
                    'unit'   => ['k'],
                    'factor' => 1000
                ],
                2 => [
                    'unit'   => ['M'],
                    'factor' => 1000
                ]
            ];

            return [
                'units' => $numbers,
                'index' => 0
            ];
        }

        // is time?
        $time = [
            0 => [
                'unit'   => ['ns'],
                'factor' => 1000
            ],
            1 => [
                'unit'   => ['µs', 'us'],
                'factor' => 1000
            ],
            2 => [
                'unit'   => ['ms'],
                'factor' => 1000
            ],
            3 => [
                'unit'   => ['s', 'sec', 'seconds'],
                'factor' => 60
            ],
            4 => [
                'unit'   => ['m', 'min'],
                'factor' => 60
            ],
            5 => [
                'unit'   => ['h'],
                'factor' => 24
            ],
            6 => [
                'unit'   => ['d'],
                'factor' => 365
            ]
        ];

        foreach ($time as $index => $timeUnit) {
            if (in_array($this->unit, $timeUnit['unit'], true)) {
                return [
                    'units' => $time,
                    'index' => $index
                ];
            }
        }

        // Define SI Units

        // is data (IEC)
        $data = [
            0 => [
                'unit'   => ['B', 'bytes'],
                'factor' => 1024
            ],
            1 => [
                'unit'   => ['KiB'],
                'factor' => 1024
            ],
            2 => [
                'unit'   => ['MiB'],
                'factor' => 1024
            ],
            3 => [
                'unit'   => ['GiB'],
                'factor' => 1024
            ],
            4 => [
                'unit'   => ['TiB'],
                'factor' => 1024
            ],
            5 => [
                'unit'   => ['PiB'],
                'factor' => 1024
            ],
            6 => [
                'unit'   => ['EiB'],
                'factor' => 1024
            ]
        ];
        foreach ($data as $index => $dataUnit) {
            if (in_array($this->unit, $dataUnit['unit'], true)) {
                return [
                    'units' => $data,
                    'index' => $index
                ];
            }
        }

        // is data (SI)
        $data = [
            0 => [
                'unit'   => ['b'],
                'factor' => 1000
            ],
            1 => [
                'unit'   => ['KB'],
                'factor' => 1000
            ],
            2 => [
                'unit'   => ['MB'],
                'factor' => 1000
            ],
            3 => [
                'unit'   => ['GB'],
                'factor' => 1000
            ],
            4 => [
                'unit'   => ['TB'],
                'factor' => 1000
            ],
            5 => [
                'unit'   => ['PB'],
                'factor' => 1000
            ],
            6 => [
                'unit'   => ['EiB'],
                'factor' => 1000
            ]
        ];
        foreach ($data as $index => $dataUnit) {
            if (in_array($this->unit, $dataUnit['unit'], true)) {
                return [
                    'units' => $data,
                    'index' => $index
                ];
            }
        }

        // is data rate?
        $dataRates = [
            0 => [
                'unit'   => ['B/s'],
                'factor' => 1000
            ],
            1 => [
                'unit'   => ['KB/s'],
                'factor' => 1000
            ],
            2 => [
                'unit'   => ['MB/s'],
                'factor' => 1000
            ],
            3 => [
                'unit'   => ['GB/s'],
                'factor' => 1000
            ],
            4 => [
                'unit'   => ['TB/s'],
                'factor' => 1000
            ],
            5 => [
                'unit'   => ['EB/s'],
                'factor' => 1000
            ],
            6 => [
                'unit'   => ['EiB'],
                'factor' => 1000
            ]
        ];
        foreach ($dataRates as $index => $dataRate) {
            if (in_array($this->unit, $dataRate['unit'], true)) {
                return [
                    'units' => $dataRates,
                    'index' => $index
                ];
            }
        }

        // is data rate?
        $dataRates = [
            0 => [
                'unit'   => ['B/s'],
                'factor' => 8
            ],
            1 => [
                'unit'   => ['Bit/s', 'bit/s', 'bps'],
                'factor' => 1000
            ],
            2 => [
                'unit'   => ['kbit/s', 'kbits', 'kbps'],
                'factor' => 1000
            ],
            3 => [
                'unit'   => ['Mbit/s', 'mbit/s', 'mbits', 'Mbps'],
                'factor' => 1000
            ],
            4 => [
                'unit'   => ['Gbit/s', 'gbit/s', 'gbits', 'Gbps'],
                'factor' => 1000
            ],
            5 => [
                'unit'   => ['Tbit/s', 'tbit/s', 'tbits', 'Tbps'],
                'factor' => 1000
            ]
        ];
        foreach ($dataRates as $index => $dataRate) {
            if (in_array($this->unit, $dataRate['unit'], true)) {
                return [
                    'units' => $dataRates,
                    'index' => $index
                ];
            }
        }

        // is herz ?
        $herzRates = [
            0 => [
                'unit'   => ['Hz', 'hz'],
                'factor' => 1000
            ],
            1 => [
                'unit'   => ['khz'],
                'factor' => 1000
            ],
            2 => [
                'unit'   => ['Mhz'],
                'factor' => 1000
            ],
            3 => [
                'unit'   => ['Ghz'],
                'factor' => 1000
            ],
            4 => [
                'unit'   => ['Thz'],
                'factor' => 1000
            ]
        ];
        foreach ($herzRates as $index => $herzRate) {
            if (in_array($this->unit, $herzRate['unit'], true)) {
                return [
                    'units' => $herzRates,
                    'index' => $index
                ];
            }
        }

        // SI Units
        $unitsToGenerate = [
            'ops',  // Operations per second
            'iops', // IO operations per second
            'rps',  // Reads per per second
            'wps',  // Writes per second

            'W',       // Watt
            'V',       // Volt
            'volts',   // Prometheus
            'A',       // Amper
            'amperes', // Prometheus
            'C',       // Celsius
            '°C',      // Celsius,
            'celsius', // Prometheus
            'F',       // Farenheit
            '°F',      // Farenheit
            'K',       // Kelvin
            'J',       // Joul
            'joules'   // Prometheus
        ];

        foreach ($unitsToGenerate as $unit) {
            $siUnits = [];
            foreach (['n', 'µ', 'm', '', 'k', 'M', 'G'] as $index => $si) {
                $siUnits[$index] = [
                    'unit'   => [$si . $unit],
                    'factor' => 1000
                ];
            }

            foreach ($siUnits as $index => $siUnit) {
                if (in_array($this->unit, $siUnit['unit'], true)) {
                    return [
                        'units' => $siUnits,
                        'index' => $index
                    ];
                }
            }
        }

        return false;
    }


}

