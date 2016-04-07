<?php

namespace MyPDO\Classes;

use MyPDO\Interfaces\DBConnectionInterface;
use MyPDO\Interfaces\DBQueryInterface;
use PDO;

class DBQuery implements DBQueryInterface
{
    private $DBConnection = null;
    private $lastQueryTime = 0;
    private $TimerStop = 0;
    private $TimerStart = 0;


    /**
     * Create new instance DBQuery.
     *
     * @param DBConnectionInterface $DBConnection
     */
    public function __construct(DBConnectionInterface $DBConnection)
    {
        $this->DBConnection = $DBConnection;
    }

    /**
     * Returns the DBConnection instance.
     *
     * @return DBConnectionInterface
     */
    public function getDBConnection()
    {
        return $this->DBConnection;
    }

    /**
     * Change DBConnection.
     *
     * @param DBConnectionInterface $DBConnection
     *
     * @return void
     */
    public function setDBConnection(DBConnectionInterface $DBConnection)
    {
        $this->DBConnection = $DBConnection;
    }

    /**
     * Executes the SQL statement and returns query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed if successful, returns a PDOStatement on error false
     */
    public function query($query, $params = null)
    {
        $statement = $this->getStandartStatement($query, $params);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->stopQuery();
        return $res;
    }

    /**
     * Executes the SQL statement and returns all rows of a result set as an associative array
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryAll($query, array $params = null)
    {
        $statement = $this->getStandartStatement($query, $params);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->stopQuery();
        return $res;

    }

    /**
     * Executes the SQL statement returns the first row of the query result
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryRow($query, array $params = null)
    {
        $statement = $this->getStandartStatement($query, $params);
        $res = $statement->fetch(PDO::FETCH_ASSOC);
        $this->stopQuery();
        return $res;
    }

    /**
     * Executes the SQL statement and returns the first column of the query result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return array
     */
    public function queryColumn($query, array $params = null)
    {
        $statement = $this->getStandartStatement($query, $params);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->stopQuery();
        return $res;
    }

    /**
     * Executes the SQL statement and returns the first field of the first row of the result.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return mixed  column value
     */
    public function queryScalar($query, array $params = null)
    {
        $statement = $this->getStandartStatement($query, $params);
        $res = $statement->fetch(PDO::FETCH_NUM)[0];
        $this->stopQuery();
        return $res;
    }

    /**
     * Executes the SQL statement.
     * This method is meant only for executing non-query SQL statement.
     * No result set will be returned.
     *
     * @param string $query sql query
     * @param array $params input parameters (name=>value) for the SQL execution
     *
     * @return integer number of rows affected by the execution.
     */
    public function execute($query, array $params = null)
    {
        $statement = $this->getStandartStatement($query, $params);
        $res = $statement->rowCount(PDO::FETCH_ASSOC);
        $this->stopQuery();
        return $res;
    }

    /**
     * Returns the last query execution time in seconds
     *
     * @return float query time in seconds
     */
    public function getLastQueryTime()
    {
        return $this->lastQueryTime;
    }

    /**
     * @param array $params
     * @param $statement
     */
    private function bindParam($statement, array $params = null)
    {
        if (!is_null($params)) {
            foreach ($params as $key => $value) {
                $key = ':'.$key;
                if (is_numeric($value)) {
                    $statement->bindValue($key, $value, PDO::PARAM_INT);
                }

                if (is_string($value)){
                    $statement->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
        }

        return $statement;
    }

    private function startQuery()
    {
        $this->TimerStart = microtime(true);
        $this->lastQueryTime = 0;
    }

    private function stopQuery()
    {
        $this->TimerStop = microtime(true);
        $this->lastQueryTime = $this->TimerStop - $this->TimerStart;
    }

    /**
     * @param $query
     * @param $params
     * @return mixed
     */
    private function getStandartStatement($query, $params)
    {
        $this->startQuery();
        $db = $this->DBConnection->getPdoInstance();
        $statement = $db->prepare($query);
        $statement = $this->bindParam($statement, $params);
        $statement->execute();
        return $statement;
    }

}