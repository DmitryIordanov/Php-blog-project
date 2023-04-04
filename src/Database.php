<?php

namespace Blog;

use InvalidArgumentException;
use PDO;
use PDOException;

class Database {
    private PDO $connection;

    public function __construct(PDO $connection){
        try {
            $this->connection = $connection;
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $extension){
            throw new \InvalidArgumentException($extension->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}