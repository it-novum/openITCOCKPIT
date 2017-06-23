<?php
/**
 * Licensed to CRATE Technology GmbH("Crate") under one or more contributor
 * license agreements.  See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.  Crate licenses
 * this file to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.  You may
 * obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * However, if you have executed another commercial license agreement
 * with Crate these terms will supersede the license and you may use the
 * software solely pursuant to the terms of the relevant commercial agreement.
 */

namespace Crate\Test\DBAL\Functional;

use Crate\DBAL\Platforms\CratePlatform;
use Crate\DBAL\Types\TimestampType;
use Crate\Test\DBAL\DBALFunctionalTestCase;
use Crate\DBAL\Types\ArrayType;
use Crate\DBAL\Types\MapType;
use Doctrine\DBAL\Types\Type;

class TypeConversionTestCase extends \PHPUnit_Framework_TestCase {

    private $platform;

    public function setUp()
    {
        $this->platform = new CratePlatform();
    }

    public function testTimestampType()
    {
        $input = new \DateTime("2014-10-21 15:23:38");

        // datetimetz
        $type = Type::getType(Type::DATETIMETZ);
        $expected = $input->format('Y-m-d\TH:i:sO');
        $output = $type->convertToDatabaseValue($input, $this->platform);
        $this->assertEquals($output, $expected);
        $inputRestored = $type->convertToPHPValue($output, $this->platform);
        $this->assertEquals($inputRestored, $input);
        $inputRestored = $type->convertToPHPValue($input, $this->platform);
        $this->assertEquals($inputRestored, $input);

        // datetime
        $type = Type::getType(Type::DATETIME);
        $expected = $input->format('Y-m-d\TH:i:s');
        $output = $type->convertToDatabaseValue($input, $this->platform);
        $this->assertEquals($output, $expected);
        $inputRestored = $type->convertToPHPValue($output, $this->platform);
        $this->assertEquals($inputRestored, $input);
        $inputRestored = $type->convertToPHPValue($input, $this->platform);
        $this->assertEquals($inputRestored, $input);

        // date
        $type = Type::getType(Type::DATE);
        $expected = $input->format('Y-m-d\TH:i:s');
        $output = $type->convertToDatabaseValue($input, $this->platform);
        $this->assertEquals($output, $expected);
        $inputRestored = $type->convertToPHPValue($output, $this->platform);
        $this->assertEquals($inputRestored, $input);
        $inputRestored = $type->convertToPHPValue($input, $this->platform);
        $this->assertEquals($inputRestored, $input);

        // time
        $type = Type::getType(Type::TIME);
        $expected = $input->format('Y-m-d\TH:i:s');
        $output = $type->convertToDatabaseValue($input, $this->platform);
        $this->assertEquals($output, $expected);
        $inputRestored = $type->convertToPHPValue($output, $this->platform);
        $this->assertEquals($inputRestored, $input);
        $inputRestored = $type->convertToPHPValue($input, $this->platform);
        $this->assertEquals($inputRestored, $input);

        // timestamp
        $type = Type::getType(TimestampType::NAME);
        $expected = $input->format('U')*TimestampType::S_TO_MS;
        $output = $type->convertToDatabaseValue($input, $this->platform);
        $this->assertEquals($output, $expected);
        $inputRestored = $type->convertToPHPValue($output, $this->platform);
        $this->assertEquals($inputRestored, $input);
        $inputRestored = $type->convertToPHPValue($input, $this->platform);
        $this->assertEquals($inputRestored, $input);
    }

    public function testTimestampTypeNull()
    {
        $types = array(Type::getType(Type::DATETIMETZ),
            Type::getType(Type::DATETIME),
            Type::getType(Type::DATE),
            Type::getType(Type::TIME),
            Type::getType(TimestampType::NAME)
        );
        foreach ($types as $type) {
            // to DB value
            $value = $type->convertToDatabaseValue(null, $this->platform);
            $this->assertEquals($value, null);

            // to PHP value
            $value = $type->convertToPHPValue(null, $this->platform);
            $this->assertEquals($value, null);
        }
    }

    public function testMapType()
    {
        $type = Type::getType(MapType::NAME);

        // to DB value
        $output = $type->convertToDatabaseValue(array('foo'=>'bar'), $this->platform);
        $this->assertEquals($output, array('foo'=>'bar'));

        $output = $type->convertToDatabaseValue(array(), $this->platform);
        $this->assertEquals($output, array());
    }

    public function testMapTypeNullValue()
    {
        $type = Type::getType(MapType::NAME);

        // to DB value
        $output = $type->convertToDatabaseValue(null, $this->platform);
        $this->assertEquals($output, null);
    }

    public function testMapTypeInvalid()
    {
        $type = Type::getType(MapType::NAME);

        // to DB value
        $notAMap = array('foo', 'bar');
        $output = $type->convertToDatabaseValue($notAMap, $this->platform);
        $this->assertEquals($output, null);

    }

    public function testArrayType()
    {
        $type = Type::getType(ArrayType::NAME);

        // to DB value
        $output = $type->convertToDatabaseValue(array('foo', 'bar'), $this->platform);
        $this->assertEquals($output, array('foo', 'bar'));

        $output = $type->convertToDatabaseValue(array(), $this->platform);
        $this->assertEquals($output, array());
    }

    public function testArrayTypeNullValue()
    {
        $type = Type::getType(ArrayType::NAME);

        // to DB value
        $output = $type->convertToDatabaseValue(null, $this->platform);
        $this->assertEquals($output, null);
    }

    public function testArrayTypeInvalid()
    {
        $type = Type::getType(ArrayType::NAME);

        // to DB value
        $notAnArray = array('foo'=>'bar');
        $output = $type->convertToDatabaseValue($notAnArray, $this->platform);
        $this->assertEquals($output, null);
    }

}
