<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108212538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analyse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analyse_prescription (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, analyses JSON NOT NULL, comment LONGTEXT DEFAULT NULL, status VARCHAR(50) NOT NULL, prescribed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', pdf_path VARCHAR(255) DEFAULT NULL, received_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', compte_rendu LONGTEXT DEFAULT NULL, INDEX IDX_3F1ED366B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE arret_maladie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, motif VARCHAR(255) NOT NULL, debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', body LONGTEXT NOT NULL, duree INT NOT NULL, INDEX IDX_9401D0B26B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cabinet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(50) DEFAULT NULL, telephone VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certificat_medical (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(50) NOT NULL, contenu LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disponibilite (id INT AUTO_INCREMENT NOT NULL, praticien_id INT NOT NULL, jour_semaine SMALLINT NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, duree_creneau SMALLINT NOT NULL, actif TINYINT(1) DEFAULT NULL, INDEX IDX_2CBACE2F2391866B (praticien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dispositif_medical (id INT AUTO_INCREMENT NOT NULL, code_cnops VARCHAR(20) NOT NULL, code_anam VARCHAR(20) NOT NULL, libelle VARCHAR(255) DEFAULT NULL, tarif_reference DOUBLE PRECISION DEFAULT NULL, accord_prealable VARCHAR(50) NOT NULL, pieces_afournir LONGTEXT DEFAULT NULL, pieces_complementaires VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier_medical (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, autre VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3581EE626B899279 (patient_id), INDEX IDX_3581EE62B03A8386 (created_by_id), INDEX IDX_3581EE62896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_client (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, ville VARCHAR(50) NOT NULL, age DATE DEFAULT NULL, poids VARCHAR(255) DEFAULT NULL, telephone VARCHAR(10) NOT NULL, duree_maladie VARCHAR(255) DEFAULT NULL, type_maladie VARCHAR(50) NOT NULL, traitement VARCHAR(255) DEFAULT NULL, observation LONGTEXT DEFAULT NULL, is_open TINYINT(1) NOT NULL, is_consulted TINYINT(1) DEFAULT NULL, date_naissance DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', prenom VARCHAR(20) NOT NULL, cin VARCHAR(8) DEFAULT NULL, statut VARCHAR(20) DEFAULT NULL, heure_arrive TIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7158A982B03A8386 (created_by_id), INDEX IDX_7158A982896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hopital (id INT AUTO_INCREMENT NOT NULL, region VARCHAR(255) NOT NULL, delegation VARCHAR(255) DEFAULT NULL, commune VARCHAR(255) DEFAULT NULL, etablissement VARCHAR(255) DEFAULT NULL, categorie VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C53D045F19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indisponibilite (id INT AUTO_INCREMENT NOT NULL, praticien_id INT NOT NULL, date DATE NOT NULL, heure_debut TIME DEFAULT NULL, heure_fin TIME DEFAULT NULL, motif VARCHAR(255) DEFAULT NULL, actif TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8717036F2391866B (praticien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maladie_chronique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maladies_chroniques (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, diabetique VARCHAR(50) DEFAULT NULL, hypertendu VARCHAR(50) DEFAULT NULL, cholesterol VARCHAR(50) DEFAULT NULL, allergie_nasale VARCHAR(50) DEFAULT NULL, autre_maladie LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_66642C819EB6921 (client_id), INDEX IDX_66642C8B03A8386 (created_by_id), INDEX IDX_66642C8896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicine (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(13) NOT NULL, name VARCHAR(50) NOT NULL, dci VARCHAR(50) DEFAULT NULL, dosage VARCHAR(10) DEFAULT NULL, unite_dosage VARCHAR(10) NOT NULL, forme VARCHAR(50) DEFAULT NULL, presentation VARCHAR(50) DEFAULT NULL, ppv DOUBLE PRECISION NOT NULL, ph DOUBLE PRECISION DEFAULT NULL, is_generic TINYINT(1) DEFAULT NULL, taux_rembourssement SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, prix_total NUMERIC(10, 2) NOT NULL, montant_paye NUMERIC(10, 2) NOT NULL, reste NUMERIC(10, 2) NOT NULL, type_paiement VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B1DC7A1E19EB6921 (client_id), INDEX IDX_B1DC7A1EB03A8386 (created_by_id), INDEX IDX_B1DC7A1E896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radio_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, zone_corps VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radiologie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, motif LONGTEXT DEFAULT NULL, statut VARCHAR(10) NOT NULL, compte_rendu LONGTEXT DEFAULT NULL, fichier VARCHAR(255) DEFAULT NULL, prescription_at DATETIME NOT NULL, INDEX IDX_12FB57496B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE radiologie_radio_type (radiologie_id INT NOT NULL, radio_type_id INT NOT NULL, INDEX IDX_416143D1C69F936A (radiologie_id), INDEX IDX_416143D1FC2899D8 (radio_type_id), PRIMARY KEY(radiologie_id, radio_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recommendation_letter (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, hopital_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_851040F06B899279 (patient_id), INDEX IDX_851040F0B03A8386 (created_by_id), INDEX IDX_851040F0896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, praticien_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, date DATE NOT NULL, heure_debut TIME NOT NULL, heure_fin TIME NOT NULL, statut VARCHAR(50) NOT NULL, motif VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_65E8AA0A2391866B (praticien_id), INDEX IDX_65E8AA0A6B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_soin (id INT AUTO_INCREMENT NOT NULL, patient_id INT DEFAULT NULL, dossier_medical_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, diagnostic LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B55097A06B899279 (patient_id), INDEX IDX_B55097A07750B79F (dossier_medical_id), INDEX IDX_B55097A0B03A8386 (created_by_id), INDEX IDX_B55097A0896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_soin_medicine (suivi_soin_id INT NOT NULL, medicine_id INT NOT NULL, INDEX IDX_DEC53B98EA52D5CC (suivi_soin_id), INDEX IDX_DEC53B982F7D140A (medicine_id), PRIMARY KEY(suivi_soin_id, medicine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptome (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, category VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptomes_generaux (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, maux_tete VARCHAR(50) DEFAULT NULL, maux_nuque VARCHAR(50) DEFAULT NULL, insomnie VARCHAR(50) DEFAULT NULL, hemorroides VARCHAR(50) DEFAULT NULL, enuresie VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_6E32EC6419EB6921 (client_id), INDEX IDX_6E32EC64B03A8386 (created_by_id), INDEX IDX_6E32EC64896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_setting (id INT AUTO_INCREMENT NOT NULL, primary_color VARCHAR(7) NOT NULL, secondary_color VARCHAR(7) NOT NULL, success_color VARCHAR(7) NOT NULL, info_color VARCHAR(7) NOT NULL, danger_color VARCHAR(7) NOT NULL, warning_color VARCHAR(7) NOT NULL, light_color VARCHAR(7) NOT NULL, dark_color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement (id INT AUTO_INCREMENT NOT NULL, medicine_id INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_2A356D272F7D140A (medicine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement_symptome (traitement_id INT NOT NULL, symptome_id INT NOT NULL, INDEX IDX_E63AA887DDA344B6 (traitement_id), INDEX IDX_E63AA88712B83D77 (symptome_id), PRIMARY KEY(traitement_id, symptome_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement_maladie_chronique (traitement_id INT NOT NULL, maladie_chronique_id INT NOT NULL, INDEX IDX_F08EA090DDA344B6 (traitement_id), INDEX IDX_F08EA090A16109E0 (maladie_chronique_id), PRIMARY KEY(traitement_id, maladie_chronique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE troubles_digestifs (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, acidite_gastrique VARCHAR(50) DEFAULT NULL, constipation VARCHAR(50) DEFAULT NULL, diarrhee VARCHAR(50) DEFAULT NULL, aspect_selles VARCHAR(50) DEFAULT NULL, gaz VARCHAR(50) DEFAULT NULL, eructation VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_CE76B04519EB6921 (client_id), INDEX IDX_CE76B045B03A8386 (created_by_id), INDEX IDX_CE76B045896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_maladie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8D93D649B03A8386 (created_by_id), INDEX IDX_8D93D649896DBBDE (updated_by_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville_maroc (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, region VARCHAR(20) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE waiting_room (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, praticien_id INT DEFAULT NULL, rdv_id INT DEFAULT NULL, queue_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', has_rdv TINYINT(1) NOT NULL, statut VARCHAR(20) DEFAULT NULL, arrive_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', called_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', consultation_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', done_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', note VARCHAR(50) DEFAULT NULL, INDEX IDX_D2A43FD86B899279 (patient_id), INDEX IDX_D2A43FD82391866B (praticien_id), INDEX IDX_D2A43FD84CCE3F86 (rdv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analyse_prescription ADD CONSTRAINT FK_3F1ED366B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE arret_maladie ADD CONSTRAINT FK_9401D0B26B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE disponibilite ADD CONSTRAINT FK_2CBACE2F2391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE626B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE62B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE dossier_medical ADD CONSTRAINT FK_3581EE62896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fiche_client ADD CONSTRAINT FK_7158A982B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE fiche_client ADD CONSTRAINT FK_7158A982896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F19EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE indisponibilite ADD CONSTRAINT FK_8717036F2391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE maladies_chroniques ADD CONSTRAINT FK_66642C819EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE maladies_chroniques ADD CONSTRAINT FK_66642C8B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE maladies_chroniques ADD CONSTRAINT FK_66642C8896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E19EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1EB03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE radiologie ADD CONSTRAINT FK_12FB57496B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE radiologie_radio_type ADD CONSTRAINT FK_416143D1C69F936A FOREIGN KEY (radiologie_id) REFERENCES radiologie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE radiologie_radio_type ADD CONSTRAINT FK_416143D1FC2899D8 FOREIGN KEY (radio_type_id) REFERENCES radio_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recommendation_letter ADD CONSTRAINT FK_851040F06B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE recommendation_letter ADD CONSTRAINT FK_851040F0B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE recommendation_letter ADD CONSTRAINT FK_851040F0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A2391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE suivi_soin ADD CONSTRAINT FK_B55097A06B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE suivi_soin ADD CONSTRAINT FK_B55097A07750B79F FOREIGN KEY (dossier_medical_id) REFERENCES dossier_medical (id)');
        $this->addSql('ALTER TABLE suivi_soin ADD CONSTRAINT FK_B55097A0B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE suivi_soin ADD CONSTRAINT FK_B55097A0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE suivi_soin_medicine ADD CONSTRAINT FK_DEC53B98EA52D5CC FOREIGN KEY (suivi_soin_id) REFERENCES suivi_soin (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suivi_soin_medicine ADD CONSTRAINT FK_DEC53B982F7D140A FOREIGN KEY (medicine_id) REFERENCES medicine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE symptomes_generaux ADD CONSTRAINT FK_6E32EC6419EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE symptomes_generaux ADD CONSTRAINT FK_6E32EC64B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE symptomes_generaux ADD CONSTRAINT FK_6E32EC64896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D272F7D140A FOREIGN KEY (medicine_id) REFERENCES medicine (id)');
        $this->addSql('ALTER TABLE traitement_symptome ADD CONSTRAINT FK_E63AA887DDA344B6 FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_symptome ADD CONSTRAINT FK_E63AA88712B83D77 FOREIGN KEY (symptome_id) REFERENCES symptome (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_maladie_chronique ADD CONSTRAINT FK_F08EA090DDA344B6 FOREIGN KEY (traitement_id) REFERENCES traitement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE traitement_maladie_chronique ADD CONSTRAINT FK_F08EA090A16109E0 FOREIGN KEY (maladie_chronique_id) REFERENCES maladie_chronique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE troubles_digestifs ADD CONSTRAINT FK_CE76B04519EB6921 FOREIGN KEY (client_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE troubles_digestifs ADD CONSTRAINT FK_CE76B045B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE troubles_digestifs ADD CONSTRAINT FK_CE76B045896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649896DBBDE FOREIGN KEY (updated_by_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE waiting_room ADD CONSTRAINT FK_D2A43FD86B899279 FOREIGN KEY (patient_id) REFERENCES fiche_client (id)');
        $this->addSql('ALTER TABLE waiting_room ADD CONSTRAINT FK_D2A43FD82391866B FOREIGN KEY (praticien_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE waiting_room ADD CONSTRAINT FK_D2A43FD84CCE3F86 FOREIGN KEY (rdv_id) REFERENCES rendez_vous (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE analyse_prescription DROP FOREIGN KEY FK_3F1ED366B899279');
        $this->addSql('ALTER TABLE arret_maladie DROP FOREIGN KEY FK_9401D0B26B899279');
        $this->addSql('ALTER TABLE disponibilite DROP FOREIGN KEY FK_2CBACE2F2391866B');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE626B899279');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE62B03A8386');
        $this->addSql('ALTER TABLE dossier_medical DROP FOREIGN KEY FK_3581EE62896DBBDE');
        $this->addSql('ALTER TABLE fiche_client DROP FOREIGN KEY FK_7158A982B03A8386');
        $this->addSql('ALTER TABLE fiche_client DROP FOREIGN KEY FK_7158A982896DBBDE');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F19EB6921');
        $this->addSql('ALTER TABLE indisponibilite DROP FOREIGN KEY FK_8717036F2391866B');
        $this->addSql('ALTER TABLE maladies_chroniques DROP FOREIGN KEY FK_66642C819EB6921');
        $this->addSql('ALTER TABLE maladies_chroniques DROP FOREIGN KEY FK_66642C8B03A8386');
        $this->addSql('ALTER TABLE maladies_chroniques DROP FOREIGN KEY FK_66642C8896DBBDE');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E19EB6921');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1EB03A8386');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E896DBBDE');
        $this->addSql('ALTER TABLE radiologie DROP FOREIGN KEY FK_12FB57496B899279');
        $this->addSql('ALTER TABLE radiologie_radio_type DROP FOREIGN KEY FK_416143D1C69F936A');
        $this->addSql('ALTER TABLE radiologie_radio_type DROP FOREIGN KEY FK_416143D1FC2899D8');
        $this->addSql('ALTER TABLE recommendation_letter DROP FOREIGN KEY FK_851040F06B899279');
        $this->addSql('ALTER TABLE recommendation_letter DROP FOREIGN KEY FK_851040F0B03A8386');
        $this->addSql('ALTER TABLE recommendation_letter DROP FOREIGN KEY FK_851040F0896DBBDE');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A2391866B');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE suivi_soin DROP FOREIGN KEY FK_B55097A06B899279');
        $this->addSql('ALTER TABLE suivi_soin DROP FOREIGN KEY FK_B55097A07750B79F');
        $this->addSql('ALTER TABLE suivi_soin DROP FOREIGN KEY FK_B55097A0B03A8386');
        $this->addSql('ALTER TABLE suivi_soin DROP FOREIGN KEY FK_B55097A0896DBBDE');
        $this->addSql('ALTER TABLE suivi_soin_medicine DROP FOREIGN KEY FK_DEC53B98EA52D5CC');
        $this->addSql('ALTER TABLE suivi_soin_medicine DROP FOREIGN KEY FK_DEC53B982F7D140A');
        $this->addSql('ALTER TABLE symptomes_generaux DROP FOREIGN KEY FK_6E32EC6419EB6921');
        $this->addSql('ALTER TABLE symptomes_generaux DROP FOREIGN KEY FK_6E32EC64B03A8386');
        $this->addSql('ALTER TABLE symptomes_generaux DROP FOREIGN KEY FK_6E32EC64896DBBDE');
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D272F7D140A');
        $this->addSql('ALTER TABLE traitement_symptome DROP FOREIGN KEY FK_E63AA887DDA344B6');
        $this->addSql('ALTER TABLE traitement_symptome DROP FOREIGN KEY FK_E63AA88712B83D77');
        $this->addSql('ALTER TABLE traitement_maladie_chronique DROP FOREIGN KEY FK_F08EA090DDA344B6');
        $this->addSql('ALTER TABLE traitement_maladie_chronique DROP FOREIGN KEY FK_F08EA090A16109E0');
        $this->addSql('ALTER TABLE troubles_digestifs DROP FOREIGN KEY FK_CE76B04519EB6921');
        $this->addSql('ALTER TABLE troubles_digestifs DROP FOREIGN KEY FK_CE76B045B03A8386');
        $this->addSql('ALTER TABLE troubles_digestifs DROP FOREIGN KEY FK_CE76B045896DBBDE');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649896DBBDE');
        $this->addSql('ALTER TABLE waiting_room DROP FOREIGN KEY FK_D2A43FD86B899279');
        $this->addSql('ALTER TABLE waiting_room DROP FOREIGN KEY FK_D2A43FD82391866B');
        $this->addSql('ALTER TABLE waiting_room DROP FOREIGN KEY FK_D2A43FD84CCE3F86');
        $this->addSql('DROP TABLE analyse');
        $this->addSql('DROP TABLE analyse_prescription');
        $this->addSql('DROP TABLE arret_maladie');
        $this->addSql('DROP TABLE cabinet');
        $this->addSql('DROP TABLE certificat_medical');
        $this->addSql('DROP TABLE disponibilite');
        $this->addSql('DROP TABLE dispositif_medical');
        $this->addSql('DROP TABLE dossier_medical');
        $this->addSql('DROP TABLE fiche_client');
        $this->addSql('DROP TABLE hopital');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE indisponibilite');
        $this->addSql('DROP TABLE maladie_chronique');
        $this->addSql('DROP TABLE maladies_chroniques');
        $this->addSql('DROP TABLE medicine');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE radio_type');
        $this->addSql('DROP TABLE radiologie');
        $this->addSql('DROP TABLE radiologie_radio_type');
        $this->addSql('DROP TABLE recommendation_letter');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE suivi_soin');
        $this->addSql('DROP TABLE suivi_soin_medicine');
        $this->addSql('DROP TABLE symptome');
        $this->addSql('DROP TABLE symptomes_generaux');
        $this->addSql('DROP TABLE theme_setting');
        $this->addSql('DROP TABLE traitement');
        $this->addSql('DROP TABLE traitement_symptome');
        $this->addSql('DROP TABLE traitement_maladie_chronique');
        $this->addSql('DROP TABLE troubles_digestifs');
        $this->addSql('DROP TABLE type_maladie');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE ville_maroc');
        $this->addSql('DROP TABLE waiting_room');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
