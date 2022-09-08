<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        "get" => ['normalization_context' => ['groups' => ['read']],],
        "post",
    ],
    itemOperations: [
        "get" => [
            'normalization_context' => ['groups' => ['read']],
        ],
        "patch" => [
            "security" => "object.user == user",
            'normalization_context' => ['groups' => ['patch']],
        ],
        "delete" => [
            "security" => "object.user == user",
        ],
    ],
)]

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'title' => 'partial',
    ]
)]
#[ORM\Entity]
class Post
{
    public function __construct()
    {
        $this->reactions = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private $id;

    #[Groups(["read"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    public User $user;

    #[Groups(["read", "patch"])]
    #[ORM\Column(name: "title", type: "string", length: 100)]
    public string $title;

    #[Groups(["read", "patch"])]
    #[ORM\Column(name: "description", type: "string", length: 2000)]
    public string $description;

    #[Groups(["read", "patch"])]
    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(required: false)]
    public ?MediaObject $image = null;

    #[ORM\OneToMany(mappedBy: "post", targetEntity: "App\Entity\Comment")]
    private $comments;

    #[Groups(["read"])]
    #[ORM\ManyToMany(targetEntity: Reaction::class, mappedBy: 'posts')]
    public $reactions;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return MediaObject|null
     */
    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return ArrayCollection
     */
    public function getReactions(): ArrayCollection
    {
        return $this->reactions;
    }
}