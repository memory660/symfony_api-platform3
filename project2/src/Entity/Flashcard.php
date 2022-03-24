<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"flashcardList"}},
 *     denormalizationContext={"groups"={"flashcardCreate"}}
 * )
 *
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *          "question": "partial",
 *          "answer": "partial"
 *     }
 * )
 *
 * @ORM\Entity
 */
class Flashcard
{
    /**
     * @ORM\Column(name="id", type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Util\Doctrine\UuidIdGenerator")
     * @Groups({"flashcardList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"flashcardList", "flashcardCreate"})
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $question;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"flashcardList", "flashcardCreate"})
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    public $answer;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="flashcards")
     * @Groups({"flashcardCreate"})
     * @Assert\NotBlank()
     */
    public $lesson;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
