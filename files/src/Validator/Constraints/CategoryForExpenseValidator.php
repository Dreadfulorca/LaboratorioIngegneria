<?php
namespace App\Validator\Constraints;

use App\Entity\Movement;
use App\Enum\MovementType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CategoryForExpenseValidator extends ConstraintValidator
{
    public function validate($movement, Constraint $constraint): void
    {
        if (!$movement instanceof Movement) return;

        $type = $movement->getType();
        $category = $movement->getCategory();

        if ($type === MovementType::EXPENSE && $category === null) {
            $this->context->buildViolation($constraint->messageMissing)
                ->atPath('category')->addViolation();
        }

        if ($type === MovementType::INCOME && $category !== null) {
            $this->context->buildViolation($constraint->messageForbidden)
                ->atPath('category')->addViolation();
        }
    }
}
