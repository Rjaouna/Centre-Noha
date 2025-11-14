<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114035542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_65E8AA0AB03A8386 ON rendez_vous (created_by_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A896DBBDE ON rendez_vous (updated_by_id)');
        $this->addSql('ALTER TABLE symptomes_generaux ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE symptomes_generaux ADD CONSTRAINT FK_6E32EC64B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE symptomes_generaux ADD CONSTRAINT FK_6E32EC64896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_6E32EC64B03A8386 ON symptomes_generaux (created_by_id)');
        $this->addSql('CREATE INDEX IDX_6E32EC64896DBBDE ON symptomes_generaux (updated_by_id)');
        $this->addSql('ALTER TABLE troubles_digestifs ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE troubles_digestifs ADD CONSTRAINT FK_CE76B045B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE troubles_digestifs ADD CONSTRAINT FK_CE76B045896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_CE76B045B03A8386 ON troubles_digestifs (created_by_id)');
        $this->addSql('CREATE INDEX IDX_CE76B045896DBBDE ON troubles_digestifs (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AB03A8386');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A896DBBDE');
        $this->addSql('DROP INDEX IDX_65E8AA0AB03A8386 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A896DBBDE ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE troubles_digestifs DROP FOREIGN KEY FK_CE76B045B03A8386');
        $this->addSql('ALTER TABLE troubles_digestifs DROP FOREIGN KEY FK_CE76B045896DBBDE');
        $this->addSql('DROP INDEX IDX_CE76B045B03A8386 ON troubles_digestifs');
        $this->addSql('DROP INDEX IDX_CE76B045896DBBDE ON troubles_digestifs');
        $this->addSql('ALTER TABLE troubles_digestifs DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE symptomes_generaux DROP FOREIGN KEY FK_6E32EC64B03A8386');
        $this->addSql('ALTER TABLE symptomes_generaux DROP FOREIGN KEY FK_6E32EC64896DBBDE');
        $this->addSql('DROP INDEX IDX_6E32EC64B03A8386 ON symptomes_generaux');
        $this->addSql('DROP INDEX IDX_6E32EC64896DBBDE ON symptomes_generaux');
        $this->addSql('ALTER TABLE symptomes_generaux DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
    }
}
