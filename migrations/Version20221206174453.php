<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221206174453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AA76ED395');
        $this->addSql('DROP INDEX IDX_9014574AA76ED395 ON matiere');
        $this->addSql('ALTER TABLE matiere DROP user_id');
        $this->addSql('ALTER TABLE user ADD matiere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F46CD258 ON user (matiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9014574AA76ED395 ON matiere (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F46CD258');
        $this->addSql('DROP INDEX UNIQ_8D93D649F46CD258 ON user');
        $this->addSql('ALTER TABLE user DROP matiere_id');
    }
}
