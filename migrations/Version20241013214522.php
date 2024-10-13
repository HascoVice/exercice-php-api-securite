<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013214522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495D450674');
        $this->addSql('DROP INDEX IDX_8D93D6495D450674 ON user');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP user_company_roles_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_company_roles_id INT DEFAULT NULL, DROP roles');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495D450674 FOREIGN KEY (user_company_roles_id) REFERENCES user_company_roles (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495D450674 ON user (user_company_roles_id)');
    }
}
