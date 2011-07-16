<?php

namespace Walleye;

class TableTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {

    }

    protected function tearDown()
    {

    }

    public function testGenerate()
    {
        $data = array(
            array(
                'id' => 1,
                'name' => 'jonathan',
                'onclick' => '/'
            )
        );

        $options = array(
            'column_names' => array(
                'id', 'name'
            ),
            'id' => 'mytable'
        );
        
        $html = Table::generate($data, $options);
        $this->assertEquals('<table id="mytable"><thead><tr><th>id</th><th>name</th></tr></thead><tfoot></tfoot><tbody><tr onclick="document.location=\'/\'"><td>1</td><td>jonathan</td></tr></tbody></table>', $html);
    }

}
