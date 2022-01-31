<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @OA\Schema(x={"fqcn"="App\Entity\Book", "groups"={Book::List, Book::Show, Book::Post, Book::Embedded}})
 */
class Book
{
    const List = 'BookList';
    const Show = 'BookShow';
    const Post = 'BookPost';
    const Embedded = 'BookEmbedded';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(type="string")
     * @Groups({Book::List, Book::Show, Book::Embedded})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @OA\Property(type="string", maxLength=255, nullable=false)
     * @Groups({"BookList", "BookShow", "BookPost", "BookEmbedded"})
     * @Assert\NotBlank(message="Book title required")
     */
    private $title;

    /**
     * @var BookAuthor|null
     *
     * @ORM\ManyToOne(targetEntity="BookAuthor", inversedBy="books")
     * @ORM\JoinColumn(onDelete="SET NULL")
     * @OA\Property(ref="#/components/schemas/BookAuthorEmbedded", x={ "groups"={"BookList", "BookShow"} })
     * @OA\Property(type="string", x={ "groups"={"BookPost"} })
     * @Groups({"BookList", "BookShow", "BookPost"})
     */
    private $author;

    /**
     * @var Keyword[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Keyword", inversedBy="books")
     * @OA\Property(type="array", @OA\Items(type="string"), x={ "groups"={"BookPost"} })
     * @OA\Property(type="array", @OA\Items(ref="#/components/schemas/KeywordEmbedded"), x={ "groups"={"BookList", "BookShow"} })
     * @Groups({"BookList", "BookShow", "BookPost"})
     */
    private $keywords;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
    }

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

    /**
     * @return Keyword[]|ArrayCollection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param Keyword[]|ArrayCollection $keywords
     */
    public function setKeywords($keywords): void
    {
        $this->keywords = $keywords;
    }
}