<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260109003800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE patient_prestation (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, prestation_id INT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E70829426B899279 (patient_id), INDEX IDX_E70829429E45C554 (prestation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_prestation ADD CONSTRAINT FK_E70829426B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE patient_prestation ADD CONSTRAINT FK_E70829429E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation_price (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_prestation DROP FOREIGN KEY FK_E70829426B899279');
        $this->addSql('ALTER TABLE patient_prestation DROP FOREIGN KEY FK_E70829429E45C554');
        $this->addSql('DROP TABLE patient_prestation');
    }
}
