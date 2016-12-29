<?php

App::uses('CakePdf', 'CakePdf.Pdf');
App::uses('AbstractPdfEngine', 'CakePdf.Pdf/Engine');
App::uses('Controller', 'Controller');

/**
 * Dummy engine
 */
class PdfTest2Engine extends AbstractPdfEngine
{

    public function output()
    {
        return $this->_Pdf->html();
    }
}

/**
 * CakePdfTest class
 * @package       CakePdf.Test.Case.Pdf
 */
class CakePdfTest extends CakeTestCase
{

    /**
     *
     */
    public static function provider()
    {
        return [[[
            'engine' => 'PdfTest2',
            'margin' => [
                'bottom' => 15,
                'left'   => 50,
                'right'  => 30,
                'top'    => 45,
            ]]]];
    }

    /**
     * Tests exception to be thrown for a non existing engine
     * @expectedException CakeException
     */
    public function testNonExistingEngineException()
    {
        $config = ['engine' => 'NonExistingEngine'];

        $pdf = new CakePdf($config);
    }

    /**
     * testOutput
     * @dataProvider provider
     */
    public function testOutput($config)
    {
        $pdf = new CakePdf($config);
        $path = CakePlugin::path('CakePdf').'Test'.DS.'test_app'.DS.'View'.DS;
        App::build(['View' => $path]);
        $pdf->viewVars(['data' => 'testing']);
        $pdf->template('testing', 'pdf');
        $result = $pdf->output();
        $expected = 'Data: testing';
        $this->assertEquals($expected, $result);

        $html = '<h2>Title</h2>';
        $result = $pdf->output($html);
        $this->assertEquals($html, $result);
    }

    /**
     * testPluginOutput
     * @dataProvider provider
     */
    public function testPluginOutput($config)
    {
        $pdf = new CakePdf($config);
        $path = CakePlugin::path('CakePdf').'Test'.DS.'test_app'.DS.'Plugin'.DS;
        App::build(['Plugin' => $path]);
        CakePlugin::load('MyPlugin');
        $pdf->viewVars(['data' => 'testing']);
        $pdf->template('MyPlugin.testing', 'MyPlugin.pdf');
        $pdf->helpers('MyPlugin.MyTest');
        $result = $pdf->output();
        $expected = 'MyPlugin Layout Data: testing';
        $this->assertEquals($expected, $result);

        $pdf->template('MyPlugin.testing', 'MyPlugin.default');
        $result = $pdf->output();
        $lines = [
            '<h2>Rendered with default layout from MyPlugin</h2>',
            'MyPlugin view Data: testing',
            'MyPlugin Helper Test: successful',
        ];
        foreach ($lines as $line) {
            $this->assertTrue(strpos($result, $line) !== false);
        }
    }

    /**
     * Tests that engine returns the proper object
     * @dataProvider provider
     */
    public function testEngine($config)
    {
        $pdf = new CakePdf($config);
        $engine = $pdf->engine();
        $this->assertEqual('PdfTest2Engine', get_class($engine));
    }

    /**
     * @dataProvider provider
     */
    public function testMargin($config)
    {
        $pdf = new CakePdf($config);
        $pdf->margin(15, 20, 25, 30);
        $expected = [
            'bottom' => 15,
            'left'   => 20,
            'right'  => 25,
            'top'    => 30,
        ];
        $this->assertEqual($expected, $pdf->margin());

        $pdf = new CakePdf($config);
        $pdf->margin(75);
        $expected = [
            'bottom' => 75,
            'left'   => 75,
            'right'  => 75,
            'top'    => 75,
        ];
        $this->assertEqual($expected, $pdf->margin());

        $pdf = new CakePdf($config);
        $pdf->margin(20, 50);
        $expected = [
            'bottom' => 20,
            'left'   => 50,
            'right'  => 50,
            'top'    => 20,
        ];
        $this->assertEqual($expected, $pdf->margin());

        $pdf = new CakePdf($config);
        $pdf->margin(['left' => 120, 'right' => 30, 'top' => 34, 'bottom' => 15]);
        $expected = [
            'bottom' => 15,
            'left'   => 120,
            'right'  => 30,
            'top'    => 34,
        ];
        $this->assertEqual($expected, $pdf->margin());

        $pdf = new CakePdf($config);
        $expected = [
            'bottom' => 15,
            'left'   => 50,
            'right'  => 30,
            'top'    => 45,
        ];
        $this->assertEqual($expected, $pdf->margin());
    }
}
