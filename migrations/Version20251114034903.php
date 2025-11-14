<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114034903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maladies_chroniques ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE maladies_chroniques ADD CONSTRAINT FK_66642C8B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE maladies_chroniques ADD CONSTRAINT FK_66642C8896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_66642C8B03A8386 ON maladies_chroniques (created_by_id)');
        $this->addSql('CREATE INDEX IDX_66642C8896DBBDE ON maladies_chroniques (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maladies_chroniques DROP FOREIGN KEY FK_66642C8B03A8386');
        $this->addSql('ALTER TABLE maladies_chroniques DROP FOREIGN KEY FK_66642C8896DBBDE');
        $this->addSql('DROP INDEX IDX_66642C8B03A8386 ON maladies_chroniques');
        $this->addSql('DROP INDEX IDX_66642C8896DBBDE ON maladies_chroniques');
        $this->addSql('ALTER TABLE maladies_chroniques DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
    }
}
