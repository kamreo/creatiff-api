<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
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
    attributes: ["security" => "is_granted('ROLE_USER')"],
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
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private $id;

    #[Groups(["read"])]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:"user_id", referencedColumnName:"id")]
    public User $user;

    #[Groups(["read", "patch"])]
    #[ORM\Column(name: "title", type: "string", length: 100)]
    private string $title;

    #[Groups(["read", "patch"])]
    #[ORM\Column(name: "description", type: "string", length: 2000)]
    private string $description;

    #[Groups(["read", "patch"])]
    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(required: false)]
    public ?MediaObject $image = null;

    #[ORM\OneToMany(mappedBy: "post", targetEntity: "App\Entity\Comment")]
    private $comments;
























}