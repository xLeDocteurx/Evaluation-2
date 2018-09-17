<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180917074317 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE objects (id INT AUTO_INCREMENT NOT NULL, giver_id INT NOT NULL, taker_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, categorie VARCHAR(255) DEFAULT NULL, received TINYINT(1) NOT NULL, INDEX IDX_B21ACCF375BD1D29 (giver_id), INDEX IDX_B21ACCF3B2E74C3 (taker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objects_users (objects_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_D826BA9D4BEE6933 (objects_id), INDEX IDX_D826BA9D67B3B43D (users_id), PRIMARY KEY(objects_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF375BD1D29 FOREIGN KEY (giver_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE objects ADD CONSTRAINT FK_B21ACCF3B2E74C3 FOREIGN KEY (taker_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE objects_users ADD CONSTRAINT FK_D826BA9D4BEE6933 FOREIGN KEY (objects_id) REFERENCES objects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE objects_users ADD CONSTRAINT FK_D826BA9D67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objects_users DROP FOREIGN KEY FK_D826BA9D4BEE6933');
        $this->addSql('DROP TABLE objects');
        $this->addSql('DROP TABLE objects_users');
    }
}
