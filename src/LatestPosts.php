<?php

namespace Blog;

use PDO;

class LatestPosts{

    /**
     * @var Database
     */
	private Database $database;

    /**
     * @param Database $database
     */
	public function __construct(Database $database){
		$this->database = $database;
	}

    /**
     * @param int $limit
     * @return array|null
     */
	public function getPostNum(int $limit): ?array{
		$statement = $this->database->getConnection()->prepare('SELECT * FROM post ORDER BY published_date DESC LIMIT :limit');
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
		$statement->execute();

		return $statement->fetchAll();
	}
}