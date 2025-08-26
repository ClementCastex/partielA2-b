<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250826074236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, quantity_available INT NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, UNIQUE INDEX uniq_equipment_name (name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, order_id INT NOT NULL, equipment_id INT NOT NULL, INDEX IDX_52EA1F098D9F6D38 (order_id), INDEX IDX_52EA1F09517FE9FE (equipment_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rental_order (id INT AUTO_INCREMENT NOT NULL, customer_name VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES rental_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09517FE9FE');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE rental_order');
    }
}
