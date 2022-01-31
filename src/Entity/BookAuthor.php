<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Zim\Bundle\SymfonyRestHelperBundle\Doctrine\ORMSetterTrait;

/**
 * @ORM\Entity()
 * @OA\Schema(x={"fqcn"="App\Entity\BookAuthor", "groups"={"BookAuthorList", "BookAuthorShow", "BookAuthorPost", "BookAuthorEmbedded"}})
 */
class BookAuthor
{
    use ORMSetterTrait;

    const List = 'BookAuthorList';
    const Show = 'BookAuthorShow';
    const Post = 'BookAuthorPost';
    const Embedded = 'BookAuthorEmbedded';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(type="string")
     * @Groups({"BookAuthorList", "BookAuthorShow", "BookAuthorEmbedded"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @OA\Property(type="string", nullable=false)
     * @Groups({"BookAuthorList", "BookAuthorShow", "BookAuthorPost", "BookAuthorEmbedded"})
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     * @OA\Property(type="integer")
     * @Groups({"BookAuthorList", "BookAuthorShow", "BookAuthorPost"})
     * @Assert\Range(min=0, max=100)
     */
    private $age;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default"="1"})
     * @OA\Property(type="boolean")
     * @Groups({"BookAuthorList", "BookAuthorShow", "BookAuthorPost"})
     */
    private $isActive = true;

    /**
     * @var Book[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Book", mappedBy="author", cascade={"persist"})
     * @OA\Property(type="array", items=@OA\Items(ref="#/components/schemas/BookEmbedded"))
     * @Groups({"BookAuthorShow", "BookAuthorPost"})
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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
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
        $this->handleOneToMany($books, 'books',
            function(Book $a, Book $b){
                return $a->getId() == $b->getId();
            },
            function(Book $item) {
                $item->setAuthor($this);
                return $item;
            },
            function(Book $item) {
                $item->setAuthor(null);
            }
        );
    }
}