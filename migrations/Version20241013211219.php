<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013211219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_company_roles (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_1F4121F2979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_company_roles ADD CONSTRAINT FK_1F4121F2979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE user ADD user_company_roles_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495D450674 FOREIGN KEY (user_company_roles_id) REFERENCES user_company_roles (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495D450674 ON user (user_company_roles_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495D450674');
        $this->addSql('ALTER TABLE user_company_roles DROP FOREIGN KEY FK_1F4121F2979B1AD6');
        $this->addSql('DROP TABLE user_company_roles');
        $this->addSql('DROP INDEX IDX_8D93D6495D450674 ON user');
        $this->addSql('ALTER TABLE user DROP user_company_roles_id');
    }
}
