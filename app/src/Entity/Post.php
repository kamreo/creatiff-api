<?php

namespace App\Entity;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    collectionOperations: [
        "get",
        "post",
    ],
    itemOperations: [
        "get",
        "put" => [
            "security" => "object.user == user",
        ],
        "delete" => [
            "security" => "object.user == user",
        ],
    ],
    attributes: ["security" => "is_granted('ROLE_USER')"],
)]
#[ORM\Entity]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    public $user;

    #[ORM\Column(name: "title", type: "string", length: 100)]
    private $title;

     #[ORM\Column(name: "description", type: "string", length: 2000)]
    private $description;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/image')]
    public ?MediaObject $image = null;

    /**
     * @return mixed
     */
    public function getId()
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return MediaObject|null
     */
    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    /**
     * @param MediaObject|null $image
     */
    public function setImage(?MediaObject $image): void
    {
        $this->image = $image;
    }
}