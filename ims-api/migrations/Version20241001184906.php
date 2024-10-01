<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001184906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "product_providers" (id SERIAL NOT NULL, organization_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C85629A32C8A3DE ON "product_providers" (organization_id)');
        $this->addSql('CREATE TABLE "tags" (id SERIAL NOT NULL, organization_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FBC942632C8A3DE ON "tags" (organization_id)');
        $this->addSql('ALTER TABLE "product_providers" ADD CONSTRAINT FK_8C85629A32C8A3DE FOREIGN KEY (organization_id) REFERENCES "organizations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "tags" ADD CONSTRAINT FK_6FBC942632C8A3DE FOREIGN KEY (organization_id) REFERENCES "organizations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE products ADD provider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA53A8AA FOREIGN KEY (provider_id) REFERENCES "product_providers" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AA53A8AA ON products (provider_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "products" DROP CONSTRAINT FK_B3BA5A5AA53A8AA');
        $this->addSql('ALTER TABLE "product_providers" DROP CONSTRAINT FK_8C85629A32C8A3DE');
        $this->addSql('ALTER TABLE "tags" DROP CONSTRAINT FK_6FBC942632C8A3DE');
        $this->addSql('DROP TABLE "product_providers"');
        $this->addSql('DROP TABLE "tags"');
        $this->addSql('DROP INDEX IDX_B3BA5A5AA53A8AA');
        $this->addSql('ALTER TABLE "products" DROP provider_id');
    }
}
