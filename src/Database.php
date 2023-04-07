<?php

namespace Blog;

use PDO;

class Database {
    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * @param PDO $connection
     */
    public function __construct(PDO $connection){
        $this->connection = $connection;
    }

    /**
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
}