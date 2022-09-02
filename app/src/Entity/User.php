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
            'normalization_context' => ['groups' => ['read']],
        ],
        'register' => [
            'method' => 'POST',
            'path' => '/user/register',
            'controller' => RegistrationController::class,
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['read']],
        ],
        'patch' =>  [
            "security" => "object.user == user",
            'normalization_context' => ['groups' => ['patch']],
        ],
        'follow' => [
            'normalization_context' => ['groups' => ['read']],
            'method' => 'GET',
            'path' => '/user/follow/{id}',
            'controller' => FollowController::class,
        ],
        'unfollow' => [
            'normalization_context' => ['groups' => ['read']],
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

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[Groups(["read", "patch"])]
    #[ORM\Column(type: 'string', unique: true)]
    private $username;

    #[ORM\Column(type: 'json')]
    private $roles = [];

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

    #[Groups(["read"])]
    #[ORM\ManyToMany(mappedBy: "following", targetEntity: "App\Entity\User")]
    private Collection $followers;

    #[Groups(["read"])]
    #[ORM\ManyToMany(targetEntity: "App\Entity\User", inversedBy: "followers")]
    private Collection $following;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return MediaObject|null
     */
    public function getProfileImage(): ?MediaObject
    {
        return $this->profileImage;
    }

    /**
     * @param MediaObject|null $profileImage
     */
    public function setProfileImage(?MediaObject $profileImage): void
    {
        $this->profileImage = $profileImage;
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
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
