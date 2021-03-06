<?php

namespace ActivatorAdmin\Test\PHPunit;

require_once __DIR__.'/../../lib/autoload.php';

use ActivatorAdmin\Lib\ConfigHelper;
use ActivatorAdmin\Lib\MySQL;

/**
 * Test the MySQL class.
 */
class MySQLTest extends \PHPUnit\Framework\TestCase
{
    protected $db;
    protected $dbConfig;

    /**
     * Connects to a MySQL database and gets an instance of MySQL.
     * MySQL connection credentials are pulled using class ConfigHelper.
     * Setup a test table before running test cases.
     */
    protected function setUp()
    {
        // Get database connection.
        $objConfigHelper = new ConfigHelper();
        $this->dbConfig = $objConfigHelper->get('mysql');
        $this->dbConfig['isTest'] = true;
        $this->db = MySQL::getInstance($this->dbConfig);

        // Create pseudo table for testing.
        $mysqli = $this->db->getConnection();
        $sql = 'CREATE TABLE IF NOT EXISTS '.$this->dbConfig['table'].'_test ';
        $sql .= '(id INT(11) NOT NULL AUTO_INCREMENT, isactive TINYINT(4), name VARCHAR(255), image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))';
        $mysqli->query($sql);
    }

    /**
     * Drop test table after running testcases.
     */
    protected function tearDown()
    {
        $this->db = MySQL::getInstance($this->dbConfig);

        // Create pseudo table for testing.
        $mysqli = $this->db->getConnection();
        $sql = 'DROP TABLE '.$this->dbConfig['table'].'_test';
        $mysqli->query($sql);
    }

    /**
     * Test the if the singleton function getInstance() returns MySQL.
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf('\ActivatorAdmin\Lib\MySQL', $this->db);
    }

    /**
     * Test getting a database connection.
     */
    public function testGetConnection()
    {
        $mysqli = $this->db->getConnection();
        $this->assertInstanceOf('mysqli', $mysqli);
    }

    /**
     * Test select function.
     * First inserting a record into the test table and then testing the size of the test table.
     */
    public function testSelect()
    {
        $mysqli = $this->db->getConnection();

        // Insert test record
        $sqlInsert = 'INSERT INTO '.$this->dbConfig['table']."_test (isactive, name) VALUES (1, 'Test Record 1')";
        $mysqli->query($sqlInsert);

        // Test select function
        $result = $this->db->select();
        $this->assertGreaterThan(0, sizeof($result));
    }

    /**
     * Test insert function.
     * Insert a test record and check the insert_id that is returned by the insert function.
     */
    public function testInsert()
    {
        $insert_id = $this->db->insert(array('isactive' => 0, 'name' => 'Test Record 2'));
        $this->assertGreaterThan(0, $insert_id);
    }

    /**
     * Test update function.
     * Insert a test record, update that record, and select it to check to updated name value.
     */
    public function testUpdate()
    {
        // Insert test record.
        $insert_id = $this->db->insert(array('isactive' => 0, 'name' => 'Test Record 3'));

        // Update test record.
        $this->db->update(array('name' => 'New Test Record 3'), 'id', $insert_id);

        // Get the updated record.
        $result = $this->db->select('*', 'id', $insert_id);

        // Check that test record has new name.
        $this->assertEquals($result['name'], 'New Test Record 3');
    }

    /**
     * Test delete function.
     * Insert a test record, delete it, and try to getting using the select function. Should return 0.
     */
    public function testDelete()
    {
        // Insert test record.
        $insert_id = $this->db->insert(array('isactive' => 0, 'name' => 'Test Record 4'));

        // Delete record.
        $this->db->delete('id', $insert_id);

        // Try to get the deleted record.
        $result = $this->db->select('*', 'id', $insert_id);

        // Check that no records were returned upon select.
        $this->assertEquals(0, sizeof($result));
    }

    /**
     * Test search function.
     * Search for records in std table with name='Item'.
     */
    public function testSearch()
    {
        $results = $this->db->search('name', 'Item');

        $this->assertTrue(is_array($results));
    }
}
