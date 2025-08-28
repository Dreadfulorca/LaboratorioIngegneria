<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 60, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 60)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 7)]
    #[Assert\NotBlank(message: 'Il colore è obbligatorio.')]
    #[Assert\Regex(pattern: '/^#[0-9A-Fa-f]{6}$/', message: 'Inserisci un colore esadecimale valido, es. #A1B2C3')]
    private ?string $color = null;

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getColor(): ?string { return $this->color; }
    public function setColor(string $color): self { $this->color = strtoupper($color); return $this; }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
