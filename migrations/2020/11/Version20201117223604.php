<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201117223604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_occupation DROP FOREIGN KEY FK_34431E4422C8FC20');
        $this->addSql('DROP TABLE occupation');
        $this->addSql('DROP TABLE person_occupation');
        $this->addSql('ALTER TABLE person ADD titles JSON NOT NULL, ADD occupations JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE occupation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, label VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created DATETIME NOT NULL, updated DATETIME NOT NULL, FULLTEXT INDEX IDX_2F87D516DE44026 (description), FULLTEXT INDEX IDX_2F87D51EA750E8 (label), FULLTEXT INDEX IDX_2F87D51EA750E86DE44026 (label, description), UNIQUE INDEX UNIQ_2F87D515E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE person_occupation (person_id INT NOT NULL, occupation_id INT NOT NULL, INDEX IDX_34431E44217BBB47 (person_id), INDEX IDX_34431E4422C8FC20 (occupation_id), PRIMARY KEY(person_id, occupation_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE person_occupation ADD CONSTRAINT FK_34431E44217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_occupation ADD CONSTRAINT FK_34431E4422C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person DROP titles, DROP occupations');
    }
}
