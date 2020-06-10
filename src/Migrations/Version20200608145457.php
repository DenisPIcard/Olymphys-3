<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608145457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE fichessecur');
        $this->addSql('DROP TABLE memoires');
        $this->addSql('DROP TABLE memoiresinter');
        $this->addSql('DROP TABLE presentation');
        $this->addSql('DROP TABLE resumes');
        $this->addSql('ALTER TABLE adminsite CHANGE datelimite_cia datelimite_cia DATETIME DEFAULT NULL, CHANGE datelimite_nat datelimite_nat DATETIME DEFAULT NULL, CHANGE session session VARCHAR(255) DEFAULT NULL, CHANGE concours_cia concours_cia DATETIME DEFAULT NULL, CHANGE concours_cn concours_cn DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE cadeaux CHANGE contenu contenu VARCHAR(255) DEFAULT NULL, CHANGE fournisseur fournisseur VARCHAR(255) DEFAULT NULL, CHANGE montant montant NUMERIC(6, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE centrescia CHANGE centre centre VARCHAR(255) DEFAULT NULL, CHANGE id_edition id_edition INT DEFAULT NULL, CHANGE id_orga1 id_orga1 INT DEFAULT NULL, CHANGE id_orga2 id_orga2 INT DEFAULT NULL, CHANGE id_jurycia id_jurycia INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classement CHANGE niveau niveau VARCHAR(255) DEFAULT NULL, CHANGE montant montant NUMERIC(3, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE edition CHANGE ed ed VARCHAR(255) DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL, CHANGE edition edition INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE lieu lieu VARCHAR(255) DEFAULT NULL, CHANGE datelimite_cia datelimite_cia DATETIME DEFAULT NULL, CHANGE datelimite_nat datelimite_nat DATETIME DEFAULT NULL, CHANGE date_ouverture_site date_ouverture_site DATETIME DEFAULT NULL, CHANGE concours_cia concours_cia DATETIME DEFAULT NULL, CHANGE concours_cn concours_cn DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE eleves CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE classe classe VARCHAR(255) DEFAULT NULL, CHANGE lettre_equipe lettre_equipe VARCHAR(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE elevesinter CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE classe classe VARCHAR(255) DEFAULT NULL, CHANGE genre genre VARCHAR(1) DEFAULT NULL, CHANGE courriel courriel VARCHAR(60) DEFAULT NULL, CHANGE numsite numsite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipes CHANGE visite_id visite_id INT DEFAULT NULL, CHANGE cadeau_id cadeau_id INT DEFAULT NULL, CHANGE phrases_id phrases_id INT DEFAULT NULL, CHANGE liaison_id liaison_id INT DEFAULT NULL, CHANGE prix_id prix_id INT DEFAULT NULL, CHANGE infoequipe_id infoequipe_id INT DEFAULT NULL, CHANGE heure heure VARCHAR(255) DEFAULT NULL, CHANGE total total SMALLINT DEFAULT NULL, CHANGE classement classement VARCHAR(255) DEFAULT NULL, CHANGE rang rang SMALLINT DEFAULT NULL, CHANGE ordre ordre SMALLINT DEFAULT NULL, CHANGE salle salle VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equipesadmin CHANGE lettre lettre VARCHAR(1) DEFAULT NULL, CHANGE numero numero SMALLINT DEFAULT NULL, CHANGE selectionnee selectionnee TINYINT(1) DEFAULT NULL, CHANGE titreProjet titreProjet VARCHAR(255) DEFAULT NULL, CHANGE nom_lycee nom_lycee VARCHAR(255) DEFAULT NULL, CHANGE denomination_lycee denomination_lycee VARCHAR(255) DEFAULT NULL, CHANGE lycee_localite lycee_localite VARCHAR(255) DEFAULT NULL, CHANGE lycee_academie lycee_academie VARCHAR(255) DEFAULT NULL, CHANGE id_prof1 id_prof1 INT DEFAULT NULL, CHANGE id_prof2 id_prof2 INT DEFAULT NULL, CHANGE prenom_prof1 prenom_prof1 VARCHAR(255) DEFAULT NULL, CHANGE nom_prof1 nom_prof1 VARCHAR(255) DEFAULT NULL, CHANGE prenom_prof2 prenom_prof2 VARCHAR(255) DEFAULT NULL, CHANGE nom_prof2 nom_prof2 VARCHAR(255) DEFAULT NULL, CHANGE rne rne VARCHAR(255) DEFAULT NULL, CHANGE rne_id rne_id INT DEFAULT NULL, CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE centrecia_id centrecia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipesadmin ADD CONSTRAINT FK_A26C5EB150D8F5D4 FOREIGN KEY (centrecia_id) REFERENCES centrescia (id)');
        $this->addSql('ALTER TABLE equipesadmin ADD CONSTRAINT FK_A26C5EB144D2DF56 FOREIGN KEY (rne_id) REFERENCES rne (id)');
        $this->addSql('ALTER TABLE equipesadmin ADD CONSTRAINT FK_A26C5EB174281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('CREATE INDEX IDX_A26C5EB150D8F5D4 ON equipesadmin (centrecia_id)');
        $this->addSql('CREATE INDEX IDX_A26C5EB174281A5E ON equipesadmin (edition_id)');
        $this->addSql('ALTER TABLE equipesadmin RENAME INDEX rne_id TO IDX_A26C5EB144D2DF56');
        $this->addSql('ALTER TABLE fichiersequipes CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE fichier fichier VARCHAR(255) DEFAULT NULL, CHANGE typefichier typefichier INT DEFAULT NULL, CHANGE national national TINYINT(1) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE jures CHANGE A A SMALLINT DEFAULT NULL, CHANGE B B SMALLINT DEFAULT NULL, CHANGE C C SMALLINT DEFAULT NULL, CHANGE D D SMALLINT DEFAULT NULL, CHANGE E E SMALLINT DEFAULT NULL, CHANGE F F SMALLINT DEFAULT NULL, CHANGE G G SMALLINT DEFAULT NULL, CHANGE H H SMALLINT DEFAULT NULL, CHANGE I I SMALLINT DEFAULT NULL, CHANGE J J SMALLINT DEFAULT NULL, CHANGE K K SMALLINT DEFAULT NULL, CHANGE L L SMALLINT DEFAULT NULL, CHANGE M M SMALLINT DEFAULT NULL, CHANGE N N SMALLINT DEFAULT NULL, CHANGE O O SMALLINT DEFAULT NULL, CHANGE P P SMALLINT DEFAULT NULL, CHANGE Q Q SMALLINT DEFAULT NULL, CHANGE R R SMALLINT DEFAULT NULL, CHANGE S S SMALLINT DEFAULT NULL, CHANGE T T SMALLINT DEFAULT NULL, CHANGE U U SMALLINT DEFAULT NULL, CHANGE V V SMALLINT DEFAULT NULL, CHANGE W W SMALLINT DEFAULT NULL, CHANGE X X SMALLINT DEFAULT NULL, CHANGE Y Y SMALLINT DEFAULT NULL, CHANGE Z Z SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE liaison CHANGE liaison liaison VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE notes CHANGE ecrit ecrit SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68C6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipes (id)');
        $this->addSql('ALTER TABLE notes ADD CONSTRAINT FK_11BA68CFFAFF81B FOREIGN KEY (jure_id) REFERENCES jures (id)');
        $this->addSql('ALTER TABLE orgacia CHANGE centre_id centre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orgacia ADD CONSTRAINT FK_CAEB80DA463CD7C3 FOREIGN KEY (centre_id) REFERENCES centrescia (id)');
        $this->addSql('ALTER TABLE palmares CHANGE a_id a_id INT DEFAULT NULL, CHANGE b_id b_id INT DEFAULT NULL, CHANGE c_id c_id INT DEFAULT NULL, CHANGE d_id d_id INT DEFAULT NULL, CHANGE e_id e_id INT DEFAULT NULL, CHANGE f_id f_id INT DEFAULT NULL, CHANGE g_id g_id INT DEFAULT NULL, CHANGE h_id h_id INT DEFAULT NULL, CHANGE i_id i_id INT DEFAULT NULL, CHANGE j_id j_id INT DEFAULT NULL, CHANGE k_id k_id INT DEFAULT NULL, CHANGE l_id l_id INT DEFAULT NULL, CHANGE m_id m_id INT DEFAULT NULL, CHANGE n_id n_id INT DEFAULT NULL, CHANGE o_id o_id INT DEFAULT NULL, CHANGE p_id p_id INT DEFAULT NULL, CHANGE q_id q_id INT DEFAULT NULL, CHANGE r_id r_id INT DEFAULT NULL, CHANGE s_id s_id INT DEFAULT NULL, CHANGE t_id t_id INT DEFAULT NULL, CHANGE u_id u_id INT DEFAULT NULL, CHANGE v_id v_id INT DEFAULT NULL, CHANGE w_id w_id INT DEFAULT NULL, CHANGE x_id x_id INT DEFAULT NULL, CHANGE y_id y_id INT DEFAULT NULL, CHANGE z_id z_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6493BDE5358 FOREIGN KEY (a_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649296BFCB6 FOREIGN KEY (b_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE64991D79BD3 FOREIGN KEY (c_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649C00A36A FOREIGN KEY (d_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649B4BCC40F FOREIGN KEY (e_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649A6096BE1 FOREIGN KEY (f_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6491EB50C84 FOREIGN KEY (g_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE64946D61CD2 FOREIGN KEY (h_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649FE6A7BB7 FOREIGN KEY (i_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649ECDFD459 FOREIGN KEY (j_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6495463B33C FOREIGN KEY (k_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649C9B48B85 FOREIGN KEY (l_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6497108ECE0 FOREIGN KEY (m_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE64963BD430E FOREIGN KEY (n_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649DB01246B FOREIGN KEY (o_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649D37B63A2 FOREIGN KEY (p_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6496BC704C7 FOREIGN KEY (q_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6497972AB29 FOREIGN KEY (r_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649C1CECC4C FOREIGN KEY (s_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6495C19F4F5 FOREIGN KEY (t_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649E4A59390 FOREIGN KEY (u_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649F6103C7E FOREIGN KEY (v_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE6494EAC5B1B FOREIGN KEY (w_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE64916CF4B4D FOREIGN KEY (x_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649AE732C28 FOREIGN KEY (y_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE palmares ADD CONSTRAINT FK_FF4EE649BCC683C6 FOREIGN KEY (z_id) REFERENCES prix (id)');
        $this->addSql('ALTER TABLE photoscn CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE thumb_id thumb_id INT DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE coment coment VARCHAR(125) DEFAULT NULL');
        $this->addSql('ALTER TABLE photoscn RENAME INDEX equipe_id TO IDX_55F3F29C6D861B89');
        $this->addSql('ALTER TABLE photoscn RENAME INDEX edition_id TO IDX_55F3F29C74281A5E');
        $this->addSql('ALTER TABLE photoscn RENAME INDEX thumb_id TO UNIQ_55F3F29CC7034EA5');
        $this->addSql('ALTER TABLE photoscnthumb CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE photosinter CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE thumb_id thumb_id INT DEFAULT NULL, CHANGE coment coment VARCHAR(125) DEFAULT NULL');
        $this->addSql('ALTER TABLE photosinter ADD CONSTRAINT FK_B6BFEF956D861B89 FOREIGN KEY (equipe_id) REFERENCES equipesadmin (id)');
        $this->addSql('ALTER TABLE photosinter ADD CONSTRAINT FK_B6BFEF9574281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE photosinter ADD CONSTRAINT FK_B6BFEF95C7034EA5 FOREIGN KEY (thumb_id) REFERENCES photosinterthumb (id)');
        $this->addSql('ALTER TABLE photosinter RENAME INDEX thumb_id TO UNIQ_B6BFEF95C7034EA5');
        $this->addSql('ALTER TABLE photosinterthumb CHANGE photo photo VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE prix CHANGE classement classement VARCHAR(255) DEFAULT NULL, CHANGE prix prix VARCHAR(255) DEFAULT NULL, CHANGE voix voix VARCHAR(255) DEFAULT NULL, CHANGE intervenant intervenant VARCHAR(255) DEFAULT NULL, CHANGE remis_par remis_par VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE rne CHANGE rne rne VARCHAR(10) NOT NULL, CHANGE nature nature VARCHAR(255) DEFAULT NULL, CHANGE sigle sigle VARCHAR(11) DEFAULT NULL, CHANGE commune commune VARCHAR(255) DEFAULT NULL, CHANGE academie academie VARCHAR(255) DEFAULT NULL, CHANGE pays pays VARCHAR(255) DEFAULT NULL, CHANGE departement departement VARCHAR(255) DEFAULT NULL, CHANGE denomination_principale denomination_principale VARCHAR(255) DEFAULT NULL, CHANGE appellation_officielle appellation_officielle VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE boite_postale boite_postale VARCHAR(255) DEFAULT NULL, CHANGE code_postal code_postal VARCHAR(255) DEFAULT NULL, CHANGE acheminement acheminement VARCHAR(255) DEFAULT NULL, CHANGE coordonnee_x coordonnee_x NUMERIC(10, 1) DEFAULT NULL, CHANGE coordonnee_y coordonnee_y NUMERIC(10, 1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6E92B6D26E92B6D2 ON rne (rne)');
        $this->addSql('ALTER TABLE totalequipes CHANGE numero_equipe numero_equipe SMALLINT DEFAULT NULL, CHANGE lettre_equipe lettre_equipe VARCHAR(1) DEFAULT NULL, CHANGE nom_equipe nom_equipe VARCHAR(255) DEFAULT NULL, CHANGE nom_lycee nom_lycee VARCHAR(255) DEFAULT NULL, CHANGE denomination_lycee denomination_lycee VARCHAR(255) DEFAULT NULL, CHANGE lycee_localite lycee_localite VARCHAR(255) DEFAULT NULL, CHANGE lycee_academie lycee_academie VARCHAR(255) DEFAULT NULL, CHANGE id_prof1 id_prof1 SMALLINT DEFAULT NULL, CHANGE id_prof2 id_prof2 SMALLINT DEFAULT NULL, CHANGE prenom_prof1 prenom_prof1 VARCHAR(255) DEFAULT NULL, CHANGE nom_prof1 nom_prof1 VARCHAR(255) DEFAULT NULL, CHANGE prenom_prof2 prenom_prof2 VARCHAR(255) DEFAULT NULL, CHANGE nom_prof2 nom_prof2 VARCHAR(255) DEFAULT NULL, CHANGE rne rne VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47D0A6CF31B17EF3 ON totalequipes (id_prof1)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47D0A6CFA8B82F49 ON totalequipes (id_prof2)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_47D0A6CF6E92B6D2 ON totalequipes (rne)');
        $this->addSql('ALTER TABLE user CHANGE is_active is_active TINYINT(1) DEFAULT NULL, CHANGE token token VARCHAR(255) DEFAULT NULL, CHANGE password_requested_at password_requested_at DATETIME DEFAULT NULL, CHANGE rne rne VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE code code VARCHAR(11) DEFAULT NULL, CHANGE civilite civilite VARCHAR(15) DEFAULT NULL, CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE prenom prenom VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(15) DEFAULT NULL, CHANGE centre_id centre_id INT DEFAULT NULL, CHANGE createdAt createdAt DATETIME DEFAULT NULL, CHANGE updatedAt updatedAt DATETIME DEFAULT NULL, CHANGE lastVisit lastVisit DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649463CD7C3 FOREIGN KEY (centre_id) REFERENCES centrescia (id)');
        $this->addSql('ALTER TABLE visites CHANGE intitule intitule VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_access_token CHANGE user_identifier user_identifier VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_authorization_code CHANGE user_identifier user_identifier VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_client CHANGE secret secret VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_refresh_token CHANGE access_token access_token CHAR(80) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fichessecur (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, edition_id INT DEFAULT NULL, fiche VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT \'NULL\', UNIQUE INDEX UNIQ_D894AD9B6D861B89 (equipe_id), INDEX IDX_D894AD9B74281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE memoires (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, memoire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, annexe TINYINT(1) DEFAULT \'NULL\', updated_at DATETIME DEFAULT \'NULL\', edition_id INT DEFAULT NULL, INDEX IDX_98974E8A6D861B89 (equipe_id), INDEX IDX_98974E8A74281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE memoiresinter (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, memoire VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, annexe TINYINT(1) DEFAULT \'NULL\', updated_at DATETIME DEFAULT \'NULL\', edition_id INT DEFAULT NULL, INDEX IDX_BDD904356D861B89 (equipe_id), INDEX IDX_BDD9043574281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE presentation (id INT AUTO_INCREMENT NOT NULL, edition_id INT DEFAULT NULL, equipe_id INT DEFAULT NULL, presentation VARCHAR(255) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, updated_At DATETIME DEFAULT \'NULL\', INDEX edition_id (edition_id), INDEX equipe_id (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE resumes (id INT AUTO_INCREMENT NOT NULL, resume VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT \'NULL\', equipe_id INT DEFAULT NULL, edition_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_CDB8AD336D861B89 (equipe_id), INDEX IDX_CDB8AD3374281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE fichessecur ADD CONSTRAINT FK_D894AD9B6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipesadmin (id)');
        $this->addSql('ALTER TABLE fichessecur ADD CONSTRAINT FK_D894AD9B74281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE adminsite CHANGE session session VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE datelimite_cia datelimite_cia DATETIME DEFAULT \'NULL\', CHANGE datelimite_nat datelimite_nat DATETIME DEFAULT \'NULL\', CHANGE concours_cia concours_cia DATETIME DEFAULT \'NULL\', CHANGE concours_cn concours_cn DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE cadeaux CHANGE contenu contenu VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE fournisseur fournisseur VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE montant montant NUMERIC(6, 2) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE centrescia DROP FOREIGN KEY FK_B841FA83760CB16F');
        $this->addSql('ALTER TABLE centrescia DROP FOREIGN KEY FK_B841FA839253B9DF');
        $this->addSql('ALTER TABLE centrescia DROP FOREIGN KEY FK_B841FA83B5AE865');
        $this->addSql('ALTER TABLE centrescia DROP FOREIGN KEY FK_B841FA8366E65137');
        $this->addSql('ALTER TABLE centrescia CHANGE id_edition id_edition INT DEFAULT NULL, CHANGE id_orga1 id_orga1 INT DEFAULT NULL, CHANGE id_orga2 id_orga2 INT DEFAULT NULL, CHANGE id_jurycia id_jurycia INT DEFAULT NULL, CHANGE centre centre VARCHAR(255) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`');
        $this->addSql('ALTER TABLE classement CHANGE niveau niveau VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE montant montant NUMERIC(3, 0) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE edition CHANGE ed ed VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATETIME DEFAULT \'NULL\', CHANGE edition edition INT DEFAULT NULL, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lieu lieu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE datelimite_cia datelimite_cia DATETIME DEFAULT \'NULL\', CHANGE datelimite_nat datelimite_nat DATETIME DEFAULT \'NULL\', CHANGE date_ouverture_site date_ouverture_site DATETIME DEFAULT \'NULL\', CHANGE concours_cia concours_cia DATETIME DEFAULT \'NULL\', CHANGE concours_cn concours_cn DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE eleves CHANGE nom nom VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE classe classe VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE lettre_equipe lettre_equipe VARCHAR(1) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE elevesinter CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE numsite numsite INT DEFAULT NULL, CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE genre genre VARCHAR(1) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE classe classe VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE courriel courriel VARCHAR(60) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE equipes CHANGE visite_id visite_id INT DEFAULT NULL, CHANGE cadeau_id cadeau_id INT DEFAULT NULL, CHANGE phrases_id phrases_id INT DEFAULT NULL, CHANGE liaison_id liaison_id INT DEFAULT NULL, CHANGE prix_id prix_id INT DEFAULT NULL, CHANGE infoequipe_id infoequipe_id INT DEFAULT NULL, CHANGE ordre ordre SMALLINT DEFAULT NULL, CHANGE heure heure VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE salle salle VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE total total SMALLINT DEFAULT NULL, CHANGE classement classement VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE rang rang SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipesadmin DROP FOREIGN KEY FK_A26C5EB150D8F5D4');
        $this->addSql('ALTER TABLE equipesadmin DROP FOREIGN KEY FK_A26C5EB144D2DF56');
        $this->addSql('ALTER TABLE equipesadmin DROP FOREIGN KEY FK_A26C5EB174281A5E');
        $this->addSql('DROP INDEX IDX_A26C5EB150D8F5D4 ON equipesadmin');
        $this->addSql('DROP INDEX IDX_A26C5EB174281A5E ON equipesadmin');
        $this->addSql('ALTER TABLE equipesadmin CHANGE centrecia_id centrecia_id INT DEFAULT NULL, CHANGE rne_id rne_id INT DEFAULT NULL, CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE lettre lettre VARCHAR(1) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE numero numero SMALLINT DEFAULT NULL, CHANGE selectionnee selectionnee TINYINT(1) DEFAULT \'NULL\', CHANGE titreProjet titreProjet VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nom_lycee nom_lycee VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE denomination_lycee denomination_lycee VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lycee_localite lycee_localite VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE lycee_academie lycee_academie VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE prenom_prof1 prenom_prof1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nom_prof1 nom_prof1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE prenom_prof2 prenom_prof2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nom_prof2 nom_prof2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE rne rne VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE id_prof1 id_prof1 INT DEFAULT NULL, CHANGE id_prof2 id_prof2 INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipesadmin RENAME INDEX idx_a26c5eb144d2df56 TO rne_id');
        $this->addSql('ALTER TABLE fichiersequipes CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE fichier fichier VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE typefichier typefichier INT DEFAULT NULL, CHANGE national national TINYINT(1) DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE jures CHANGE A A SMALLINT DEFAULT NULL, CHANGE B B SMALLINT DEFAULT NULL, CHANGE C C SMALLINT DEFAULT NULL, CHANGE D D SMALLINT DEFAULT NULL, CHANGE E E SMALLINT DEFAULT NULL, CHANGE F F SMALLINT DEFAULT NULL, CHANGE G G SMALLINT DEFAULT NULL, CHANGE H H SMALLINT DEFAULT NULL, CHANGE I I SMALLINT DEFAULT NULL, CHANGE J J SMALLINT DEFAULT NULL, CHANGE K K SMALLINT DEFAULT NULL, CHANGE L L SMALLINT DEFAULT NULL, CHANGE M M SMALLINT DEFAULT NULL, CHANGE N N SMALLINT DEFAULT NULL, CHANGE O O SMALLINT DEFAULT NULL, CHANGE P P SMALLINT DEFAULT NULL, CHANGE Q Q SMALLINT DEFAULT NULL, CHANGE R R SMALLINT DEFAULT NULL, CHANGE S S SMALLINT DEFAULT NULL, CHANGE T T SMALLINT DEFAULT NULL, CHANGE U U SMALLINT DEFAULT NULL, CHANGE V V SMALLINT DEFAULT NULL, CHANGE W W SMALLINT DEFAULT NULL, CHANGE X X SMALLINT DEFAULT NULL, CHANGE Y Y SMALLINT DEFAULT NULL, CHANGE Z Z SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE liaison CHANGE liaison liaison VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68C6D861B89');
        $this->addSql('ALTER TABLE notes DROP FOREIGN KEY FK_11BA68CFFAFF81B');
        $this->addSql('ALTER TABLE notes CHANGE ecrit ecrit SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE oauth2_access_token CHANGE user_identifier user_identifier VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE oauth2_authorization_code CHANGE user_identifier user_identifier VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE oauth2_client CHANGE secret secret VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE oauth2_refresh_token CHANGE access_token access_token CHAR(80) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE orgacia DROP FOREIGN KEY FK_CAEB80DA463CD7C3');
        $this->addSql('ALTER TABLE orgacia CHANGE centre_id centre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6493BDE5358');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649296BFCB6');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE64991D79BD3');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649C00A36A');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649B4BCC40F');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649A6096BE1');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6491EB50C84');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE64946D61CD2');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649FE6A7BB7');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649ECDFD459');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6495463B33C');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649C9B48B85');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6497108ECE0');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE64963BD430E');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649DB01246B');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649D37B63A2');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6496BC704C7');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6497972AB29');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649C1CECC4C');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6495C19F4F5');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649E4A59390');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649F6103C7E');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE6494EAC5B1B');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE64916CF4B4D');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649AE732C28');
        $this->addSql('ALTER TABLE palmares DROP FOREIGN KEY FK_FF4EE649BCC683C6');
        $this->addSql('ALTER TABLE palmares CHANGE a_id a_id INT DEFAULT NULL, CHANGE b_id b_id INT DEFAULT NULL, CHANGE c_id c_id INT DEFAULT NULL, CHANGE d_id d_id INT DEFAULT NULL, CHANGE e_id e_id INT DEFAULT NULL, CHANGE f_id f_id INT DEFAULT NULL, CHANGE g_id g_id INT DEFAULT NULL, CHANGE h_id h_id INT DEFAULT NULL, CHANGE i_id i_id INT DEFAULT NULL, CHANGE j_id j_id INT DEFAULT NULL, CHANGE k_id k_id INT DEFAULT NULL, CHANGE l_id l_id INT DEFAULT NULL, CHANGE m_id m_id INT DEFAULT NULL, CHANGE n_id n_id INT DEFAULT NULL, CHANGE o_id o_id INT DEFAULT NULL, CHANGE p_id p_id INT DEFAULT NULL, CHANGE q_id q_id INT DEFAULT NULL, CHANGE r_id r_id INT DEFAULT NULL, CHANGE s_id s_id INT DEFAULT NULL, CHANGE t_id t_id INT DEFAULT NULL, CHANGE u_id u_id INT DEFAULT NULL, CHANGE v_id v_id INT DEFAULT NULL, CHANGE w_id w_id INT DEFAULT NULL, CHANGE x_id x_id INT DEFAULT NULL, CHANGE y_id y_id INT DEFAULT NULL, CHANGE z_id z_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photoscn DROP FOREIGN KEY FK_55F3F29C6D861B89');
        $this->addSql('ALTER TABLE photoscn DROP FOREIGN KEY FK_55F3F29C74281A5E');
        $this->addSql('ALTER TABLE photoscn DROP FOREIGN KEY FK_55F3F29CC7034EA5');
        $this->addSql('ALTER TABLE photoscn CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE thumb_id thumb_id INT DEFAULT NULL, CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE coment coment VARCHAR(125) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE photoscn RENAME INDEX uniq_55f3f29cc7034ea5 TO thumb_id');
        $this->addSql('ALTER TABLE photoscn RENAME INDEX idx_55f3f29c74281a5e TO edition_id');
        $this->addSql('ALTER TABLE photoscn RENAME INDEX idx_55f3f29c6d861b89 TO equipe_id');
        $this->addSql('ALTER TABLE photoscnthumb CHANGE photo photo VARCHAR(255) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE photosinter DROP FOREIGN KEY FK_B6BFEF956D861B89');
        $this->addSql('ALTER TABLE photosinter DROP FOREIGN KEY FK_B6BFEF9574281A5E');
        $this->addSql('ALTER TABLE photosinter DROP FOREIGN KEY FK_B6BFEF95C7034EA5');
        $this->addSql('ALTER TABLE photosinter CHANGE equipe_id equipe_id INT DEFAULT NULL, CHANGE edition_id edition_id INT DEFAULT NULL, CHANGE thumb_id thumb_id INT DEFAULT NULL, CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE coment coment VARCHAR(125) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE photosinter RENAME INDEX uniq_b6bfef95c7034ea5 TO thumb_id');
        $this->addSql('ALTER TABLE photosinterthumb CHANGE photo photo VARCHAR(255) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE prix CHANGE prix prix VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE classement classement VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE voix voix VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE intervenant intervenant VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE remis_par remis_par VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
        $this->addSql('DROP INDEX UNIQ_6E92B6D26E92B6D2 ON rne');
        $this->addSql('ALTER TABLE rne CHANGE rne rne TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE commune commune VARCHAR(150) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, CHANGE academie academie TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE pays pays TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE departement departement TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE appellation_officielle appellation_officielle TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE adresse adresse TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE boite_postale boite_postale TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE code_postal code_postal TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE sigle sigle TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE denomination_principale denomination_principale VARCHAR(100) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, CHANGE acheminement acheminement VARCHAR(50) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, CHANGE coordonnee_x coordonnee_x NUMERIC(9, 1) DEFAULT \'NULL\', CHANGE coordonnee_y coordonnee_y NUMERIC(9, 1) DEFAULT \'NULL\', CHANGE nature nature TEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE nom nom VARCHAR(60) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`');
        $this->addSql('DROP INDEX UNIQ_47D0A6CF31B17EF3 ON totalequipes');
        $this->addSql('DROP INDEX UNIQ_47D0A6CFA8B82F49 ON totalequipes');
        $this->addSql('DROP INDEX UNIQ_47D0A6CF6E92B6D2 ON totalequipes');
        $this->addSql('ALTER TABLE totalequipes CHANGE numero_equipe numero_equipe SMALLINT DEFAULT NULL, CHANGE lettre_equipe lettre_equipe VARCHAR(1) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE nom_equipe nom_equipe VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE nom_lycee nom_lycee VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE denomination_lycee denomination_lycee VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE lycee_localite lycee_localite VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE lycee_academie lycee_academie VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE id_prof1 id_prof1 INT DEFAULT NULL, CHANGE id_prof2 id_prof2 INT DEFAULT NULL, CHANGE prenom_prof1 prenom_prof1 VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE nom_prof1 nom_prof1 VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE prenom_prof2 prenom_prof2 VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE nom_prof2 nom_prof2 VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`, CHANGE rne rne VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649463CD7C3');
        $this->addSql('ALTER TABLE user CHANGE centre_id centre_id INT DEFAULT NULL, CHANGE is_active is_active TINYINT(1) NOT NULL, CHANGE token token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE password_requested_at password_requested_at DATETIME DEFAULT \'NULL\', CHANGE rne rne VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE nom nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE adresse adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE ville ville VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE code code VARCHAR(11) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE createdAt createdAt DATETIME DEFAULT \'NULL\', CHANGE updatedAt updatedAt DATETIME DEFAULT \'NULL\', CHANGE lastVisit lastVisit DATETIME DEFAULT \'NULL\', CHANGE civilite civilite VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE visites CHANGE intitule intitule VARCHAR(255) CHARACTER SET utf8 DEFAULT \'NULL\' COLLATE `utf8_general_ci`');
    }
}
