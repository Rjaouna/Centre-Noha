<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260103142314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix paiement column types and client FK';
    }

    public function up(Schema $schema): void
    {
        // Modifier uniquement les colonnes existantes
        $this->addSql('ALTER TABLE paiement 
            CHANGE prix_total prix_total INT NOT NULL,
            CHANGE montant_paye montant_paye INT NOT NULL,
            CHANGE type_paiement type_paiement VARCHAR(30) NOT NULL,
            CHANGE client_id client_id INT NOT NULL
        ');

        // Ajouter la FK propre (si elle existe déjà, MySQL refusera → OK)
        $this->addSql('
            ALTER TABLE paiement
            ADD CONSTRAINT FK_PAIEMENT_FICHE_CLIENT
            FOREIGN KEY (client_id) REFERENCES fiche_client (id)
            ON DELETE CASCADE
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE paiement 
            CHANGE prix_total prix_total VARCHAR(50) NOT NULL,
            CHANGE montant_paye montant_paye VARCHAR(50) NOT NULL,
            CHANGE type_paiement type_paiement VARCHAR(50) NOT NULL,
            CHANGE client_id client_id INT DEFAULT NULL
        ');

        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_PAIEMENT_FICHE_CLIENT');
    }
}
