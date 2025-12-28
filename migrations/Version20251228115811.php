<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228115811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arret_maladie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, motif VARCHAR(255) NOT NULL, debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', body LONGTEXT NOT NULL, duree INT NOT NULL, INDEX IDX_9401D0B26B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE arret_maladie ADD CONSTRAINT FK_9401D0B26B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE arret_maladie DROP FOREIGN KEY FK_9401D0B26B899279');
        $this->addSql('DROP TABLE arret_maladie');
    }
}
