<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"userList"}},
 *     denormalizationContext={"groups"={"userCreate"}},
 *     validationGroups="userCreate",
 *     itemOperations={
 *        "get",
 *        "put",
 *        "changePassword"={
 *             "method"="PUT",
 *             "path"="/users/{id}/change-password",
 *             "denormalization_context"={"groups"={"userChangePassword"}},
 *             "validation_groups"={"userChangePassword"},
 *             "swagger_context"={
 *                 "summary" = "Change user password"
 *             }
 *         }
 *     }
 * )
 *
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Util\Doctrine\UuidIdGenerator")
     * @Groups({"userList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userList", "userCreate"})
     * @Assert\NotBlank(groups={"userCreate"})
     * @Assert\Length(max="255", groups={"userCreate"})
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"userList", "userCreate"})
     * @Assert\Email(groups={"userCreate"})
     * @Assert\NotBlank(groups={"userCreate"})
     * @Assert\Length(max="255", groups={"userCreate"})
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userCreate", "userChangePassword"})     
     */
    public $password;

    /**
     * @ORM\Column(type="json")
     */
    public $roles = ['ROLE_USER'];

    /**
     * @Groups({"userCreate", "userChangePassword"})
     * @Assert\NotBlank(groups={"userCreate", "userChangePassword"})
     * @Assert\Length(min="8", max="255", groups={"userCreate", "userChangePassword"})
     *
     * @var string
     */
    public $plainPassword;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->name;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string {
        return $this->email;
    }
}
