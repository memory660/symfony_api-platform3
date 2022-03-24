<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"lessonList"}},
 *     denormalizationContext={"groups"={"lessonCreate"}},
 *     itemOperations={"get","put"}
 * )
 *
 * @ORM\Entity
 */
class Lesson
{
    /**
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Util\Doctrine\UuidIdGenerator")
     * @Groups({"lessonList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"lessonList", "lessonCreate"})
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $name;

    /**
     * @ORM\OneToMany(targetEntity="Flashcard", mappedBy="lesson", cascade={"persist", "remove"})
     * @ApiSubresource()
     */
    public $flashcards;

    /**
     * @ORM\ManyToOne(targetEntity="Subject", inversedBy="lessons")
     * @Groups({"lessonCreate"})
     * @Assert\NotBlank()
     */
    public $subject;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}

