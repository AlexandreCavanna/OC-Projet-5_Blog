<?php


namespace FireStorm;

abstract class AbstractRepository
{
    protected static \PDO $db;


    public function getDb(): \PDO
    {
        $confDb = require(__DIR__ . '/../Config/database.php');

        self::$db = new \PDO(
            sprintf("%s%s", $confDb['dsn'], $confDb['dbname']),
            $confDb['user'],
            $confDb['password']
        );

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
