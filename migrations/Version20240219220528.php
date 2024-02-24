<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219220528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_64C19AA9A76ED395 ON agence (user_id)');
        $this->addSql('ALTER TABLE client ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C7440455A76ED395 ON client (user_id)');
        $this->addSql('ALTER TABLE destinataire ADD client_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE destinataire ADD CONSTRAINT FK_FEA9FF9219EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE destinataire ADD CONSTRAINT FK_FEA9FF92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FEA9FF9219EB6921 ON destinataire (client_id)');
        $this->addSql('CREATE INDEX IDX_FEA9FF92A76ED395 ON destinataire (user_id)');
        $this->addSql('ALTER TABLE transfert ADD client_id INT NOT NULL, ADD user_id INT DEFAULT NULL, ADD destinataire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transfert ADD CONSTRAINT FK_1E4EACBB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transfert ADD CONSTRAINT FK_1E4EACBBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transfert ADD CONSTRAINT FK_1E4EACBBA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES destinataire (id)');
        $this->addSql('CREATE INDEX IDX_1E4EACBB19EB6921 ON transfert (client_id)');
        $this->addSql('CREATE INDEX IDX_1E4EACBBA76ED395 ON transfert (user_id)');
        $this->addSql('CREATE INDEX IDX_1E4EACBBA4F84F6E ON transfert (destinataire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA9A76ED395');
        $this->addSql('DROP INDEX IDX_64C19AA9A76ED395 ON agence');
        $this->addSql('ALTER TABLE agence DROP user_id');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('DROP INDEX IDX_C7440455A76ED395 ON client');
        $this->addSql('ALTER TABLE client DROP user_id');
        $this->addSql('ALTER TABLE transfert DROP FOREIGN KEY FK_1E4EACBB19EB6921');
        $this->addSql('ALTER TABLE transfert DROP FOREIGN KEY FK_1E4EACBBA76ED395');
        $this->addSql('ALTER TABLE transfert DROP FOREIGN KEY FK_1E4EACBBA4F84F6E');
        $this->addSql('DROP INDEX IDX_1E4EACBB19EB6921 ON transfert');
        $this->addSql('DROP INDEX IDX_1E4EACBBA76ED395 ON transfert');
        $this->addSql('DROP INDEX IDX_1E4EACBBA4F84F6E ON transfert');
        $this->addSql('ALTER TABLE transfert DROP client_id, DROP user_id, DROP destinataire_id');
        $this->addSql('ALTER TABLE destinataire DROP FOREIGN KEY FK_FEA9FF9219EB6921');
        $this->addSql('ALTER TABLE destinataire DROP FOREIGN KEY FK_FEA9FF92A76ED395');
        $this->addSql('DROP INDEX IDX_FEA9FF9219EB6921 ON destinataire');
        $this->addSql('DROP INDEX IDX_FEA9FF92A76ED395 ON destinataire');
        $this->addSql('ALTER TABLE destinataire DROP client_id, DROP user_id');
    }
}
