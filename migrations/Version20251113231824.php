<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251113231824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE troubles_digestifs (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, acidite_gastrique VARCHAR(50) DEFAULT NULL, constipation VARCHAR(50) DEFAULT NULL, diarrhee VARCHAR(50) DEFAULT NULL, aspect_selles VARCHAR(50) DEFAULT NULL, gaz VARCHAR(50) DEFAULT NULL, eructation VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_CE76B04519EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE troubles_digestifs ADD CONSTRAINT FK_CE76B04519EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE fiche_client CHANGE age age SMALLINT DEFAULT NULL, CHANGE poids poids SMALLINT DEFAULT NULL, CHANGE duree_maladie duree_maladie SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE troubles_digestifs DROP FOREIGN KEY FK_CE76B04519EB6921');
        $this->addSql('DROP TABLE troubles_digestifs');
        $this->addSql('ALTER TABLE fiche_client CHANGE age age SMALLINT NOT NULL, CHANGE poids poids SMALLINT NOT NULL, CHANGE duree_maladie duree_maladie SMALLINT NOT NULL');
    }
}
