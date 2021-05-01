<?php


namespace App\Repository;

use App\Entity\Comment;
use FireStorm\AbstractRepository;
use PDO;

class CommentRepository extends AbstractRepository
{
    /**
     * @param string $content
     * @param int $postId
     */
    public function createComment(string $content, int $postId)
    {
        $sql = 'INSERT INTO comment(content, postId) VALUES(:content, :postId)';

        $field = [
            'content' => $content,
            'postId' => $postId,
        ];

        $this->create($sql, $field);
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getCommentsByPost($id): ?array
    {
        $db = $this->getDb();
        $query =  $db->prepare('SELECT * FROM comment WHERE postId = ?');
        $query->execute([$id]);

        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            $var[] = new Comment($data);
        }

        return $var ?? null;
    }

    /**
     * @param $id
     * @return \App\Entity\Comment
     */
    public function getComment($id): Comment
    {
        $db = $this->getDb();
        $query =  $db->prepare('SELECT * FROM comment WHERE id = ?');
        $query->execute([$id]);
        $data = $query->fetch(PDO::FETCH_ASSOC);

        return new Comment($data);
    }

    public function getComments(): ?array
    {
        $db = $this->getDb();
        $query =  $db->prepare('SELECT * FROM comment');
        $query->execute();

        while ($data = $query->fetch(PDO::FETCH_ASSOC)) {
            $var[] = new Comment($data);
        }
        return $var ?? null;
    }

    /**
     * @param $id
     */
    public function approveComment($id)
    {
        $db = $this->getDb();
        $query =  $db->prepare("UPDATE comment SET status = 'Approuvé' WHERE id = ?");
        $query->execute([$id]);
    }

    /**
     * @param $id
     */
    public function reportComment($id)
    {
        $db = $this->getDb();
        $query =  $db->prepare("UPDATE comment SET status = 'Signalé' WHERE id = ?");
        $query->execute([$id]);
    }

    /**
     * @param $id
     */
    public function deleteComment($id)
    {
        $db = $this->getDb();
        $query =  $db->prepare("DELETE FROM comment WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * @return mixed
     */
    public function countNeedReview()
    {
        $db = $this->getDb();
        $sql = "SELECT postId,
                    (SELECT COUNT(status) from comment WHERE status = 'Approuvé')+
                    (SELECT COUNT(status) from comment WHERE status = \"Besoin d'un examen\")  
                AS NeedReview
                FROM comment
                GROUP BY postId";

        $query =  $db->prepare($sql);
        $query->execute();

        return $query->fetch();
    }
}
