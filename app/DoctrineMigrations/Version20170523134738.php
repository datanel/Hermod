<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * First migration, creates the stop_point_location_patch table
 */
class Version20170523134738 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE stop_point_location_patch_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stop_point_location_patch (id INT NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, using_user_geolocation BOOLEAN NOT NULL, stop_point_id VARCHAR(255) NOT NULL, stop_point_name VARCHAR(255) NOT NULL, stop_point_current_lat DOUBLE PRECISION NOT NULL, stop_point_current_lon DOUBLE PRECISION NOT NULL, stop_point_patched_lat DOUBLE PRECISION NOT NULL, stop_point_patched_lon DOUBLE PRECISION NOT NULL, route_id VARCHAR(255) NOT NULL, route_name VARCHAR(255) NOT NULL, user_geolocation_lat DOUBLE PRECISION NOT NULL, user_geolocation_lon DOUBLE PRECISION NOT NULL, user_gps_accuracy DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE stop_point_location_patch_id_seq CASCADE');
        $this->addSql('DROP TABLE stop_point_location_patch');
    }
}
