<?php
declare(strict_types=1);

namespace App\Tests\Integration\Validation;

use App\Entity\Category;
use App\Entity\Movement;
use App\Enum\MovementType as MovementEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Verifica le Assert sull'entity Movement (senza toccare Controller/Form).
 *
 * @covers \App\Entity\Movement
 */
final class MovementValidationTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        /** @var ValidatorInterface $validator */
        $validator = static::getContainer()->get(ValidatorInterface::class);
        $this->validator = $validator;
    }

    private function makeValid(): Movement
    {
        return (new Movement())
            ->setAmount('12.50')
            ->setDescription('Spesa valida')
            ->setDate(new \DateTimeImmutable('2025-08-01'))
            ->setType(MovementEnum::EXPENSE)
            ->setCategory((new Category())->setName('Cibo')->setColor('#00AAFF'));
    }

    public function testAmountMustBeGreaterThanZero(): void
    {
        $m = $this->makeValid()->setAmount('0.00');
        $violations = $this->validator->validate($m);

        self::assertGreaterThanOrEqual(1, $violations->count());
        self::assertTrue(
            \in_array('amount', array_map(fn($v) => $v->getPropertyPath(), iterator_to_array($violations))),
            'Mi aspetto una violazione su "amount"'
        );
    }

    public function testDateCannotBeInTheFuture(): void
    {
        $m = $this->makeValid()->setDate(new \DateTimeImmutable('+2 days'));
        $violations = $this->validator->validate($m);

        self::assertGreaterThanOrEqual(1, $violations->count());
        self::assertTrue(
            \in_array('date', array_map(fn($v) => $v->getPropertyPath(), iterator_to_array($violations))),
            'Mi aspetto una violazione su "date"'
        );
    }

    public function testDescriptionTooLongIsInvalid(): void
    {
        $m = $this->makeValid()->setDescription(str_repeat('x', 101));
        $violations = $this->validator->validate($m);

        self::assertGreaterThanOrEqual(1, $violations->count());
        self::assertTrue(
            \in_array('description', array_map(fn($v) => $v->getPropertyPath(), iterator_to_array($violations))),
            'Mi aspetto una violazione su "description"'
        );
    }

    public function testValidMovementHasNoViolations(): void
    {
        $m = $this->makeValid();
        $violations = $this->validator->validate($m);

        self::assertSame(0, $violations->count(), 'Un movimento valido non deve avere violazioni');
    }

    public function testIncomeWithCategoryTriggersClassLevelViolation(): void
    {
        // Verifichiamo anche il validator di classe come integrazione del servizio "validator"
        $m = (new Movement())
            ->setAmount('20.00')
            ->setDescription('Entrata con categoria')
            ->setDate(new \DateTimeImmutable('2025-08-01'))
            ->setType(MovementEnum::INCOME)
            ->setCategory((new Category())->setName('Qualcosa')->setColor('#123456'));

        $violations = $this->validator->validate($m);

        self::assertGreaterThanOrEqual(1, $violations->count(), 'Mi aspetto almeno una violazione di classe');
    }
}
