<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251201181012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recommendation_letter (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, hopital_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_851040F06B899279 (patient_id), INDEX IDX_851040F0B03A8386 (created_by_id), INDEX IDX_851040F0896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recommendation_letter ADD CONSTRAINT FK_851040F06B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE recommendation_letter ADD CONSTRAINT FK_851040F0B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE recommendation_letter ADD CONSTRAINT FK_851040F0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recommendation_letter DROP FOREIGN KEY FK_851040F06B899279');
        $this->addSql('ALTER TABLE recommendation_letter DROP FOREIGN KEY FK_851040F0B03A8386');
        $this->addSql('ALTER TABLE recommendation_letter DROP FOREIGN KEY FK_851040F0896DBBDE');
        $this->addSql('DROP TABLE recommendation_letter');
    }
}
