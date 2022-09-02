<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private $id;

    #[Groups(["read"])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    public $user;

    #[Groups(["read"])]
    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(name:"post_id", referencedColumnName:"id")]
    public $post;

    #[Groups(["read", "patch"])]
    #[ORM\Column(type: 'string', nullable: 'false')]
    public $content;

    #[Groups(["read"])]
    #[ORM\Column(type: "string", nullable: "true")]
    public $commentId;


}