<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260109102952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE waiting_room_patient_prestation (waiting_room_id INT NOT NULL, patient_prestation_id INT NOT NULL, INDEX IDX_FB8DE2156CBEECA7 (waiting_room_id), INDEX IDX_FB8DE2155FC28BA0 (patient_prestation_id), PRIMARY KEY(waiting_room_id, patient_prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE waiting_room_patient_prestation ADD CONSTRAINT FK_FB8DE2156CBEECA7 FOREIGN KEY (waiting_room_id) REFERENCES waiting_room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE waiting_room_patient_prestation ADD CONSTRAINT FK_FB8DE2155FC28BA0 FOREIGN KEY (patient_prestation_id) REFERENCES patient_prestation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE waiting_room_patient_prestation DROP FOREIGN KEY FK_FB8DE2156CBEECA7');
        $this->addSql('ALTER TABLE waiting_room_patient_prestation DROP FOREIGN KEY FK_FB8DE2155FC28BA0');
        $this->addSql('DROP TABLE waiting_room_patient_prestation');
    }
}
