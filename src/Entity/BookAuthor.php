<?php

namespace App\Entity;

use App\Repository\BookAuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookAuthorRepository::class)
 * @OA\Schema(x={"fqcn"="App\Entity\BookAuthor", "groups"={"BookAuthorList", "BookAuthorShow", "BookAuthorPost"}})
 */
class BookAuthor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(type="string")
     * @Groups({"BookAuthorList", "BookAuthorShow"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @OA\Property(type="string", nullable=false)
     * @Groups({"BookAuthorList", "BookAuthorShow", "BookAuthorPost"})
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
     * @ORM\OneToMany(targetEntity="Book", mappedBy="author")
     * @OA\Property(type="array", items=@OA\Items(ref="#/components/schemas/BookAuthorShow(Book)"))
     * @Groups({"BookAuthorShow"})
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
}
