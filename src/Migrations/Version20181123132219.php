<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181123132219 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boat (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, coord_x INT NOT NULL, coord_y INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tile (id SERIAL NOT NULL, type VARCHAR(255) NOT NULL, coord_x INT NOT NULL, coord_y INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE boat');
        $this->addSql('DROP TABLE tile');
    }
}
