<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318033414 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pronostic (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category_id INT DEFAULT NULL, result_id INT DEFAULT NULL, create_at VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_E64BDCDEA76ED395 (user_id), INDEX IDX_E64BDCDE12469DE2 (category_id), INDEX IDX_E64BDCDE7A7B643 (result_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pronostic ADD CONSTRAINT FK_E64BDCDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pronostic ADD CONSTRAINT FK_E64BDCDE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE pronostic ADD CONSTRAINT FK_E64BDCDE7A7B643 FOREIGN KEY (result_id) REFERENCES result (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pronostic DROP FOREIGN KEY FK_E64BDCDE7A7B643');
        $this->addSql('DROP TABLE pronostic');
        $this->addSql('DROP TABLE result');
    }
}