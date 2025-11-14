<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114022034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE maladies_chroniques (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, diabetique VARCHAR(50) DEFAULT NULL, hypertendu VARCHAR(50) DEFAULT NULL, cholesterol VARCHAR(50) DEFAULT NULL, allergie_nasale VARCHAR(50) DEFAULT NULL, autre_maladie LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_66642C819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE maladies_chroniques ADD CONSTRAINT FK_66642C819EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maladies_chroniques DROP FOREIGN KEY FK_66642C819EB6921');
        $this->addSql('DROP TABLE maladies_chroniques');
    }
}
