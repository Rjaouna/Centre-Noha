<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260102174001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, praticien_id INT NOT NULL, jour_semaine SMALLINT NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, duree_creneau SMALLINT NOT NULL, actif TINYINT(1) DEFAULT NULL, INDEX IDX_2CBACE2F2391866B (praticien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indisponibilite (id INT AUTO_INCREMENT NOT NULL, praticien_id INT NOT NULL, date DATE NOT NULL, heure_debut TIME DEFAULT NULL, heure_fin TIME DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, actif TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8717036F2391866B (praticien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F2391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE indisponibilite ADD CONSTRAINT FK_8717036F2391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A19EB6921');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A896DBBDE');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0AB03A8386');
        $this->addSql('DROP INDEX IDX_65E8AA0A19EB6921 ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A896DBBDE ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0AB03A8386 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD praticien_id INT DEFAULT NULL, ADD patient_id INT DEFAULT NULL, ADD date DATE NOT NULL, ADD heure_debut TIME NOT NULL, ADD heure_fin TIME NOT NULL, ADD expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP client_id, DROP created_by_id, DROP updated_by_id, DROP date_rdv_at, DROP commentaire, DROP updated_at, CHANGE statut statut VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A2391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A2391866B ON rendez_vous (praticien_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A6B899279 ON rendez_vous (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F2391866B');
        $this->addSql('ALTER TABLE indisponibilite DROP FOREIGN KEY FK_8717036F2391866B');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE indisponibilite');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A2391866B');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('DROP INDEX IDX_65E8AA0A2391866B ON rendez_vous');
        $this->addSql('DROP INDEX IDX_65E8AA0A6B899279 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous ADD client_id INT DEFAULT NULL, ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD date_rdv_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD commentaire LONGTEXT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP praticien_id, DROP patient_id, DROP date, DROP heure_debut, DROP heure_fin, DROP expires_at, CHANGE statut statut VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A19EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_65E8AA0A19EB6921 ON rendez_vous (client_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A896DBBDE ON rendez_vous (updated_by_id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0AB03A8386 ON rendez_vous (created_by_id)');
    }
}
