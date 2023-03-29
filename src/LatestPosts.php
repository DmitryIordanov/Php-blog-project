<?php

namespace Blog;

use PDO;

class LatestPosts{

	private PDO $connection;

	public function __construct(PDO $connection){
		$this->connection = $connection;
	}

	public function getPostNum(int $limit): ?array{
		$statement = $this->connection->prepare('SELECT * FROM post ORDER BY published_date DESC LIMIT ' . $limit);
		$statement->execute();

		return $statement->fetchAll();
	}
}