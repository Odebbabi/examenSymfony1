<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223214605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, cour_id INT DEFAULT NULL, exercice_id INT DEFAULT NULL, exam_id INT DEFAULT NULL, quiz_id INT DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526CB7942F03 (cour_id), INDEX IDX_9474526C89D40298 (exercice_id), INDEX IDX_9474526C578D5E91 (exam_id), INDEX IDX_9474526C853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cour (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, user_id INT NOT NULL, nom_cour VARCHAR(255) NOT NULL, description_cour VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, image_name VARCHAR(255) DEFAULT NULL, image_original_name VARCHAR(255) DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, image_size INT DEFAULT NULL, image_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_A71F964FF46CD258 (matiere_id), INDEX IDX_A71F964FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, user_id INT NOT NULL, semestre VARCHAR(255) NOT NULL, type_exam VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, pdf_name VARCHAR(255) DEFAULT NULL, pdf_original_name VARCHAR(255) DEFAULT NULL, pdf_mime_type VARCHAR(255) DEFAULT NULL, pdf_size INT DEFAULT NULL, pdf_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', correction_name VARCHAR(255) DEFAULT NULL, correction_original_name VARCHAR(255) DEFAULT NULL, correction_mime_type VARCHAR(255) DEFAULT NULL, correction_size INT DEFAULT NULL, correction_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_514C8FECF46CD258 (matiere_id), INDEX IDX_514C8FECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, cour_id INT NOT NULL, user_id INT NOT NULL, type_exercice VARCHAR(255) NOT NULL, nom_exercice VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, pdf_name VARCHAR(255) DEFAULT NULL, pdf_original_name VARCHAR(255) DEFAULT NULL, pdf_mime_type VARCHAR(255) DEFAULT NULL, pdf_size INT DEFAULT NULL, pdf_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', correction_name VARCHAR(255) DEFAULT NULL, correction_original_name VARCHAR(255) DEFAULT NULL, correction_mime_type VARCHAR(255) DEFAULT NULL, correction_size INT DEFAULT NULL, correction_dimensions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_E418C74DB7942F03 (cour_id), INDEX IDX_E418C74DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, niveau_id INT DEFAULT NULL, nom_matiere VARCHAR(255) NOT NULL, domaine_matiere VARCHAR(255) NOT NULL, INDEX IDX_9014574AB3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, nom_niveau VARCHAR(255) NOT NULL, num_niveau INT NOT NULL, UNIQUE INDEX UNIQ_4BDFF36BF2FEA4D3 (num_niveau), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, nom_proposition VARCHAR(255) DEFAULT NULL, correcte TINYINT(1) NOT NULL, INDEX IDX_C7CDC3531E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, nom_question VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, user_id INT NOT NULL, nom_quiz VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, num_question INT DEFAULT NULL, difficulte VARCHAR(255) NOT NULL, INDEX IDX_A412FA92F46CD258 (matiere_id), INDEX IDX_A412FA92A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (quiz_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), INDEX IDX_6033B00B1E27F6BF (question_id), PRIMARY KEY(quiz_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, date_birth DATE NOT NULL, niveau INT DEFAULT NULL, num_carte_inscription VARCHAR(255) DEFAULT NULL, specialty VARCHAR(255) DEFAULT NULL, num_cin INT DEFAULT NULL, account_type VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, carte_inscription VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, cin_avant VARCHAR(255) DEFAULT NULL, cin_avant_updated_at DATETIME NOT NULL, cin_arriere VARCHAR(255) DEFAULT NULL, cin_arriere_updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB7942F03 FOREIGN KEY (cour_id) REFERENCES cour (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C89D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C578D5E91 FOREIGN KEY (exam_id) REFERENCES examen (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964FF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE cour ADD CONSTRAINT FK_A71F964FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FECF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FECA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DB7942F03 FOREIGN KEY (cour_id) REFERENCES cour (id)');
        $this->addSql('ALTER TABLE exercice ADD CONSTRAINT FK_E418C74DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE proposition ADD CONSTRAINT FK_C7CDC3531E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB7942F03');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DB7942F03');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C578D5E91');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C89D40298');
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964FF46CD258');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FECF46CD258');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92F46CD258');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AB3E9C81');
        $this->addSql('ALTER TABLE proposition DROP FOREIGN KEY FK_C7CDC3531E27F6BF');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B1E27F6BF');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE cour DROP FOREIGN KEY FK_A71F964FA76ED395');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FECA76ED395');
        $this->addSql('ALTER TABLE exercice DROP FOREIGN KEY FK_E418C74DA76ED395');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92A76ED395');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE cour');
        $this->addSql('DROP TABLE examen');
        $this->addSql('DROP TABLE exercice');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE proposition');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE `user`');
    }
}
