<?php


namespace FireStorm;

abstract class AbstractRepository
{
    protected static \PDO $db;


    public function getDb(): \PDO
    {
        self::$db = new \PDO('mysql:host=127.0.0.1;dbname=blog;', 'root', '');
        self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);

        return self::$db;
    }

    /**
     * @param string $sql
     * @param array $fields
     */
    public function create(string $sql, array $fields)
    {
        $query = $this->getDb()->prepare($sql);
        return $query->execute($fields);
    }
}
