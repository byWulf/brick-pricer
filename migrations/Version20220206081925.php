<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220206081925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE color (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, rgb VARCHAR(255) NOT NULL, is_transparent BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE external_color (id INTEGER NOT NULL, color_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_541B3B6A7ADA1FB5 ON external_color (color_id)');
        $this->addSql('CREATE TABLE piece_piece (piece_source INTEGER UNSIGNED NOT NULL, piece_target INTEGER UNSIGNED NOT NULL, PRIMARY KEY(piece_source, piece_target))');
        $this->addSql('CREATE INDEX IDX_56798A4885F87422 ON piece_piece (piece_source)');
        $this->addSql('CREATE INDEX IDX_56798A489C1D24AD ON piece_piece (piece_target)');
        $this->addSql('CREATE TABLE piece_count (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, list_id INTEGER UNSIGNED DEFAULT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, count_needed INTEGER UNSIGNED NOT NULL, count_having INTEGER UNSIGNED NOT NULL)');
        $this->addSql('CREATE INDEX IDX_976AC5093DAE168B ON piece_count (list_id)');
        $this->addSql('CREATE INDEX IDX_976AC509C40FCFA8 ON piece_count (piece_id)');
        $this->addSql('DROP INDEX IDX_44CA0B23211805E2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece AS SELECT id, name FROM piece');
        $this->addSql('DROP TABLE piece');
        $this->addSql('CREATE TABLE piece (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, color_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, CONSTRAINT FK_44CA0B237ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO piece (id, name) SELECT id, name FROM __temp__piece');
        $this->addSql('DROP TABLE __temp__piece');
        $this->addSql('CREATE INDEX IDX_44CA0B237ADA1FB5 ON piece (color_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE external_color');
        $this->addSql('DROP TABLE piece_piece');
        $this->addSql('DROP TABLE piece_count');
        $this->addSql('DROP INDEX IDX_44CA0B237ADA1FB5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece AS SELECT id, name FROM piece');
        $this->addSql('DROP TABLE piece');
        $this->addSql('CREATE TABLE piece (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, piece_list_id INTEGER UNSIGNED DEFAULT NULL)');
        $this->addSql('INSERT INTO piece (id, name) SELECT id, name FROM __temp__piece');
        $this->addSql('DROP TABLE __temp__piece');
        $this->addSql('CREATE INDEX IDX_44CA0B23211805E2 ON piece (piece_list_id)');
    }
}
