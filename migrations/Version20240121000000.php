<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121000000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(55) NOT NULL, last_name VARCHAR(55) NOT NULL, gender VARCHAR(55) DEFAULT NULL, phone VARCHAR(55) DEFAULT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, rank INT NOT NULL, with_note TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, organization_id INT DEFAULT NULL, service_id INT DEFAULT NULL, contact_id INT DEFAULT NULL, date DATETIME NOT NULL, duration VARCHAR(55) DEFAULT NULL, people_count INT NOT NULL, organization_note VARCHAR(255) DEFAULT NULL, service_note VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, location_street VARCHAR(255) DEFAULT NULL, location_zip_code VARCHAR(25) DEFAULT NULL, location_city VARCHAR(255) DEFAULT NULL, location_department VARCHAR(55) DEFAULT NULL, location_region VARCHAR(55) DEFAULT NULL, location_country VARCHAR(55) DEFAULT NULL, INDEX IDX_6B71CBF432C8A3DE (organization_id), INDEX IDX_6B71CBF4ED5CA9E6 (service_id), UNIQUE INDEX UNIQ_6B71CBF4E7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, rank INT NOT NULL, with_note TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(55) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF4ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF4E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF432C8A3DE');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF4ED5CA9E6');
        $this->addSql('ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF4E7A1254A');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE quote');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
    }
}
