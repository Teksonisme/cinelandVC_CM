<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201219233847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_EAFAD362CEAEEAB0 ON acteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAFAD36226EA0B0C ON acteur (nom_prenom)');
        $this->addSql('ALTER TABLE film CHANGE date_sortie date_sortie VARCHAR(50) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8244BE22FF7747B4 ON film (titre)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_EAFAD36226EA0B0C ON acteur');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EAFAD362CEAEEAB0 ON acteur (date_naissance)');
        $this->addSql('DROP INDEX UNIQ_8244BE22FF7747B4 ON film');
        $this->addSql('ALTER TABLE film CHANGE date_sortie date_sortie DATE NOT NULL');
    }
}
