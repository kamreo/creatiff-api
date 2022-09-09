<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;


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
        'commentId' => 'exact',
        'postId' => 'exact',
        'userId' => 'exact',
    ]
)]

#[ORM\Entity]
class Reaction
{
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private $id;

    #[Groups(["read"])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reactions')]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    public User $user;

    #[Groups(["read"])]
    #[ORM\ManyToMany(targetEntity: Post::class, inversedBy: 'reactions')]
    public $posts;

    #[Groups(["read"])]
    #[ORM\ManyToMany(targetEntity: Comment::class, inversedBy: 'reactions')]
    public $comments;

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
     * @return ArrayCollection
     */
    public function getPosts(): ArrayCollection
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection $posts
     */
    public function setPosts(ArrayCollection $posts): void
    {
        $this->posts = $posts;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }

    /**
     * @param ArrayCollection $comments
     */
    public function setComments(ArrayCollection $comments): void
    {
        $this->comments = $comments;
    }
}