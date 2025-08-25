<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250825145811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE movements_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categories (id INT NOT NULL, name VARCHAR(60) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_3AF346685E237E06 ON categories (name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE movements (id INT NOT NULL, category_id INT DEFAULT NULL, amount NUMERIC(10, 2) NOT NULL, description VARCHAR(100) NOT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3823752112469DE2 ON movements (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN movements.date IS '(DC2Type:date_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE movements ADD CONSTRAINT FK_3823752112469DE2 FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE categories_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE movements_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE movements DROP CONSTRAINT FK_3823752112469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categories
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE movements
        SQL);
    }
}
