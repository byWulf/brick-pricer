<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220208114951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__color AS SELECT id, name, rgb, is_transparent FROM color');
        $this->addSql('DROP TABLE color');
        $this->addSql('CREATE TABLE color (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, rgb VARCHAR(255) NOT NULL, is_transparent BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO color (id, name, rgb, is_transparent) SELECT id, name, rgb, is_transparent FROM __temp__color');
        $this->addSql('DROP TABLE __temp__color');
        $this->addSql('DROP INDEX IDX_541B3B6A7ADA1FB5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__external_color AS SELECT id, color_id, system, ids, names FROM external_color');
        $this->addSql('DROP TABLE external_color');
        $this->addSql('CREATE TABLE external_color (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, color_id INTEGER DEFAULT NULL, system VARCHAR(255) NOT NULL, ids CLOB NOT NULL --(DC2Type:json)
        , names CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_541B3B6A7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO external_color (id, color_id, system, ids, names) SELECT id, color_id, system, ids, names FROM __temp__external_color');
        $this->addSql('DROP TABLE __temp__external_color');
        $this->addSql('CREATE INDEX IDX_541B3B6A7ADA1FB5 ON external_color (color_id)');
        $this->addSql('DROP INDEX IDX_44CA0B237ADA1FB5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece AS SELECT id, color_id, name, image_url, part_number, cached_parts_needed, cached_best_price FROM piece');
        $this->addSql('DROP TABLE piece');
        $this->addSql('CREATE TABLE piece (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, color_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, part_number VARCHAR(255) NOT NULL, cached_parts_needed INTEGER UNSIGNED NOT NULL, cached_best_price INTEGER UNSIGNED DEFAULT NULL, CONSTRAINT FK_44CA0B237ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO piece (id, color_id, name, image_url, part_number, cached_parts_needed, cached_best_price) SELECT id, color_id, name, image_url, part_number, cached_parts_needed, cached_best_price FROM __temp__piece');
        $this->addSql('DROP TABLE __temp__piece');
        $this->addSql('CREATE INDEX IDX_44CA0B237ADA1FB5 ON piece (color_id)');
        $this->addSql('DROP INDEX IDX_56798A4885F87422');
        $this->addSql('DROP INDEX IDX_56798A489C1D24AD');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_piece AS SELECT piece_source, piece_target FROM piece_piece');
        $this->addSql('DROP TABLE piece_piece');
        $this->addSql('CREATE TABLE piece_piece (piece_source INTEGER UNSIGNED NOT NULL, piece_target INTEGER UNSIGNED NOT NULL, PRIMARY KEY(piece_source, piece_target), CONSTRAINT FK_56798A4885F87422 FOREIGN KEY (piece_source) REFERENCES piece (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_56798A489C1D24AD FOREIGN KEY (piece_target) REFERENCES piece (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO piece_piece (piece_source, piece_target) SELECT piece_source, piece_target FROM __temp__piece_piece');
        $this->addSql('DROP TABLE __temp__piece_piece');
        $this->addSql('CREATE INDEX IDX_56798A4885F87422 ON piece_piece (piece_source)');
        $this->addSql('CREATE INDEX IDX_56798A489C1D24AD ON piece_piece (piece_target)');
        $this->addSql('DROP INDEX IDX_976AC5093DAE168B');
        $this->addSql('DROP INDEX IDX_976AC509C40FCFA8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_count AS SELECT id, list_id, piece_id, count_needed, count_having FROM piece_count');
        $this->addSql('DROP TABLE piece_count');
        $this->addSql('CREATE TABLE piece_count (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, list_id INTEGER UNSIGNED DEFAULT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, count_needed INTEGER UNSIGNED NOT NULL, count_having INTEGER UNSIGNED NOT NULL, CONSTRAINT FK_976AC5093DAE168B FOREIGN KEY (list_id) REFERENCES piece_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_976AC509C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO piece_count (id, list_id, piece_id, count_needed, count_having) SELECT id, list_id, piece_id, count_needed, count_having FROM __temp__piece_count');
        $this->addSql('DROP TABLE __temp__piece_count');
        $this->addSql('CREATE INDEX IDX_976AC5093DAE168B ON piece_count (list_id)');
        $this->addSql('CREATE INDEX IDX_976AC509C40FCFA8 ON piece_count (piece_id)');
        $this->addSql('DROP INDEX IDX_4CE21405C40FCFA8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_number AS SELECT id, piece_id, system, ids FROM piece_number');
        $this->addSql('DROP TABLE piece_number');
        $this->addSql('CREATE TABLE piece_number (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, system VARCHAR(255) NOT NULL, ids CLOB NOT NULL --(DC2Type:json)
        , CONSTRAINT FK_4CE21405C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO piece_number (id, piece_id, system, ids) SELECT id, piece_id, system, ids FROM __temp__piece_number');
        $this->addSql('DROP TABLE __temp__piece_number');
        $this->addSql('CREATE INDEX IDX_4CE21405C40FCFA8 ON piece_number (piece_id)');
        $this->addSql('DROP INDEX IDX_D87BA3B2C40FCFA8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_price AS SELECT id, piece_id, source, price, updated FROM piece_price');
        $this->addSql('DROP TABLE piece_price');
        $this->addSql('CREATE TABLE piece_price (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, source VARCHAR(255) NOT NULL, price INTEGER UNSIGNED DEFAULT NULL, updated DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_D87BA3B2C40FCFA8 FOREIGN KEY (piece_id) REFERENCES piece (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO piece_price (id, piece_id, source, price, updated) SELECT id, piece_id, source, price, updated FROM __temp__piece_price');
        $this->addSql('DROP TABLE __temp__piece_price');
        $this->addSql('CREATE INDEX IDX_D87BA3B2C40FCFA8 ON piece_price (piece_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__color AS SELECT id, name, rgb, is_transparent FROM color');
        $this->addSql('DROP TABLE color');
        $this->addSql('CREATE TABLE color (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rgb VARCHAR(255) NOT NULL, is_transparent BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO color (id, name, rgb, is_transparent) SELECT id, name, rgb, is_transparent FROM __temp__color');
        $this->addSql('DROP TABLE __temp__color');
        $this->addSql('DROP INDEX IDX_541B3B6A7ADA1FB5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__external_color AS SELECT id, color_id, system, ids, names FROM external_color');
        $this->addSql('DROP TABLE external_color');
        $this->addSql('CREATE TABLE external_color (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, color_id INTEGER DEFAULT NULL, system VARCHAR(255) NOT NULL, ids CLOB NOT NULL --(DC2Type:json)
        , names CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO external_color (id, color_id, system, ids, names) SELECT id, color_id, system, ids, names FROM __temp__external_color');
        $this->addSql('DROP TABLE __temp__external_color');
        $this->addSql('CREATE INDEX IDX_541B3B6A7ADA1FB5 ON external_color (color_id)');
        $this->addSql('DROP INDEX IDX_44CA0B237ADA1FB5');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece AS SELECT id, color_id, part_number, name, image_url, cached_parts_needed, cached_best_price FROM piece');
        $this->addSql('DROP TABLE piece');
        $this->addSql('CREATE TABLE piece (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, color_id INTEGER DEFAULT NULL, part_number VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, image_url VARCHAR(255) NOT NULL, cached_parts_needed INTEGER UNSIGNED NOT NULL, cached_best_price INTEGER UNSIGNED DEFAULT NULL)');
        $this->addSql('INSERT INTO piece (id, color_id, part_number, name, image_url, cached_parts_needed, cached_best_price) SELECT id, color_id, part_number, name, image_url, cached_parts_needed, cached_best_price FROM __temp__piece');
        $this->addSql('DROP TABLE __temp__piece');
        $this->addSql('CREATE INDEX IDX_44CA0B237ADA1FB5 ON piece (color_id)');
        $this->addSql('DROP INDEX IDX_976AC5093DAE168B');
        $this->addSql('DROP INDEX IDX_976AC509C40FCFA8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_count AS SELECT id, list_id, piece_id, count_needed, count_having FROM piece_count');
        $this->addSql('DROP TABLE piece_count');
        $this->addSql('CREATE TABLE piece_count (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, list_id INTEGER UNSIGNED DEFAULT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, count_needed INTEGER UNSIGNED NOT NULL, count_having INTEGER UNSIGNED NOT NULL)');
        $this->addSql('INSERT INTO piece_count (id, list_id, piece_id, count_needed, count_having) SELECT id, list_id, piece_id, count_needed, count_having FROM __temp__piece_count');
        $this->addSql('DROP TABLE __temp__piece_count');
        $this->addSql('CREATE INDEX IDX_976AC5093DAE168B ON piece_count (list_id)');
        $this->addSql('CREATE INDEX IDX_976AC509C40FCFA8 ON piece_count (piece_id)');
        $this->addSql('DROP INDEX IDX_4CE21405C40FCFA8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_number AS SELECT id, piece_id, system, ids FROM piece_number');
        $this->addSql('DROP TABLE piece_number');
        $this->addSql('CREATE TABLE piece_number (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, system VARCHAR(255) NOT NULL, ids CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO piece_number (id, piece_id, system, ids) SELECT id, piece_id, system, ids FROM __temp__piece_number');
        $this->addSql('DROP TABLE __temp__piece_number');
        $this->addSql('CREATE INDEX IDX_4CE21405C40FCFA8 ON piece_number (piece_id)');
        $this->addSql('DROP INDEX IDX_56798A4885F87422');
        $this->addSql('DROP INDEX IDX_56798A489C1D24AD');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_piece AS SELECT piece_source, piece_target FROM piece_piece');
        $this->addSql('DROP TABLE piece_piece');
        $this->addSql('CREATE TABLE piece_piece (piece_source INTEGER UNSIGNED NOT NULL, piece_target INTEGER UNSIGNED NOT NULL, PRIMARY KEY(piece_source, piece_target))');
        $this->addSql('INSERT INTO piece_piece (piece_source, piece_target) SELECT piece_source, piece_target FROM __temp__piece_piece');
        $this->addSql('DROP TABLE __temp__piece_piece');
        $this->addSql('CREATE INDEX IDX_56798A4885F87422 ON piece_piece (piece_source)');
        $this->addSql('CREATE INDEX IDX_56798A489C1D24AD ON piece_piece (piece_target)');
        $this->addSql('DROP INDEX IDX_D87BA3B2C40FCFA8');
        $this->addSql('CREATE TEMPORARY TABLE __temp__piece_price AS SELECT id, piece_id, source, price, updated FROM piece_price');
        $this->addSql('DROP TABLE piece_price');
        $this->addSql('CREATE TABLE piece_price (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piece_id INTEGER UNSIGNED DEFAULT NULL, source VARCHAR(255) NOT NULL, price INTEGER UNSIGNED NOT NULL, updated DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO piece_price (id, piece_id, source, price, updated) SELECT id, piece_id, source, price, updated FROM __temp__piece_price');
        $this->addSql('DROP TABLE __temp__piece_price');
        $this->addSql('CREATE INDEX IDX_D87BA3B2C40FCFA8 ON piece_price (piece_id)');
    }
}
