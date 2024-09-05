<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827191729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "products" (id SERIAL NOT NULL, organization_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A32C8A3DE ON "products" (organization_id)');
        $this->addSql('ALTER TABLE "products" ADD CONSTRAINT FK_B3BA5A5A32C8A3DE FOREIGN KEY (organization_id) REFERENCES "organizations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "products" DROP CONSTRAINT FK_B3BA5A5A32C8A3DE');
        $this->addSql('DROP TABLE "products"');
    }
}
