<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128214306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE medicine (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(13) NOT NULL, name VARCHAR(50) NOT NULL, dci VARCHAR(50) DEFAULT NULL, dosage VARCHAR(10) DEFAULT NULL, unite_dosage VARCHAR(10) NOT NULL, forme VARCHAR(50) DEFAULT NULL, presentation VARCHAR(50) DEFAULT NULL, ppv DOUBLE PRECISION NOT NULL, ph DOUBLE PRECISION DEFAULT NULL, is_generic TINYINT(1) DEFAULT NULL, taux_rembourssement SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_soin_medicine (suivi_soin_id INT NOT NULL, medicine_id INT NOT NULL, INDEX IDX_DEC53B98EA52D5CC (suivi_soin_id), INDEX IDX_DEC53B982F7D140A (medicine_id), PRIMARY KEY(suivi_soin_id, medicine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suivi_soin_medicine ADD CONSTRAINT FK_DEC53B98EA52D5CC FOREIGN KEY (suivi_soin_id) REFERENCES suivi_soin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suivi_soin_medicine ADD CONSTRAINT FK_DEC53B982F7D140A FOREIGN KEY (medicine_id) REFERENCES medicine (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_soin_medicine DROP FOREIGN KEY FK_DEC53B98EA52D5CC');
        $this->addSql('ALTER TABLE suivi_soin_medicine DROP FOREIGN KEY FK_DEC53B982F7D140A');
        $this->addSql('DROP TABLE medicine');
        $this->addSql('DROP TABLE suivi_soin_medicine');
    }
}
