<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221228140812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auction_user (auction_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_46F88DEA57B8F0DE (auction_id), INDEX IDX_46F88DEAA76ED395 (user_id), PRIMARY KEY(auction_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beat (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, music VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(25) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, banner VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F593AD72BF FOREIGN KEY (beatmaker_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F5935DFCD4B8 FOREIGN KEY (winner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE auction ADD CONSTRAINT FK_DEE4F59373694F FOREIGN KEY (beat_id) REFERENCES beat (id)');
        $this->addSql('ALTER TABLE auction_user ADD CONSTRAINT FK_46F88DEA57B8F0DE FOREIGN KEY (auction_id) REFERENCES auction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE auction_user ADD CONSTRAINT FK_46F88DEAA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F593AD72BF');
        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F5935DFCD4B8');
        $this->addSql('ALTER TABLE auction DROP FOREIGN KEY FK_DEE4F59373694F');
        $this->addSql('ALTER TABLE auction_user DROP FOREIGN KEY FK_46F88DEA57B8F0DE');
        $this->addSql('ALTER TABLE auction_user DROP FOREIGN KEY FK_46F88DEAA76ED395');
        $this->addSql('DROP TABLE auction');
        $this->addSql('DROP TABLE auction_user');
        $this->addSql('DROP TABLE beat');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
