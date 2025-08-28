<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add 'color' column to categories (#RRGGBB).
 */
final class Version20250828120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add color column to categories';
    }

    public function up(Schema $schema): void
    {
        // 1) Colonna 'color' (idempotente)
        $this->addSql("ALTER TABLE categories ADD COLUMN IF NOT EXISTS color VARCHAR(7)");
        // Metto un default temporaneo per evitare problemi col NOT NULL su righe esistenti
        $this->addSql("ALTER TABLE categories ALTER COLUMN color SET DEFAULT '#000000'");
        $this->addSql("UPDATE categories SET color = '#000000' WHERE color IS NULL");
        $this->addSql("ALTER TABLE categories ALTER COLUMN color SET NOT NULL");

        // 2) Vincolo di formato esadecimale — safe e idempotente:
        //    non esiste 'ADD CONSTRAINT IF NOT EXISTS', quindi prima lo droppo se c'è
        $this->addSql("ALTER TABLE categories DROP CONSTRAINT IF EXISTS categories_color_chk");
        $this->addSql("ALTER TABLE categories ADD CONSTRAINT categories_color_chk CHECK (color ~ '^#[0-9A-Fa-f]{6}$')");
    }

    public function down(Schema $schema): void
    {
        // Tolgo il vincolo se presente e poi la colonna
        $this->addSql("ALTER TABLE categories DROP CONSTRAINT IF EXISTS categories_color_chk");
        $this->addSql("ALTER TABLE categories DROP COLUMN IF EXISTS color");
    }

}
