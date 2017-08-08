<?php

namespace Core;

/**
 * Class Model
 * @package Core
 * @author Pavel Spichak
 */
class Model
{
    protected $tableName;
    protected $db;


    function __construct()
    {
        $this->db = App::getDatabase();
    }


    public function getById(int $id, $fields = '*')
    {
        $fieldNames = $fields;
        if (is_array($fields) && count($fields)) {
            $fieldNames = implode($fields, ',');
        }

        $sql = "
            SELECT 
                {$fieldNames}
            FROM
                {$this->tableName}
            WHERE
                id = {$id}
        ";

        $result = $this->db->query($sql);

        return (isset($result[0])) ? ((count($fields) > 1 || $fields == '*') ? $result[0] : $result[0][$fieldNames]) : [];
    }


    public function delete(int $id)
    {
        $sql = "
            DELETE FROM
                {$this->tableName}
            WHERE
                id = {$id}
        ";

        return $this->db->query($sql);
    }
}