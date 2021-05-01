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
        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
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
        $query =  $db->prepare('SELECT * FROM post WHERE id = ?');
        $query->execute([$id]);
        $data = $query->fetch(PDO::FETCH_ASSOC);
        return new Post($data);
    }

    /**
     * @param $id
     * @param null $title
     * @param $chapo
     * @param $author
     * @param $content
     * @return bool
     */
    public function updatePost($id, $title, $chapo, $author, $content): bool
    {
        $db = $this->getDb();
        $query =  $db->prepare('UPDATE post SET title = :title, chapo = :chapo, author = :author, content = :content, modify_at = NOW() WHERE id = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute([
            'title' => $title,
            'chapo' => $chapo,
            'author' => $author,
            'content' => $content
        ]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function deletePost($id): bool
    {
        $req = $this->getDb()->prepare('DELETE FROM post WHERE id = ?');
        return $req->execute([$id]);
    }
}
