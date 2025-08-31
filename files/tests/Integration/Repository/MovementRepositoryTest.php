<?php
declare(strict_types=1);

namespace App\Tests\Integration\Repository;

use App\Entity\Category;
use App\Entity\Movement;
use App\Enum\MovementType as MovementEnum;
use App\Repository\MovementRepository;
use App\Tests\Integration\DatabaseTestCase;

/**
 * @covers \App\Repository\MovementRepository
 */
final class MovementRepositoryTest extends DatabaseTestCase
{
    private function seed(): array
    {
        $food = (new Category())->setName('Cibo')->setColor('#00AAFF');
        $transport = (new Category())->setName('Trasporti')->setColor('#AA00FF');

        $this->em->persist($food);
        $this->em->persist($transport);

        $m1 = (new Movement())
            ->setAmount('1000.00')->setDescription('Stipendio')
            ->setDate(new \DateTimeImmutable('2025-08-01'))
            ->setType(MovementEnum::INCOME)
            ->setCategory(null);

        $m2 = (new Movement())
            ->setAmount('50.00')->setDescription('Spesa supermercato')
            ->setDate(new \DateTimeImmutable('2025-08-02'))
            ->setType(MovementEnum::EXPENSE)
            ->setCategory($food);

        $m3 = (new Movement())
            ->setAmount('20.00')->setDescription('Biglietto bus')
            ->setDate(new \DateTimeImmutable('2025-08-03'))
            ->setType(MovementEnum::EXPENSE)
            ->setCategory($transport);

        foreach ([$m1, $m2, $m3] as $m) {
            $this->em->persist($m);
        }
        $this->em->flush();

        return [$food, $transport, $m1, $m2, $m3];
    }

    public function testFindByFilterWithoutCategoryReturnsAllSortedByDateDesc(): void
    {
        [$food, $transport, $m1, $m2, $m3] = $this->seed();

        /** @var MovementRepository $repo */
        $repo = $this->em->getRepository(Movement::class);

        $rows = $repo->findByFilter(null);

        self::assertCount(3, $rows, 'Dovrebbero tornare tutti i movimenti');
        // Ordinamento per data DESC atteso: 3 -> 2 -> 1
        self::assertSame($m3->getId(), $rows[0]->getId());
        self::assertSame($m2->getId(), $rows[1]->getId());
        self::assertSame($m1->getId(), $rows[2]->getId());
    }

    public function testFindByFilterWithCategoryReturnsOnlyThatCategory(): void
    {
        [$food, $transport] = $this->seed();

        /** @var MovementRepository $repo */
        $repo = $this->em->getRepository(Movement::class);

        $rowsFood = $repo->findByFilter($food);
        $rowsTransport = $repo->findByFilter($transport);

        self::assertCount(1, $rowsFood);
        self::assertTrue($rowsFood[0]->getCategory()?->getName() === 'Cibo');

        self::assertCount(1, $rowsTransport);
        self::assertTrue($rowsTransport[0]->getCategory()?->getName() === 'Trasporti');
    }
}
