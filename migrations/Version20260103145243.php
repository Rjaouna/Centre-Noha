<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260103145243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_PAIEMENT_FICHE_CLIENT');
        $this->addSql('ALTER TABLE paiement ADD reste NUMERIC(10, 2) NOT NULL, CHANGE prix_total prix_total NUMERIC(10, 2) NOT NULL, CHANGE montant_paye montant_paye NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E19EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E19EB6921');
        $this->addSql('ALTER TABLE paiement DROP reste, CHANGE prix_total prix_total INT NOT NULL, CHANGE montant_paye montant_paye INT NOT NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_PAIEMENT_FICHE_CLIENT FOREIGN KEY (client_id) REFERENCES fiche_client (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
