<?php


/**
 * This class runs all tests for the /Lib directory.
 */
class AllLibTestsTest extends PHPUnit_Framework_TestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All Lib class tests');
        $suite->addTestDirectory(OLD_APP . DS . 'Test/Case/Lib');

        return $suite;
    }
}
