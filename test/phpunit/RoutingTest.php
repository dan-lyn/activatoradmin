<?php
/**
 * PHPunit test for testing the routing in the Slim app.
 *
 */
namespace ActivatorAdmin\Test\PHPunit;

require_once(__DIR__ . '/../../lib/Slim/Slim.php');
require_once(__DIR__ . '/../../lib/ConfigHelper.class.php');

use \ActivatorAdmin\Lib\ConfigHelper;

\Slim\Slim::registerAutoloader();

class RoutingTest extends \PHPUnit_Framework_TestCase
{
    private $response;

    /**
     * Helper function for setting up a request on the Silm app.
     */
    protected function request($method, $path)
    {
        ob_start();

        $objConfigHelper = new ConfigHelper();
        $host = $objConfigHelper->get('url', 'host');

        \Slim\Environment::mock(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => $host
        ));

        require(__DIR__ . '/../../index.php');

        $this->response = $app->response();

        return ob_get_clean();
    }

    /**
     * Test the index page.
     * GET request method on path '/'.
     */
    public function testIndex()
    {
        $this->request('GET', '/');
        $this->assertEquals(302, $this->response->status());
    }

    /**
     * Test the 'items' route.
     * GET request on path '/items'.
     */
    public function testItems()
    {
        $this->request('GET', '/items');
        $this->assertEquals(302, $this->response->status());
    }

    /**
     * Test the 'item' route.
     * GET request on path '/item/1'. 1 is the ID of an item.
     */
    public function testGetItem()
    {
        $this->request('GET', '/item/1');
        $this->assertEquals(302, $this->response->status());
    }

    /**
     * Test the 'item' route: Update an item.
     * PUT request on path '/item/1'. 1 is the ID of an item.
     */
    public function testPutItem()
    {
        $this->request('PUT', '/item/1');
        $this->assertEquals(302, $this->response->status());
    }

    /**
     * Test the 'item' route: Delete an item.
     * DELETE request on path '/item/id'. id should be an integer/id of an item.
     */
    public function testDeleteItem()
    {
        $this->request('DELETE', '/item/id');
        $this->assertEquals(302, $this->response->status());
    }

}

