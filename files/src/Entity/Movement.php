<?php

namespace App\Entity;

use App\Enum\MovementType;
use App\Repository\MovementRepository;
use App\Validator\Constraints\CategoryForExpense;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovementRepository::class)]
#[ORM\Table(name: 'movements')]
#[CategoryForExpense]
class Movement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Salviamo come DECIMAL(10,2) in DB e come stringa in PHP (per evitare problemi di float).
     */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'L\'ammontare è obbligatorio.')]
    #[Assert\Regex(
        pattern: '/^(?:0|[1-9]\d*)(?:\.\d{1,2})?$/',
        message: 'Inserisci un importo positivo con al massimo due decimali.'
    )]
    #[Assert\GreaterThan(value: 0, message: 'L\'ammontare deve essere strettamente positivo.')]
    private ?string $amount = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100, maxMessage: 'Max 100 caratteri.')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today', message: 'La data non può essere nel futuro.')]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::STRING, enumType: MovementType::class)]
    #[Assert\NotNull]
    private ?MovementType $type = MovementType::EXPENSE;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL', nullable: true)]
    private ?Category $category = null;

    public function getId(): ?int { return $this->id; }

    public function getAmount(): ?string { return $this->amount; }
    public function setAmount(string $amount): self { $this->amount = $amount; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDate(): ?\DateTimeImmutable { return $this->date; }
    public function setDate(\DateTimeImmutable $date): self { $this->date = $date; return $this; }

    public function getType(): ?MovementType { return $this->type; }
    public function setType(MovementType $type): self { $this->type = $type; return $this; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): self { $this->category = $category; return $this; }
}
