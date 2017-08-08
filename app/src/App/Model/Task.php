<?php

namespace App\Model;

use Core\Model;
use Exception;

class Task extends Model
{

    function __construct()
    {
        parent::__construct();

        $this->tableName = 'task';
    }

    public function create(array $data)
    {

        if (empty($data['title']) || empty($data['author']) || empty($data['email']) || empty($data['message'])) {
            throw new Exception('Fill required fields');
        }

        $title = $this->db->escape($data['title']);
        $author = $this->db->escape($data['author']);
        $email = $this->db->escape($data['email']);
        $message = $this->db->escape($data['message']);
        $image = (isset($data['image'])) ? $this->db->escape($data['image']) : '';

        $sql = "
            INSERT INTO
                task (title, author, email, message, image)
            VALUES (
                '{$title }', 
                '{$author}', 
                '{$email}', 
                '{$message}', 
                '{$image}'
            ); 
        ";

        return $this->db->query($sql);
    }


    public function update(int $id, array $data)
    {
        if (empty($data['message'])) {
            throw new Exception('Fill required fields');
        }


        $sql = "
            UPDATE 
                task
            SET
                message = '{$data['message']}',
                is_performed = {$data['is_performed']}
            WHERE 
                id = {$id}
        ";

        return $this->db->query($sql);
    }


    public function getList(string $orderField = 'title', string $orderDirection = 'asc',
                            int $start = 1, int $offset = 10): array
    {

        $sql = "
            SELECT 
                *
            FROM
                task
            ORDER BY 
                {$orderField} {$orderDirection}
            LIMIT 
                {$start}, {$offset}
        ";

        return $this->db->query($sql);
    }


    public function getTasksCount(): int
    {
        $sql = "
            SELECT 
                COUNT(*) AS count
            FROM
                task
        ";

        $result = $this->db->query($sql);

        return (isset($result[0]['count'])) ? $result[0]['count'] : 0;
    }
}