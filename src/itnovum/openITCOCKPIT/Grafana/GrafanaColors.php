<?php


namespace itnovum\openITCOCKPIT\Grafana;


class GrafanaColors {

    /**
     * @var array
     */
    private $colors;

    public function __construct() {
        $this->colors = [
            'green' => [
                'name'     => __('Green'),
                'main'     => 'rgb(86, 166, 75)',
                'children' => [
                    'rgb(25, 115, 14)',
                    'rgb(55, 135, 45)',
                    'rgb(115, 191, 105)',
                    'rgb(150, 217, 141)'
                ],
            ],

            'yellow' => [
                'name'     => __('Yellow'),
                'main'     => 'rgb(242, 204, 12)',
                'children' => [
                    'rgb(204, 157, 0)',
                    'rgb(224, 180, 0)',
                    'rgb(250, 222, 42)',
                    'rgb(255, 238, 82)'
                ],
            ],

            'red' => [
                'name'     => __('Red'),
                'main'     => 'rgb(224, 47, 68)',
                'children' => [
                    'rgb(173, 3, 23)',
                    'rgb(196, 22, 42)',
                    'rgb(242, 73, 92)',
                    'rgb(255, 115, 131)'
                ],
            ],

            'blue' => [
                'name'     => __('Blue'),
                'main'     => 'rgb(50, 116, 217)',
                'children' => [
                    'rgb(18, 80, 176)',
                    'rgb(31, 96, 196)',
                    'rgb(87, 148, 242)',
                    'rgb(138, 184, 255)'
                ],
            ],

            'orange' => [
                'name'     => __('Orange'),
                'main'     => 'rgb(255, 120, 10)',
                'children' => [
                    'rgb(229, 84, 0)',
                    'rgb(250, 100, 0)',
                    'rgb(255, 152, 48)',
                    'rgb(225, 179, 87)'
                ],
            ],

            'purple' => [
                'name'     => __('Purple'),
                'main'     => 'rgb(163, 82, 204)',
                'children' => [
                    'rgb(124, 46, 163)',
                    'rgb(143, 59, 184)',
                    'rgb(184, 119, 217)',
                    'rgb(202, 149, 229)'
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getColors() {
        return $this->colors;
    }

}
