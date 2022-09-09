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
    attributes: ["security" => "is_granted('ROLE_USER')"],
)]
#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'commentId' => 'exact',
    ]
)]
#[ORM\Entity]
class Comment
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
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id",  nullable: 'false')]
    public $user;

    #[Groups(["read"])]
    #[ORM\ManyToOne(targetEntity: Post::class , inversedBy: 'comments')]
    #[ORM\JoinColumn(name:"post_id", referencedColumnName:"id",  nullable: 'false')]
    public $post;

    #[Groups(["read", "patch"])]
    #[ORM\Column(type: 'string', nullable: 'false')]
    public $content;

    #[Groups(["read"])]
    #[ORM\Column(type: "string", nullable: "true")]
    #[ApiProperty(required: false)]
    public $commentId;

    #[Groups(["read"])]
    #[ORM\ManyToMany(targetEntity: Reaction::class, mappedBy: 'comments')]
    public $reactions;

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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post): void
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * @param mixed $commentId
     */
    public function setCommentId($commentId): void
    {
        $this->commentId = $commentId;
    }

    /**
     * @return ArrayCollection
     */
    public function getReactions(): ArrayCollection
    {
        return $this->reactions;
    }
}