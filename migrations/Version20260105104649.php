<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105104649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE radio_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, zone_corps VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radiologie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, motif LONGTEXT DEFAULT NULL, statut VARCHAR(10) NOT NULL, compte_rendu LONGTEXT DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, prescription_at DATETIME NOT NULL, INDEX IDX_12FB57496B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radiologie_radio_type (radiologie_id INT NOT NULL, radio_type_id INT NOT NULL, INDEX IDX_416143D1C69F936A (radiologie_id), INDEX IDX_416143D1FC2899D8 (radio_type_id), PRIMARY KEY(radiologie_id, radio_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE radiologie ADD CONSTRAINT FK_12FB57496B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE radiologie_radio_type ADD CONSTRAINT FK_416143D1C69F936A FOREIGN KEY (radiologie_id) REFERENCES radiologie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE radiologie_radio_type ADD CONSTRAINT FK_416143D1FC2899D8 FOREIGN KEY (radio_type_id) REFERENCES radio_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE radiologie DROP FOREIGN KEY FK_12FB57496B899279');
        $this->addSql('ALTER TABLE radiologie_radio_type DROP FOREIGN KEY FK_416143D1C69F936A');
        $this->addSql('ALTER TABLE radiologie_radio_type DROP FOREIGN KEY FK_416143D1FC2899D8');
        $this->addSql('DROP TABLE radio_type');
        $this->addSql('DROP TABLE radiologie');
        $this->addSql('DROP TABLE radiologie_radio_type');
    }
}
