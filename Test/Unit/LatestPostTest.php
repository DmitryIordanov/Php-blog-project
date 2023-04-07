<?php

namespace Blog\Test\Unit;
use Blog\Database;
use Blog\LatestPosts;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LatestPostTest extends TestCase {
    /**
     * @var LatestPosts
     */
    private LatestPosts $object;
    /**
     * @var MockObject|Database|(Database&MockObject)
     */
    private MockObject $database;
    /**
     * @var MockObject|PDO|(PDO&MockObject)
     */
    private MockObject $pdo;
    /**
     * @var MockObject|PDOStatement|(PDOStatement&MockObject)
     */
    private MockObject $pdoStatement;

    protected function setUp(): void {
        $this->database = $this->createMock(Database::class);

        $this->pdo = $this->createMock(PDO::class);

        $this->database->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->pdo);

        $this->pdoStatement = $this->createMock(PDOStatement::class);

        $this->object = new LatestPosts($this->database);
    }

    public function testGetPostNumEmpty(): void {
        $limit = 0;
        $expectedResult = [];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute');

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResult);

        $result = $this->object->getPostNum($limit);
        $this->assertEmpty($result);
    }

    public function testGetPostNum(): void{
        $limit = 3;
        $expectedResult = [
            'title' => 'My Post',
            'author' => 'Iordanov Dmitry'
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM post ORDER BY published_date DESC LIMIT :limit'))
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute');

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expectedResult);

        $this->pdoStatement->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':limit'), $this->equalTo($limit), $this->equalTo(PDO::PARAM_INT));

        $result = $this->object->getPostNum($limit);
        $this->assertNotEmpty($result);
    }
}