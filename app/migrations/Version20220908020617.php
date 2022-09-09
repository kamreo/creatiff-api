<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908020617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reaction (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_A4D707F7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reaction_post (reaction_id INT NOT NULL, post_id INT NOT NULL, INDEX IDX_22C1F23A813C7171 (reaction_id), INDEX IDX_22C1F23A4B89032C (post_id), PRIMARY KEY(reaction_id, post_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reaction_comment (reaction_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_5AA9850D813C7171 (reaction_id), INDEX IDX_5AA9850DF8697D13 (comment_id), PRIMARY KEY(reaction_id, comment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reaction ADD CONSTRAINT FK_A4D707F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reaction_post ADD CONSTRAINT FK_22C1F23A813C7171 FOREIGN KEY (reaction_id) REFERENCES reaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reaction_post ADD CONSTRAINT FK_22C1F23A4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reaction_comment ADD CONSTRAINT FK_5AA9850D813C7171 FOREIGN KEY (reaction_id) REFERENCES reaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reaction_comment ADD CONSTRAINT FK_5AA9850DF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reaction_post DROP FOREIGN KEY FK_22C1F23A813C7171');
        $this->addSql('ALTER TABLE reaction_comment DROP FOREIGN KEY FK_5AA9850D813C7171');
        $this->addSql('DROP TABLE reaction');
        $this->addSql('DROP TABLE reaction_post');
        $this->addSql('DROP TABLE reaction_comment');
    }
}
