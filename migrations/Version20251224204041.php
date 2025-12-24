<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251224204041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE traitement (id INT AUTO_INCREMENT NOT NULL, medicine_id INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_2A356D272F7D140A (medicine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement_symptome (traitement_id INT NOT NULL, symptome_id INT NOT NULL, INDEX IDX_E63AA887DDA344B6 (traitement_id), INDEX IDX_E63AA88712B83D77 (symptome_id), PRIMARY KEY(traitement_id, symptome_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement_maladie_chronique (traitement_id INT NOT NULL, maladie_chronique_id INT NOT NULL, INDEX IDX_F08EA090DDA344B6 (traitement_id), INDEX IDX_F08EA090A16109E0 (maladie_chronique_id), PRIMARY KEY(traitement_id, maladie_chronique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D272F7D140A FOREIGN KEY (medicine_id) REFERENCES medicine (id)');
        $this->addSql('ALTER TABLE traitement_symptome ADD CONSTRAINT FK_E63AA887DDA344B6 FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_symptome ADD CONSTRAINT FK_E63AA88712B83D77 FOREIGN KEY (symptome_id) REFERENCES symptome (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_maladie_chronique ADD CONSTRAINT FK_F08EA090DDA344B6 FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_maladie_chronique ADD CONSTRAINT FK_F08EA090A16109E0 FOREIGN KEY (maladie_chronique_id) REFERENCES maladie_chronique (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D272F7D140A');
        $this->addSql('ALTER TABLE traitement_symptome DROP FOREIGN KEY FK_E63AA887DDA344B6');
        $this->addSql('ALTER TABLE traitement_symptome DROP FOREIGN KEY FK_E63AA88712B83D77');
        $this->addSql('ALTER TABLE traitement_maladie_chronique DROP FOREIGN KEY FK_F08EA090DDA344B6');
        $this->addSql('ALTER TABLE traitement_maladie_chronique DROP FOREIGN KEY FK_F08EA090A16109E0');
        $this->addSql('DROP TABLE traitement');
        $this->addSql('DROP TABLE traitement_symptome');
        $this->addSql('DROP TABLE traitement_maladie_chronique');
    }
}
