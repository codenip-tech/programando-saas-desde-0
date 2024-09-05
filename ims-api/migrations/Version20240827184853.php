<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827184853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "memberships" (id SERIAL NOT NULL, user_id INT NOT NULL, organization_id INT NOT NULL, role VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_865A4776A76ED395 ON "memberships" (user_id)');
        $this->addSql('CREATE INDEX IDX_865A477632C8A3DE ON "memberships" (organization_id)');
        $this->addSql('CREATE UNIQUE INDEX memberships_org_user_id_uniq ON "memberships" (organization_id, user_id)');
        $this->addSql('CREATE TABLE "organizations" (id SERIAL NOT NULL, owner_id INT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_427C1C7F7E3C61F9 ON "organizations" (owner_id)');
        $this->addSql('CREATE TABLE "users" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "users" (email)');
        $this->addSql('ALTER TABLE "memberships" ADD CONSTRAINT FK_865A4776A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "memberships" ADD CONSTRAINT FK_865A477632C8A3DE FOREIGN KEY (organization_id) REFERENCES "organizations" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "organizations" ADD CONSTRAINT FK_427C1C7F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "memberships" DROP CONSTRAINT FK_865A4776A76ED395');
        $this->addSql('ALTER TABLE "memberships" DROP CONSTRAINT FK_865A477632C8A3DE');
        $this->addSql('ALTER TABLE "organizations" DROP CONSTRAINT FK_427C1C7F7E3C61F9');
        $this->addSql('DROP TABLE "memberships"');
        $this->addSql('DROP TABLE "organizations"');
        $this->addSql('DROP TABLE "users"');
    }
}
