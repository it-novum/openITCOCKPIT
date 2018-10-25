<?php

App::uses('Arrays', 'Lib');


class ArraysTest extends CakeTestCase {

    public function testEnsureArrayKeys() {
        $src = [
            'cheese' => 'milk',
            'pizza'  => [
                'cheese' => [
                    'milk' => [],
                ],
            ],
            'bacon'  => [],
        ];
        $target = [
            'cheese' => [
                'fat' => [],
            ],
            'pizza'  => [
                'cheese' => [
                    'fat' => '',
                ],
                'onions' => [
                    'red' => '',
                ],
            ],
            'bacon'  => [
                'egg' => [],
            ],
        ];

        $result = Arrays::ensureArrayKeys($src, $target);

        $this->assertEqual($result, [
            'cheese' => 'milk',
            'pizza'  => [
                'cheese' => [
                    'milk' => [],
                    'fat'  => '',
                ],
                'onions' => [
                    'red' => '',
                ],
            ],
            'bacon'  => [
                'egg' => [],
            ],
        ]);
    }
}
