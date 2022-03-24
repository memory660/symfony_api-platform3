<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"subjectList"}},
 *     denormalizationContext={"groups"={"subjectCreate"}},
 *     itemOperations={
 *      "get",
 *      "put"={"access_control"="is_granted('SUBJECT_EDIT', object)", "access_control_message"="You are not owner of this user."},
  *     "delete"={"access_control"="is_granted('SUBJECT_DELETE', object)", "access_control_message"="You are not owner of this user."}* 
 *     },
 *     attributes={"access_control"="is_granted('ROLE_USER')"}
 * )
 *
 * @ORM\Entity
 */
class Subject
{
    /**
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Util\Doctrine\UuidIdGenerator")
     * @Groups({"subjectList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"subjectList", "subjectCreate"})
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    /**
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="subject", cascade={"persist", "remove"})
     * @ApiSubresource()
     */
    public $lessons;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @Assert\NotBlank(groups={"subjectCreate"})
     */
    public $user;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}

