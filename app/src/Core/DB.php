<?php

namespace Core;

use Exception;
use mysqli;

/**
 * Class DB
 * @package Core
 * @author Pavel Spichak
 */
class DB
{

    protected $connection;


    function __construct(string $host, string $name, string $password, string $database)
    {
        $this->connection = new mysqli($host, $name, $password, $database);

        if (mysqli_connect_error()) {
            throw new Exception("Couldn't connect to database!");
        }
    }


    public function query(string $sql)
    {
        if (!$this->connection) {
            throw new Exception('Connection to database not established!');
        }

        $queryResult = $this->connection->query($sql);

        if (is_bool($queryResult)) {
            return $queryResult;
        }

        if (mysqli_error($this->connection)) {
            throw new Exception(mysqli_errno($this->connection));
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($queryResult)) {
            $data[] = $row;
        }

        return $data;
    }


    public function escape(string $str): string
    {
        return $this->connection->escape_string($str);
    }
}