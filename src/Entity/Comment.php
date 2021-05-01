<?php


namespace App\Entity;

class Comment
{
    private int $id;
    private int $post_id;
    private string $status;
    private string $content;
    private string $created_at;
    private ?string $modify_at='';

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    /**
     * @param array $data
     */
    private function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getPost_id(): int
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     * @return \App\Entity\Comment
     */
    public function setPost_id(int $post_id): Comment
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreated_At(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreated_At(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string|null
     */
    public function getModify_At(): ?string
    {
        return $this->modify_at;
    }

    /**
     * @param string|null $modify_at
     */
    public function setModify_At(?string $modify_at): void
    {
        $this->modify_at = $modify_at;
    }
}
