<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @OA\Schema(x={"fqcn"="App\Entity\Book", "groups"={"BookList", "BookShow", "BookPost", "BookAuthorShow(Book)"}})
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(type="string")
     * @Groups({"BookList", "BookShow", "BookAuthorShow(Book)"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @OA\Property(type="string", maxLength=255, nullable=false)
     * @Groups({"BookList", "BookShow", "BookPost", "BookAuthorShow(Book)"})
     * @Assert\NotBlank(message="Book title required")
     */
    private $title;

    /**
     * @var BookAuthor|null
     *
     * @ORM\ManyToOne(targetEntity="BookAuthor", inversedBy="books")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @OA\Property(ref="#/components/schemas/BookAuthorList", x={ "groups"={"BookList", "BookShow"} })
     * @OA\Property(type="string", x={ "groups"={"BookPost"} })
     * @Groups({"BookList", "BookShow", "BookPost"})
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return BookAuthor|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param BookAuthor|null $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
}
