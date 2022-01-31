<?php

namespace App\Entity;

use App\Repository\KeywordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Zim\Bundle\SymfonyRestHelperBundle\Doctrine\ORMSetterTrait;

/**
 * @ORM\Entity(repositoryClass=KeywordRepository::class)
 * @OA\Schema(x={"fqcn"="App\Entity\Keyword", "groups"={"KeywordList", "KeywordShow", "KeywordPost", "KeywordEmbedded"}})
 */
class Keyword
{
    use ORMSetterTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(type="string")
     * @Groups({"KeywordList", "KeywordShow", "KeywordEmbedded"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @OA\Property(type="string")
     * @Groups({"KeywordList", "KeywordShow", "KeywordPost", "KeywordEmbedded"})
     */
    private $name;

    /**
     * @var Book[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Book", mappedBy="keywords")
     * @OA\Property(type="array", @OA\Items(type="string"), x={ "groups"={"KeywordPost"} })
     * @OA\Property(type="array", @OA\Items(ref="#/components/schemas/BookEmbedded"), x={ "groups"={"KeywordList", "KeywordShow"} })
     * @Groups({"KeywordList", "KeywordShow", "KeywordPost"})
     */
    private $books;

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

    /**
     * @return Book[]|ArrayCollection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param Book[]|ArrayCollection $books
     */
    public function setBooks($books): void
    {
        $this->handleManyToManyInverseSide($books, 'books',
            function(Book $a, Book $b) {
                return $a->getId() == $b->getId();
            },
            function(Book $newBook) {
                $newBook->getKeywords()->add($this);
                return $newBook;
            },
            function(Book $book){
                $book->getKeywords()->removeElement($this);
            },
            function(Book $book){
                $book->getKeywords()->add($this);
            }
        );
    }
}
