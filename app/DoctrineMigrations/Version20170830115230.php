<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170830115230 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE status ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE status ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE location ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE location ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_user ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE api_user ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE elevator ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE elevator ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE stop_point ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE stop_point ALTER updated_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE api_user ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE api_user ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE location ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE location ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE elevator ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE elevator ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE stop_point ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE stop_point ALTER updated_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE status ALTER created_at SET DEFAULT \'now()\'');
        $this->addSql('ALTER TABLE status ALTER updated_at SET DEFAULT \'now()\'');
    }
}
