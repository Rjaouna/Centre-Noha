<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108151335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE waiting_room (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, praticien_id INT DEFAULT NULL, rdv_id INT DEFAULT NULL, queue_date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', has_rdv TINYINT(1) NOT NULL, statut VARCHAR(20) DEFAULT NULL, arrive_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', called_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', consultation_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', done_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D2A43FD86B899279 (patient_id), INDEX IDX_D2A43FD82391866B (praticien_id), INDEX IDX_D2A43FD84CCE3F86 (rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE waiting_room ADD CONSTRAINT FK_D2A43FD86B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE waiting_room ADD CONSTRAINT FK_D2A43FD82391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE waiting_room ADD CONSTRAINT FK_D2A43FD84CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE waiting_room DROP FOREIGN KEY FK_D2A43FD86B899279');
        $this->addSql('ALTER TABLE waiting_room DROP FOREIGN KEY FK_D2A43FD82391866B');
        $this->addSql('ALTER TABLE waiting_room DROP FOREIGN KEY FK_D2A43FD84CCE3F86');
        $this->addSql('DROP TABLE waiting_room');
    }
}
