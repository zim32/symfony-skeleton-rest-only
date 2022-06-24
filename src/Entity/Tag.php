<?php

namespace App\Entity;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;    

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zim\Bundle\SymfonyRestHelperBundle\Doctrine\ORMSetterTrait;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[OA\Schema(x: ["fqcn"=>Tag::class, "groups"=>[self::ListGroup, self::ShowGroup, self::PostGroup, self::EmbeddedGroup]])]
class Tag
{
    use ORMSetterTrait;

    const ListGroup = 'TagList';
    const ShowGroup = 'TagShow';
    const PostGroup = 'TagPost';
    const EmbeddedGroup = 'TagEmbedded';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[OA\Property(type: "integer")]
    #[Groups([self::ListGroup, self::ShowGroup, self::EmbeddedGroup])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[OA\Property(type: "string", nullable: false)]
    #[Groups([self::ListGroup, self::ShowGroup, self::PostGroup, self::EmbeddedGroup])]
    private $name;

    /**
     * @var Book[]|ArrayCollection
     */
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: "tags")]
    #[OA\Property(type: "array", items: new OA\Items(type: "string"), x: ["groups"=>[self::PostGroup]])]
    #[OA\Property(type: "array", items: new OA\Items(ref: SCHEMA_REF . Book::EmbeddedGroup), x: ["groups"=>[self::ListGroup, self::ShowGroup]])]
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

    public function setBooks(iterable $books)
    {
        $this->handleManyToManyInverseSide(
            $books, 
            'books',
            'tags',
            function(Book $a, Book $b) {
                return $a->getId() == $b->getId();
            }
        );
    }
}
