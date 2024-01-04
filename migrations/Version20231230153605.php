<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230153605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE advertisement_like_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE advertisement_like (id INT NOT NULL, people_id INT DEFAULT NULL, advertisement_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4FE15BFB3147C936 ON advertisement_like (people_id)');
        $this->addSql('CREATE INDEX IDX_4FE15BFBA1FBF71B ON advertisement_like (advertisement_id)');
        $this->addSql('ALTER TABLE advertisement_like ADD CONSTRAINT FK_4FE15BFB3147C936 FOREIGN KEY (people_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE advertisement_like ADD CONSTRAINT FK_4FE15BFBA1FBF71B FOREIGN KEY (advertisement_id) REFERENCES advertisement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE advertisement_like_id_seq CASCADE');
        $this->addSql('ALTER TABLE advertisement_like DROP CONSTRAINT FK_4FE15BFB3147C936');
        $this->addSql('ALTER TABLE advertisement_like DROP CONSTRAINT FK_4FE15BFBA1FBF71B');
        $this->addSql('DROP TABLE advertisement_like');
    }
}
