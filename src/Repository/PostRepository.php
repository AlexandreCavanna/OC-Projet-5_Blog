<?php


namespace App\Repository;


use App\Entity\Post;
use FireStorm\AbstractRepository;
use PDO;
use PDOStatement;

class PostRepository extends AbstractRepository
{
    /**
     * @return array
     */
    public function getPosts(): array
    {
        $db = $this->getDb();
        $query =  $db->prepare('SELECT * FROM post ORDER BY created_at DESC');
        $query->execute();
        while($data = $query->fetch(PDO::FETCH_ASSOC))
        {
            $var[] = new Post($data);
        }
        return $var;
    }

    /**
     * @param $id
     * @return Post
     */
    public function getPost($id): Post
    {
        $db = $this->getDb();
        $query =  $db->prepare('SELECT id, title, chapo, content, author, created_at FROM post WHERE id = ?');
        $query->execute([$id]);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return new Post($data);
    }
}
