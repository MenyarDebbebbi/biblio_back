<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial entities: User, Editeur, Auteur, Categorie, Livre';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        
        $this->addSql('CREATE TABLE editeur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL)');
        
        $this->addSql('CREATE TABLE auteur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL)');
        
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, designation VARCHAR(255) NOT NULL)');
        
        $this->addSql('CREATE TABLE livre (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, auteur_id INTEGER DEFAULT NULL, categorie_id INTEGER DEFAULT NULL, editeur_id INTEGER DEFAULT NULL, titre VARCHAR(255) NOT NULL, qte INTEGER NOT NULL, priorite VARCHAR(255) NOT NULL, isbn VARCHAR(255) DEFAULT NULL, datepub DATE NOT NULL, CONSTRAINT FK_AC634F9960BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AC634F99BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AC634F993375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AC634F9960BB6FE6 ON livre (auteur_id)');
        $this->addSql('CREATE INDEX IDX_AC634F99BCF5E72D ON livre (categorie_id)');
        $this->addSql('CREATE INDEX IDX_AC634F993375BD21 ON livre (editeur_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE editeur');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE livre');
    }
}

