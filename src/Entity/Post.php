<?php


namespace App\Entity;

class Post
{
    private int $id;
    private string $title;
    private string $chapo;
    private string $author;
    private string $content;
    private string $created_At;
    private ?string $modify_At;

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
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getChapo(): string
    {
        return $this->chapo;
    }

    /**
     * @param string $chapo
     */
    public function setChapo(string $chapo): void
    {
        $this->chapo = $chapo;
    }

    /**
     * @return mixed
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCreated_At(): string
    {
        return $this->created_At;
    }

    /**
     * @param string $created_At
     */
    public function setCreated_At(string $created_At): void
    {
        $this->created_At = $created_At;
    }


    /**
     * @return string|null
     */
    public function getModify_At(): ?string
    {
        return $this->modify_At;
    }

    /**
     * @param string|null $modify_At
     */
    public function setModify_At(?string $modify_At): void
    {
        $this->modify_At = $modify_At;
    }
}
