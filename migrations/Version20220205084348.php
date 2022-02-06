<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220205084348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add piece entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE piece (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piece_list_id INTEGER UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_44CA0B23211805E2 ON piece (piece_list_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE piece');
    }
}
