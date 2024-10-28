<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027213301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pokemon_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE statistique_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE talent_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, statistique_id INT DEFAULT NULL, is_manual BOOLEAN NOT NULL, nom VARCHAR(255) NOT NULL, numero_pokedex INT NOT NULL, description TEXT NOT NULL, image VARCHAR(255) NOT NULL, poids DOUBLE PRECISION NOT NULL, taille DOUBLE PRECISION NOT NULL, type1 VARCHAR(255) NOT NULL, type2 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62DC90F376A81463 ON pokemon (statistique_id)');
        $this->addSql('CREATE TABLE statistique (id INT NOT NULL, hp INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, special_attack INT NOT NULL, special_defense INT NOT NULL, speed INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE talent (id INT NOT NULL, pokemon_id INT NOT NULL, nom VARCHAR(255) NOT NULL, is_hidden BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_16D902F52FE71C3E ON talent (pokemon_id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F376A81463 FOREIGN KEY (statistique_id) REFERENCES statistique (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talent ADD CONSTRAINT FK_16D902F52FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pokemon_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE statistique_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE talent_id_seq CASCADE');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F376A81463');
        $this->addSql('ALTER TABLE talent DROP CONSTRAINT FK_16D902F52FE71C3E');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE statistique');
        $this->addSql('DROP TABLE talent');
    }
}
