<?php
declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Entity\Category;
use App\Entity\Movement;
use App\Enum\MovementType as MovementEnum; // alias per evitare collisione con FormType
use App\Validator\Constraints\CategoryForExpense;
use App\Validator\Constraints\CategoryForExpenseValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * @covers \App\Validator\Constraints\CategoryForExpense
 * @covers \App\Validator\Constraints\CategoryForExpenseValidator
 */
final class CategoryForExpenseValidatorTest extends TestCase
{
    /** @return array<string, Movement> */
    private function buildMovements(): array
    {
        $baseDate = new \DateTimeImmutable('2025-08-01');

        $incomeNoCat = (new Movement())
            ->setAmount('100.00')
            ->setDescription('Stipendio')
            ->setDate($baseDate)
            ->setType(MovementEnum::INCOME)
            ->setCategory(null);

        $incomeWithCat = (new Movement())
            ->setAmount('50.00')
            ->setDescription('Entrata con categoria (non valida)')
            ->setDate($baseDate)
            ->setType(MovementEnum::INCOME)
            ->setCategory((new Category())->setName('Varie')->setColor('#FFAA00'));

        $expenseNoCat = (new Movement())
            ->setAmount('10.00')
            ->setDescription('Uscita senza categoria (non valida)')
            ->setDate($baseDate)
            ->setType(MovementEnum::EXPENSE)
            ->setCategory(null);

        $expenseWithCat = (new Movement())
            ->setAmount('25.00')
            ->setDescription('Spesa alimentari')
            ->setDate($baseDate)
            ->setType(MovementEnum::EXPENSE)
            ->setCategory((new Category())->setName('Cibo')->setColor('#00AAFF'));

        return compact('incomeNoCat','incomeWithCat','expenseNoCat','expenseWithCat');
    }

    /** Crea validator con context finto (spy) che intercetta le violazioni. */
    private function makeValidator(?MockObject &$contextSpy = null, ?MockObject &$builderSpy = null): CategoryForExpenseValidator
    {
        /** @var ExecutionContextInterface&MockObject $context */
        $context = $this->createMock(ExecutionContextInterface::class);
        /** @var ConstraintViolationBuilderInterface&MockObject $builder */
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $builder->method('atPath')->willReturn($builder);
        $builder->method('setParameter')->willReturn($builder);
        $builder->method('setCode')->willReturn($builder);
        $builder->method('addViolation')->willReturn(null);

        $context->method('buildViolation')->willReturn($builder);

        $validator = new CategoryForExpenseValidator();
        $validator->initialize($context);

        $contextSpy = $context;
        $builderSpy = $builder;

        return $validator;
    }

    public function testIncomeCanNotHaveCategory(): void
    {
        $data = $this->buildMovements();
        $validator = $this->makeValidator($context, $builder);

        // ci aspettiamo UNA sola violazione costruita
        $context->expects(self::once())->method('buildViolation');
        $builder->expects(self::once())->method('addViolation');

        $validator->validate($data['incomeWithCat'], new CategoryForExpense());
    }

    public function testExpenseMustHaveCategory(): void
    {
        $data = $this->buildMovements();
        $validator = $this->makeValidator($context, $builder);

        $context->expects(self::once())->method('buildViolation');
        $builder->expects(self::once())->method('addViolation');

        $validator->validate($data['expenseNoCat'], new CategoryForExpense());
    }

    public function testIncomeWithoutCategoryIsValid(): void
    {
        $data = $this->buildMovements();
        $validator = $this->makeValidator($context, $builder);

        $context->expects(self::never())->method('buildViolation');
        $builder->expects(self::never())->method('addViolation');

        $validator->validate($data['incomeNoCat'], new CategoryForExpense());
    }

    public function testExpenseWithCategoryIsValid(): void
    {
        $data = $this->buildMovements();
        $validator = $this->makeValidator($context, $builder);

        $context->expects(self::never())->method('buildViolation');
        $builder->expects(self::never())->method('addViolation');

        $validator->validate($data['expenseWithCat'], new CategoryForExpense());
    }
}
