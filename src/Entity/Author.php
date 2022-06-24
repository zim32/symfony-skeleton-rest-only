<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Traversable;
use Zim\Bundle\SymfonyRestHelperBundle\Doctrine\ORMSetterTrait;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[OA\Schema(x: ["fqcn"=>Author::class, "groups"=>[self::ListGroup, self::ShowGroup, self::PostGroup, self::EmbeddedGroup]])]
class Author
{
    use ORMSetterTrait;

    const ListGroup = 'AuthorList';
    const ShowGroup = 'AuthorShow';
    const PostGroup = 'AuthorPost';
    const EmbeddedGroup = 'AuthorEmbedded';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[OA\Property(type: "integer")]
    #[Groups([self::ListGroup, self::ShowGroup, self::EmbeddedGroup])]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[OA\Property(type: "string", maxLength: 255, nullable: false)]
    #[Groups([self::ListGroup, self::ShowGroup, self::PostGroup, self::EmbeddedGroup])]
    #[Assert\NotBlank(message: "Book title required")]
    private string $name;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: "author")]
    #[OA\Property(
        oneOf: [
            new OA\Property(type: "array", items: new OA\Items(type: "integer")),
            new OA\Property(type: "array", items: new OA\Items(ref: SCHEMA_REF . Book::EmbeddedGroup ))
        ]
    )]
    #[Groups([self::ListGroup, self::ShowGroup, self::PostGroup])]
    private iterable $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBooks(): iterable 
    {
        return $this->books;
    }

    public function setBooks(iterable $books): void
    {
        $this->handleOneToMany(
            $books, 
            'books',
            'author',
            function(Book $submitted, Book $existing) {
                return $submitted->getId() == $existing->getId();
            }
        );
    }
}
