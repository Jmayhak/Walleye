<?php
namespace Walleye;

/**
 * Test class for Config.
 * @group Walleye
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    public function testGetRoutesIsArray()
    {
        $routes = Config::getRoutes();
        $this->assertTrue(is_array($routes));
    }

    /**
     * @todo Implement testGetRoutes().
     */
    public function testGetRoutesControllersExist()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
