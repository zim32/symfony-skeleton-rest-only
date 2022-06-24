<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use Zim\Bundle\SymfonyRestHelperBundle\Doctrine\ORMSetterTrait;
use Zim\Bundle\SymfonyRestHelperBundle\Helper\SchemaHelper;

#[ORM\Entity]
#[OA\Schema(x: ["fqcn"=>Book::class, "groups"=>[self::ListGroup, self::ShowGroup, self::PostGroup, self::EmbeddedGroup]])]
class Book
{
    use ORMSetterTrait;

    const ListGroup = 'BookList';
    const ShowGroup = 'BookShow';
    const PostGroup = 'BookPost';
    const EmbeddedGroup = 'BookEmbedded';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[OA\Property(type: "integer")]
    #[Groups([self::ListGroup, self::ShowGroup, self::EmbeddedGroup])]
    private int $id;


    #[ORM\Column(type: "string", length: 255)]
    #[OA\Property(type: "string", maxLength: 255, nullable: false)]
    #[Groups([self::ListGroup, self::ShowGroup, self::PostGroup, self::EmbeddedGroup])]
    #[Assert\NotBlank(message: "Book title required")]
    private string $title;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    #[OA\Property(ref: SCHEMA_REF . Author::EmbeddedGroup, x: ["groups"=>[self::ListGroup, self::ShowGroup]])]
    #[OA\Property(type: "string", x: ["groups"=>[self::PostGroup]])]
    #[Groups([self::ListGroup, self::ShowGroup, self::PostGroup])]
    private ?Author $author = null;

    /**
     * @var Tag[]|ArrayCollection
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: "books")]
    #[OA\Property(type: "array", items: new OA\Items(type: "string"), x: ["groups"=>[self::PostGroup]])]
    #[OA\Property(type: "array", items: new OA\Items(ref: SCHEMA_REF . Tag::EmbeddedGroup), x: ["groups"=>[self::ListGroup, self::ShowGroup]])]
    #[Groups([self::ListGroup, self::ShowGroup, self::PostGroup])]
    private iterable $tags;

    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author)
    {
        $this->author = $author;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags(): iterable
    {
        return $this->tags;
    }

    public function setTags(iterable $tags)
    {
        $this->tags = $tags;
    }
}