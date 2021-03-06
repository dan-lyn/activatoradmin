<?php

namespace ActivatorAdmin\Test\PHPunit;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../lib/autoload.php';

use ActivatorAdmin\Lib\ConfigHelper;

/**
 * PHPunit test for testing the routing in the Slim app.
 */
class RoutingTest extends \PHPUnit\Framework\TestCase
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

        \Slim\Http\Environment::mock(array(
            'REQUEST_METHOD' => $method,
            'PATH_INFO' => $path,
            'SERVER_NAME' => $host,
        ));

        require __DIR__.'/../../index.php';

        $container = $app->getContainer();
        $this->response = $container['response'];

        return ob_get_clean();
    }

    /**
     * Test the index page.
     * GET request method on path '/'.
     */
    public function testIndex()
    {
        $this->request('GET', '/');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'login' route.
     */
    public function testLogin()
    {
        $this->request('GET', '/login');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'logout' route.
     */
    public function testLogout()
    {
        $this->request('GET', '/logout');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'items' route.
     * GET request on path '/items'.
     */
    public function testItems()
    {
        $this->request('GET', '/items');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'item' route.
     * GET request on path '/item/1'. 1 is the ID of an item.
     */
    public function testGetItem()
    {
        $this->request('GET', '/item/1');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'item' route: Update an item.
     * PUT request on path '/item/1'. 1 is the ID of an item.
     */
    public function testPutItem()
    {
        $this->request('PUT', '/item/1');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'item' route: Delete an item.
     * DELETE request on path '/item/id'. id should be an integer/id of an item.
     */
    public function testDeleteItem()
    {
        $this->request('DELETE', '/item/1');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'search' route.
     */
    public function testSearch()
    {
        $this->request('GET', '/search/term');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'stats' route.
     */
    public function testStats()
    {
        $this->request('GET', '/stats');
        $this->assertEquals(200, $this->response->getStatusCode());
    }

    /**
     * Test the 'get-stats' route.
     */
    public function testGetStats()
    {
        $this->request('GET', '/get-stats');
        $this->assertEquals(200, $this->response->getStatusCode());
    }
}
