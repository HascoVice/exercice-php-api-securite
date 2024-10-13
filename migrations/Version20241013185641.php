<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013185641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_company_role DROP FOREIGN KEY FK_94C8EC86A76ED395');
        $this->addSql('ALTER TABLE user_company_role DROP FOREIGN KEY FK_94C8EC86979B1AD6');
        $this->addSql('DROP TABLE user_company_role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_company_role (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, company_id INT DEFAULT NULL, role VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_94C8EC86A76ED395 (user_id), INDEX IDX_94C8EC86979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_company_role ADD CONSTRAINT FK_94C8EC86A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_company_role ADD CONSTRAINT FK_94C8EC86979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }
}
