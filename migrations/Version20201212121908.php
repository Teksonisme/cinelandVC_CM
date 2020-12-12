<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212121908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE acteur (id INT AUTO_INCREMENT NOT NULL, nom_prenom VARCHAR(40) NOT NULL, date_naissance DATE DEFAULT NULL, nationalite VARCHAR(25) DEFAULT NULL, UNIQUE INDEX UNIQ_EAFAD362CEAEEAB0 (date_naissance), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE acteur_film (acteur_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_14B01103DA6F574A (acteur_id), INDEX IDX_14B01103567F5183 (film_id), PRIMARY KEY(acteur_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film (id INT AUTO_INCREMENT NOT NULL, genre_id INT DEFAULT NULL, titre VARCHAR(60) NOT NULL, duree SMALLINT NOT NULL, date_sortie DATE NOT NULL, note SMALLINT DEFAULT NULL, age_minimal SMALLINT NOT NULL, UNIQUE INDEX UNIQ_8244BE228456C035 (duree), INDEX IDX_8244BE224296D31F (genre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film_acteur (film_id INT NOT NULL, acteur_id INT NOT NULL, INDEX IDX_8108EE68567F5183 (film_id), INDEX IDX_8108EE68DA6F574A (acteur_id), PRIMARY KEY(film_id, acteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_835033F86C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE acteur_film ADD CONSTRAINT FK_14B01103DA6F574A FOREIGN KEY (acteur_id) REFERENCES acteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE acteur_film ADD CONSTRAINT FK_14B01103567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film ADD CONSTRAINT FK_8244BE224296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE film_acteur ADD CONSTRAINT FK_8108EE68567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_acteur ADD CONSTRAINT FK_8108EE68DA6F574A FOREIGN KEY (acteur_id) REFERENCES acteur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE acteur_film DROP FOREIGN KEY FK_14B01103DA6F574A');
        $this->addSql('ALTER TABLE film_acteur DROP FOREIGN KEY FK_8108EE68DA6F574A');
        $this->addSql('ALTER TABLE acteur_film DROP FOREIGN KEY FK_14B01103567F5183');
        $this->addSql('ALTER TABLE film_acteur DROP FOREIGN KEY FK_8108EE68567F5183');
        $this->addSql('ALTER TABLE film DROP FOREIGN KEY FK_8244BE224296D31F');
        $this->addSql('DROP TABLE acteur');
        $this->addSql('DROP TABLE acteur_film');
        $this->addSql('DROP TABLE film');
        $this->addSql('DROP TABLE film_acteur');
        $this->addSql('DROP TABLE genre');
    }
}
