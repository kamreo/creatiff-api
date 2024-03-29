<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\FollowController;
use App\Controller\RegistrationController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['user:read']],
        ],
        'register' => [
            'method' => 'POST',
            'path' => '/user/register',
            'controller' => RegistrationController::class,
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['user:read']],
        ],
        'patch' =>  [
            "security" => "object.user == user",
            'normalization_context' => ['groups' => ['patch']],
        ],
        'follow' => [
            'normalization_context' => ['groups' => ['user:read']],
            'method' => 'GET',
            'path' => '/user/follow/{id}',
            'controller' => FollowController::class,
        ],
        'unfollow' => [
            'normalization_context' => ['groups' => ['user:read']],
            'method' => 'GET',
            'path' => '/user/unfollow/{id}',
            'controller' => FollowController::class,
        ],
    ],
)]

#[ApiFilter(SearchFilter::class,
    properties: [
        'id' => 'exact',
        'username' => 'partial',
    ]
)]

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->following = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[ApiProperty(identifier: true)]
    private $id;

    /**
     * @var null|string
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[Groups(["read", "user:read", "patch"])]
    #[ORM\Column(type: 'string', unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var null|string
     */
    #[Groups(["patch"])]
    #[ORM\Column(type: 'string')]
    private $password;

    #[Groups(["read", "patch"])]
    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/image')]
    public ?MediaObject $profileImage = null;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "App\Entity\Post")]
    private $posts;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "App\Entity\Comment")]
    private $comments;

    #[Groups(["user:read"])]
    #[ORM\ManyToMany(mappedBy: "following", targetEntity: "App\Entity\User")]
    private Collection $followers;

    #[Groups(["user:read"])]
    #[ORM\ManyToMany(targetEntity: "App\Entity\User", inversedBy: "followers")]
    private Collection $following;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: "App\Entity\Reaction")]
    private $reactions;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return Collection
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    /**
     * @return Collection
     */
    public function getFollowing(): Collection
    {
        return $this->following;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return string[]
     *
     * @psalm-return array<int, 'ROLE_USER'>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Follow another User
     *
     * @param User $user
     * @return void
     */
    public function follow(User $user)
    {
        $this->following->add($user);
    }

    /**
     * @see UserInterface
     *
     * @return void
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
