<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031201239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "organization_billings" (id SERIAL NOT NULL, organization_id INT NOT NULL, customer_id VARCHAR(100) NOT NULL, status VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2F9651332C8A3DE ON "organization_billings" (organization_id)');
        $this->addSql('ALTER TABLE "organization_billings" ADD CONSTRAINT FK_C2F9651332C8A3DE FOREIGN KEY (organization_id) REFERENCES "organizations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "organization_billings" DROP CONSTRAINT FK_C2F9651332C8A3DE');
        $this->addSql('DROP TABLE "organization_billings"');
    }
}
