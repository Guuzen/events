<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190713182656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE event (id VARCHAR(255) NOT NULL, tariff_ids JSON NOT NULL, order_ids JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN event.id IS \'(DC2Type:app_event_id)\'');
        $this->addSql('COMMENT ON COLUMN event.tariff_ids IS \'(DC2Type:app_tariff_ids)\'');
        $this->addSql('COMMENT ON COLUMN event.order_ids IS \'(DC2Type:app_order_ids)\'');
        $this->addSql('CREATE TABLE "order" (id VARCHAR(255) NOT NULL, event_id VARCHAR(255) NOT NULL, product_id VARCHAR(255) NOT NULL, tariff_id VARCHAR(255) NOT NULL, promocode_id VARCHAR(255) DEFAULT NULL, user_id VARCHAR(255) NOT NULL, sum JSON NOT NULL, maked_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, paid BOOLEAN NOT NULL, cancelled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "order".id IS \'(DC2Type:app_order_id)\'');
        $this->addSql('COMMENT ON COLUMN "order".event_id IS \'(DC2Type:app_event_id)\'');
        $this->addSql('COMMENT ON COLUMN "order".product_id IS \'(DC2Type:app_product_id)\'');
        $this->addSql('COMMENT ON COLUMN "order".tariff_id IS \'(DC2Type:app_tariff_id)\'');
        $this->addSql('COMMENT ON COLUMN "order".promocode_id IS \'(DC2Type:app_promocode_id)\'');
        $this->addSql('COMMENT ON COLUMN "order".user_id IS \'(DC2Type:app_user_id)\'');
        $this->addSql('COMMENT ON COLUMN "order".sum IS \'(DC2Type:app_money)\'');
        $this->addSql('COMMENT ON COLUMN "order".maked_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE ticket (id VARCHAR(255) NOT NULL, event_id VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, reserved BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN ticket.id IS \'(DC2Type:app_ticket_id)\'');
        $this->addSql('COMMENT ON COLUMN ticket.event_id IS \'(DC2Type:app_event_id)\'');
        $this->addSql('CREATE TABLE ticket_tariff (id VARCHAR(255) NOT NULL, event_id VARCHAR(255) NOT NULL, price_net JSON NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN ticket_tariff.id IS \'(DC2Type:app_ticket_tariff_id)\'');
        $this->addSql('COMMENT ON COLUMN ticket_tariff.event_id IS \'(DC2Type:app_event_id)\'');
        $this->addSql('COMMENT ON COLUMN ticket_tariff.price_net IS \'(DC2Type:app_tariff_price_net)\'');
        $this->addSql('CREATE TABLE "user" (id VARCHAR(255) NOT NULL, full_name JSON NOT NULL, contacts JSON NOT NULL, geo JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:app_event_id)\'');
        $this->addSql('COMMENT ON COLUMN "user".contacts IS \'(DC2Type:app_user_contacts)\'');
        $this->addSql('COMMENT ON COLUMN "user".geo IS \'(DC2Type:app_user_geo)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_tariff');
        $this->addSql('DROP TABLE "user"');
    }
}
