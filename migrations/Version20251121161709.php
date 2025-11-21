<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251121161709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossier_medical (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3581EE626B899279 (patient_id), INDEX IDX_3581EE62B03A8386 (created_by_id), INDEX IDX_3581EE62896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE626B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE62B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE62896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE suivi_soin ADD dossier_medical_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE suivi_soin ADD CONSTRAINT FK_B55097A07750B79F FOREIGN KEY (dossier_medical_id) REFERENCES dossier_medical (id)');
        $this->addSql('CREATE INDEX IDX_B55097A07750B79F ON suivi_soin (dossier_medical_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_soin DROP FOREIGN KEY FK_B55097A07750B79F');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE626B899279');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE62B03A8386');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE62896DBBDE');
        $this->addSql('DROP TABLE dossier_medical');
        $this->addSql('DROP INDEX IDX_B55097A07750B79F ON suivi_soin');
        $this->addSql('ALTER TABLE suivi_soin DROP dossier_medical_id');
    }
}
