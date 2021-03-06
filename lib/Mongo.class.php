<?php

namespace ActivatorAdmin\Lib;

/**
 * Mongo is used for setting up a connection to a MongoDB.
 * Mongo is a singleton.
 */
class Mongo implements iDatabase
{
    private static $instance;
    private $mongoCollection;

    /**
     * Connect to a MongoDB with the credentials from the $config['mongo'] array.
     *
     * @param array $config is the mongo entry in the configuration array that is setup in class ConfigHelper
     */
    private function __construct(array $config)
    {
        $username = $config['user'];
        $password = $config['pass'];

        $strAuthentication = 'mongodb://';
        if ($username !== '' && $password !== '') {
            $strAuthentication .= $username.':'.$password.'@';
        }
        $strAuthentication .= $config['host'];

        $client = new \MongoClient($strAuthentication);
        $db = $client->{$config['name']};
        $this->mongoCollection = $db->{$config['collection']};
    }

    /**
     * getInstance returns an instance of Mongo (Singleton Pattern).
     *
     * @param array $config is the mongo entry in the configuration array that is setup in class ConfigHelper
     *
     * @return object Mongo
     */
    public static function getInstance(array $config)
    {
        if (static::$instance === null) {
            static::$instance = new static($config);
        }

        return static::$instance;
    }

    /**
     * Execute a select/find query defined using the parameters.
     *
     * @param string $columns     are the names of the columns to return. Not required. Default * (all columns)
     * @param string $whereColumn is the field name for the query for filtering the records. Not required
     * @param string $whereValue  is the field value for the query for filtering the records. Not required
     * @param string $orderBy     is the column name and direction for sorting records. Not required
     * @param int    $limit       is the number of records to return. Not required
     */
    public function select($columns = '*', $whereColumn = '', $whereValue = '', $orderBy = '', $limit = 0)
    {
        $results = array();

        $query = array();
        if ($whereColumn !== '' && $whereValue !== '') {
            if ($whereColumn === 'id') {
                $whereColumn = $this->fixWhereColumnForId();
                $whereValue = $this->fixWhereValueForId($whereValue);
            }

            if (strpos($columns, 'COUNT(*)') !== false) {
                $whereValue = (string) $whereValue;
            }

            $query[$whereColumn] = $whereValue;
        }

        $cursor = $this->mongoCollection->find($query);

        if ($orderBy) {
            $cursor->sort(array($orderBy => 1));
        }

        if ($limit) {
            $cursor->limit($limit);
        }

        foreach ($cursor as $document) {
            $document['id'] = (string) $document['_id'];
            $results[] = $document;
        }

        if (count($results) === 1) {
            $results = $results[0];
        }

        if (strpos($columns, 'COUNT(*)') !== false) {
            $arrColumnsSplit = explode(' ', $columns);
            if (isset($arrColumnsSplit[2]) && $arrColumnsSplit[2] !== '') {
                return array($arrColumnsSplit[2] => count($results));
            }
        }

        return $results;
    }

    /**
     * Execute an insert query defined using the parameter - which is the actual document to be inserted.
     *
     * @param array $document is array of data to insert. Array key is field name
     */
    public function insert(array $document)
    {
        if (is_array($document) && !empty($document)) {
            $this->mongoCollection->insert($document);
        }
    }

    /**
     * Execute an update query defined using the parameters.
     *
     * @param array  $data        is an array of fields to update - array key = column_name
     * @param string $whereColumn is the column name for the where clause for specifying what to records to update
     * @param string $whereValue  is the value for the where clause for specifying what to records to update
     */
    public function update(array $data, string $whereColumn = '', string $whereValue = '')
    {
        if ($whereColumn === 'id') {
            $whereColumn = $this->fixWhereColumnForId();
            $whereValue = $this->fixWhereValueForId($whereValue);
        }

        $criteria = array(
            $whereColumn => $whereValue,
        );

        $fields = array();
        foreach ($data as $field_name => $value) {
            $fields[$field_name] = $value;
        }
        $set = array('$set' => $fields);

        $this->mongoCollection->update($criteria, $set);
    }

    /**
     * Execute a delete/remove query defined using the parameters.
     *
     * @param string $whereColumn is the field name for the query for specifying what to records to delete
     * @param string $whereValue  is the field value for the query for specifying what to records to delete
     */
    public function delete(string $whereColumn, string $whereValue)
    {
        if ($whereColumn === 'id') {
            $whereColumn = $this->fixWhereColumnForId();
            $whereValue = $this->fixWhereValueForId($whereValue);
        }

        $this->mongoCollection->remove(array(
            $whereColumn => $whereValue,
        ));
    }

    /**
     * Helper function that returns the name of the id field.
     */
    private function fixWhereColumnForId()
    {
        return '_id';
    }

    /**
     * Helper function that retuns the value as MongoId.
     *
     * @param string $value is the id value that is to be converted to MongoId
     */
    private function fixWhereValueForId($value)
    {
        return new \MongoId($value);
    }
}
